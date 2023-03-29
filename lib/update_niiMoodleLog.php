<?php
include_once("../lib/printLog.php");
include_once("../lib/updateDummy.php");

function update_niiMoodleLog($lang, $year, $logtable, $logdb, $eppnDomain, $lastupdate){

	//$dry_run = 1;
	$dry_run = 0;

	include("../auth/login.php");

	if($lastupdate > 0){
		$log = "Recieving the $lang data after ".date('Y-m-d H:i:s',$lastupdate)." from GakuNinLMS.";
		printLog($log);
	}

        $cmids  = array();
        $mnames = array();

        // DB接続
        $pdo = pdo_connect_db($logdb);
        //データ検索用sql準備
        if($lang == 'Ja'){
		$shortname='rinrin_security-ja';
        }
        if($lang == 'En'){
		$shortname='rinrin_security-en';
        }
        if($lang == 'Kr'){
		$shortname='rinrin_security-kr';
        }
        if($lang == 'Cn'){
		$shortname='rinrin_security-cn';
        }
	$sql = "SELECT * from courseInfo where modname = 'quiz' and courseshortname = '".$shortname."' and visibility = 1";
        //print $sql;
	
	$stmt = $pdo->prepare($sql);
        try {
                $executed = $stmt->execute();
        } catch (Exception $e){
                print('Select Error:'.$e->getMessage());
                die();
        }
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		//printLog($result['coursemoduleid'].": ".$result['modinstancename']);
                array_push($cmids,  $result['coursemoduleid']);
                array_push($mnames, $result['modinstancename']);
        }
	$cmid  = $cmids[0];
	$mname = $mnames[0];

	// APIでGLMSからfinalTestの結果を取得
	$html = callReportAPI(1, $cmid, $lastupdate, '');

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
		printLog("Clear existing records of year $year");
		$sql='DELETE FROM '.$logtable.' where lang = ? and year = ?';
		if( $dry_run == 0 ){
		$stmt = $pdo->prepare($sql);
		$executed = $stmt->execute( array( $lang, $year) );
		}
	}

	//データ追加用sql準備
	$stmt = $pdo->prepare("INSERT INTO
	$logtable (lang,year,eptid,Status,Start,End,ElapsedTime,FinalTest) VALUES(?, ?, ?, ?, ?, ?, ?, ?);");

	//print "$logdb";
	//print "$logtable";
	foreach($arr as $v){
//		print "No.".$i;
//		print $v;
//		print "\n";
                
		//タイムアウト対策で，とにかく出力（出力バッファの内容を送信）
                @ob_flush(); @flush();

		$rowdata = array();
		$row = array();

		//言語と年度を設定
		$rowdata[0] = $lang;
		$rowdata[1] = $year;

		//各行を配列にする
		$row = preg_split("/,/", $v);

		if($row[0]=="姓"){ continue; }; // タイトル行をスキップ
		if($row[0]==''){ continue; };   //空行をスキップ

//		print "$row[2], $row[3], $row[4], $row[5], $row[6], $row[7]\n";

		//2列目を学籍番号にする(xxxx@eppn -> xxxx)
		$patten ='/^(.+)@'.$eppnDomain.'$/';
		if(preg_match($patten,$row[2])){
			$tmp = preg_split('/@/',$row[2]);
			$rowdata[2] = $tmp[0];
        	}

		//printLog("$row[2], $row[3], $row[4], $row[5], $row[6], $row[7]");

		//Startをdatetimeにする
		$patten = '/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+):(\d+)/';
		if(preg_match( $patten, $row[4], $t)){
			$rowdata[4] = $t[1]."-".$t[2]."-".$t[3]." ".$t[4].":".$t[5].":".$t[6];
		}
		//Endをdatetimeにする
		$patten = '/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+):(\d+)/';
        	if(preg_match( $patten, $row[5], $t)){
                	$rowdata[5] = "$t[1]-$t[2]-$t[3] $t[4]:$t[5]:$t[6]";
        	}else{
			$rowdata[5]=null;
		}
		//ElapsedTimeをdoubleにする
		$patten = '/(\d+)秒/';
		if(preg_match( $patten, $row[6], $t)){
			$rowdata[6] = $t[1];
		}else{
			$rowdata[6]=null;
		}

//		$rowdata[2] = $row[2]; //ePTID
		$rowdata[3] = $row[3]; //Status
//		$rowdata[4] = $row[4]; //Start
//		$rowdata[5] = $row[5]; //End
//		$rowdata[6] = $row[6]; //ElepsedTime
		$rowdata[7] = $row[7]; //FinalTest
		$patten = '/-/';
		if(preg_match( $patten, $row[7], $t)){
			$rowdata[7]=null;
		}
		
		printLog("$rowdata[0], $rowdata[1], $rowdata[2], $rowdata[3], $rowdata[4], $rowdata[5], $rowdata[6], $rowdata[7]");

		//SQL実行
		if( $dry_run == 0 ){
			try{
				$executed = $stmt->execute($rowdata);
			} catch (Exception $e){
				print("Import Error:".$e->getMessage());
				die();
			}
		}
	}

        //アップデート日時登録用のダミーデータを登録
        updateDummy($logtable, $lang, $year);
}
?>
