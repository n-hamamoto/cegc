<?php
include_once("../lib/printLog.php");
include_once("../lib/updateDummy.php");

function update_niiMoodleTracking($lang, $year, $logtable, $logdb, $eppnDomain, $syncall, $syncdate, $dry_run){

	//$dry_run = 0;
	//$dry_run = 1;

	include("../lib/br.php");

 	$cmids  = array();
        $mnames = array();

        // DB接続
        $pdo = pdo_connect_db($logdb);
        //データ検索用sql準備
	if($lang == 'Ja'){
		$shortname = 'rinrin_security-ja';
	}
	if($lang == 'En'){
		$shortname = 'rinrin_security-en';
	}
	if($lang == 'Kr'){
		$shortname = 'rinrin_security-kr';
	}
	if($lang == 'Cn'){
		$shortname = 'rinrin_security-cn';
	}
        $sql = "SELECT * from courseInfo where modname = 'scorm' and courseshortname = '".$shortname."' and visibility = 1 and year = ".$year;
        //print $sql."\n";
        $stmt = $pdo->prepare($sql);
	try {
        	$executed = $stmt->execute();
	} catch (Exception $e){
                print('Select Error:'.$e->getMessage());
                die();
       	}
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		//print $result['coursemoduleid']."\n";
		array_push($cmids,  $result['coursemoduleid']);
		array_push($mnames, $result['modinstancename']);
	}

	//courseidの分だけループ
        for($ii=0; $ii<count($cmids); $ii++){
                //printLog("----------------------------------------");
                //printLog("$cmids[$ii] $mnames[$ii]");
                //printLog("----------------------------------------");
		//タイムアウト対策で，とにかく出力（出力バッファの内容を送信）
		@ob_flush(); @flush();

		$cmid = $cmids[$ii];

		if($syncall == 0){	
			// cmid毎に最終更新日時を取得	
			$sql="SELECT max(updated_at) from niiMoodleTracking where lang = ? and year = ? and eptid != 'dummy' and cmid = ?";
			$stmt = $pdo->prepare($sql);
			$executed = $stmt->execute( array( $lang, $year, $cmid) );
        		if($executed){
                		$data = $stmt->fetch();
				if(isset($data[0])){
                			$lastupdate = new Datetime($data[0]);
                			$lastupdate = $lastupdate->format('U');
				}else{
					$lastupdate = ''; //データがなかったら全データ取得
				}
				if($lastupdate > 0 ){
                			$lastupdate = $lastupdate - 300;//最終更新の5分前のデータを基準にして取得する。
				}
			}
		}elseif($syncall == 1){
			$lastupdate = '';//syncall == 1なら全データ取得
		}elseif($syncall == 2){
                	$lastupdate = new Datetime($syncdate);
                	$lastupdate = $lastupdate->format('U');
		}else{
			die();
		}

                printLog("----------------------------------------");
		if($lastupdate>0){
			$log = "Recieving the $lang record after ".date('Y-m-d H:i:s',$lastupdate)." from GakuNinLMS.";
		}else{
			$log = "Recieving the all $lang record from GakuNinLMS.";
		}
		
                printLog("$log $cmids[$ii] $mnames[$ii]");

                //タイムアウト対策で，とにかく出力（出力バッファの内容を送信）
                @ob_flush(); @flush();

		// APIでGLMSからTrackingの結果を取得
		$html = callReportAPI(1, $cmid, $lastupdate,'');

		//api出力(csv型式)を読み込み
		$arr = preg_split("/\n/", $html);
		//var_dump($arr);

		$records = count($arr)-2;
		if($records >= 0 ){
			printLog("$lang : $records Records Recieved.");
		}else{
			printLog("$lang : error -- response dump");
			var_dump($arr);
			print $br;
			die();
		}

		//print "$logtable $logdb";
		// DB接続
		$pdo = pdo_connect_db($logdb);

		//テーブルのクリア
		if($syncall == 1){
			printLog("Clear existing records");
			if($dry_run == 0){
			$sql='DELETE FROM '.$logtable.' where lang = ? and year = ? and cmid = ?';
			$stmt = $pdo->prepare($sql);
			$executed = $stmt->execute( array( $lang, $year, $cmid) );
			}
		}
	
		//$sql = 'INSERT INTO '.$logtable.' (lang, year, cmid, eptid, Count, Start, LastAccess, Score) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		//$stmt = $pdo->prepare($sql);

		for($i = 1; $i < count($arr)-1; $i++ ){

			//タイムアウト対策で，とにかく出力（出力バッファの内容を送信）
			@ob_flush(); @flush();

			$v = $arr[$i];
			//printLog("No.".$i.": ");

			$arg = array();
			$row = array();

			//各行を配列にする
			$row = preg_split("/,/", $v);

			//言語と年度を設定
			$arg[0] = $lang;
			$arg[1] = $year;
	              	$arg[2] = $cmid; //cmid
			//初期設定
//              	$arg[3] = $row[1]; //ePTID
                	$arg[4] = (int) $row[2]; //Count
//              	$arg[5] = $row[3]; //Start
//              	$arg[6] = $row[4]; //LastAccess
                	$arg[7] = (float) $row[5]; //Score

			if($row[0]==''){ continue; };   //空行をスキップ

			//print "$row[0], $row[1], $row[2], $row[3], $row[4], $row[5]".$br;

			//3列目を学籍番号にする(xxxx@eppn -> xxxx)
			$patten ='/^(.+)@'.$eppnDomain.'$/';
			if(preg_match($patten,$row[1])){
				$tmp = preg_split('/@/',$row[1]);
				$arg[3] = $tmp[0];
        		}

			//print "$row[2], $row[3], $row[4], $row[5], $row[6], $row[7]\n";

			//Startをdatetimeにする
			$skip = 0;
			$patten = '/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+):(\d+)/';
			if(preg_match( $patten, $row[3], $t)){
				$arg[5] = $t[1]."-".$t[2]."-".$t[3]." ".$t[4].":".$t[5].":".$t[6];
			}else{
				$skip = 1;
				$arg[5] = "none";
			}
			//LastAccessをdatetimeにする
			$patten = '/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+):(\d+)/';
        		if(preg_match( $patten, $row[4], $t)){
                		$arg[6] = "$t[1]-$t[2]-$t[3] $t[4]:$t[5]:$t[6]";
			}else{
				$arg[6] = "none";
			}

	//		var_dump($rowdata);
			printLog("No. $i: $arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7]");
			if($skip != 0){printLog("skipped");}
			// 受験を開始していないデータは飛ばす
			if($skip == 0){
				//データを確認
				$sql = "SELECT count(*) FROM $logtable where 
					lang = '".$arg[0]."' AND
					year = $arg[1] AND
					cmid = $arg[2] AND
					eptid = '".$arg[3]."' AND
					Count = $arg[4] AND
					Start = '".$arg[5]."' AND
					LastAccess = '".$arg[6]."' AND
					Score = $arg[7]";
				//print($sql);
				$stmt = $pdo->prepare($sql);
				try{
                                	$executed = $stmt->execute();
					$count = $stmt->fetchColumn();
                        	} catch (Exception $e){
                                	print('Error:'.$e->getMessage());
                                	die();
                        	}
				//重複データがあったら消す
				if($count > 0){
                        		$sql = "DELETE FROM $logtable where
                                		lang = '".$arg[0]."' AND
                                		year = $arg[1] AND
                                		cmid = $arg[2] AND
                                		eptid = '".$arg[3]."' AND
                                		Count = $arg[4] AND
                                		Start = '".$arg[5]."' AND
                                		LastAccess = '".$arg[6]."' AND
                                		Score = $arg[7]";
                        		$stmt = $pdo->prepare($sql);
                        		try{
                                		$executed = $stmt->execute();
                        		} catch (Exception $e){
                                		print('Error:'.$e->getMessage());
                                		die();
                        		}
				}
		
				//データ追加用sql準備
				$sql = 'INSERT INTO '.$logtable.' (lang, year, cmid, eptid, Count, Start, LastAccess, Score) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
				$stmt = $pdo->prepare($sql);
				//SQL実行
/* */
				if($dry_run == 0){
					try{
						$executed = $stmt->execute(array($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7]));
						printLog("$sql, @arg");
					} catch (Exception $e){
						print('Import Error:'.$e->getMessage());
						die();
					}
				}
			}
/* */
		}
	}

	if($dry_run == 0){
		//アップデート日時登録用のダミーデータを登録
        	updateDummy($logtable, $lang, $year);
	}
}
?>
