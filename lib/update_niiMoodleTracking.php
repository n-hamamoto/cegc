<?php
include_once("../lib/printLog.php");
include_once("../lib/updateDummy.php");

function update_niiMoodleTracking($lang, $year, $logtable, $logdb, $eppnDomain, $lastupdate){

	include("../auth/login.php");

 	$cmids  = array();
        $mnames = array();

	if($lastupdate > 0){
		$log = "Recieving the $lang data after ".date('Y-m-d H:i:s',$lastupdate)." from GakuNinLMS.";
		printLog($log);
	}

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
        $sql = "SELECT * from courseInfo where modname = 'scorm' and courseshortname = '".$shortname."' and visibility = 1";
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
                printLog("----------------------------------------");
                printLog("$cmids[$ii] $mnames[$ii]");
                //printLog("----------------------------------------");
		//タイムアウト対策で，とにかく出力（出力バッファの内容を送信）
		@ob_flush(); @flush();
		
		$cmid = $cmids[$ii];

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
		}

		//print "$logtable $logdb";
		// DB接続
		$pdo = pdo_connect_db($logdb);

		//テーブルのクリア
		if($lastupdate == ''){
			printLog("Clear existing records");
			$sql='DELETE FROM '.$logtable.' where lang = ? and year = ? and cmid = ?';
			$stmt = $pdo->prepare($sql);
			$executed = $stmt->execute( array( $lang, $year, $cmid) );
		}
	
		//データ追加用sql準備
		$sql = 'INSERT INTO '.$logtable.' (lang, year, cmid, eptid, Count, Start, LastAccess, Score) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$stmt = $pdo->prepare($sql);

		for($i = 1; $i < count($arr)-1; $i++ ){

			//タイムアウト対策で，とにかく出力（出力バッファの内容を送信）
			@ob_flush(); @flush();

			$v = $arr[$i];
			printLog("No.".$i.": ");

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

//			print "$row[0], $row[1], $row[2], $row[3], $row[4], $row[5]\n";

			//3列目を学籍番号にする(xxxx@eppn -> xxxx)
			$patten ='/^(.+)@'.$eppnDomain.'$/';
			if(preg_match($patten,$row[1])){
				$tmp = preg_split('/@/',$row[1]);
				$arg[3] = $tmp[0];
        		}

			//print "$row[2], $row[3], $row[4], $row[5], $row[6], $row[7]\n";

			//Startをdatetimeにする
			$patten = '/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+):(\d+)/';
			if(preg_match( $patten, $row[3], $t)){
				$arg[5] = $t[1]."-".$t[2]."-".$t[3]." ".$t[4].":".$t[5].":".$t[6];
			}
			//LastAccessをdatetimeにする
			$patten = '/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+):(\d+)/';
        		if(preg_match( $patten, $row[4], $t)){
                		$arg[6] = "$t[1]-$t[2]-$t[3] $t[4]:$t[5]:$t[6]";
        		}

	//		var_dump($rowdata);
			printLog("$arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6] $arg[7]");

			//SQL実行
/* */
			try{
				$executed = $stmt->execute(array($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7]));
			} catch (Exception $e){
				print('Import Error:'.$e->getMessage());
				die();
			}
/* */
		}
	}

	//アップデート日時登録用のダミーデータを登録
	updateDummy($logtable, $lang, $year);
/*
	//旧dummyを削除
	$sql = 'DELETE FROM '.$logtable.' where lang = ? and year = ? and eptid = ?';
	$stmt = $pdo->prepare($sql);

        try{
                $executed = $stmt->execute(array($lang, $year, 'dummy'));
        } catch (Exception $e){
                print('Import Error:'.$e->getMessage());
                die();
        }

	//dummyを登録
	$sql = 'INSERT INTO '.$logtable.' (lang, year, eptid) VALUES (?, ?, ?)';
	$stmt = $pdo->prepare($sql);

	try{
		$executed = $stmt->execute( array($lang, $year, 'dummy') );
	} catch (Exception $e){
		print('Import Error:'.$e->getMessage());
		die();
	}
*/
}
?>
