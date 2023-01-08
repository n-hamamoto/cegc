<?php

//include_once("../conf/config.php");
//include_once("../lib/dblib.php");

//require("callReportAPI.php");

function update_niiMoodleLog($lang, $year, $logtable, $logdb, $eppnDomain, $lastupdate){

	include("../auth/login.php");

	print "Recieving the $lang data after ".date('Y-m-d H:i:s',$lastupdate)." from GakuNinLMS.".$br;

        $cmids  = array();
        $mnames = array();

        // DB接続
        $pdo = pdo_connect_db($logdb);
        //データ検索用sql準備
        if($lang == 'Ja'){
                $sql = "SELECT * from courseInfo where modname = 'quiz' and courseshortname = 'rinrin_security-ja' and visibility = 1";
        }
        if($lang == 'En'){
                $sql = "SELECT * from courseInfo where modname = 'quiz' and courseshortname = 'rinrin_security-en' and visibility = 1";
        }
        if($lang == 'Kr'){
                $sql = "SELECT * from courseInfo where modname = 'quiz' and courseshortname = 'rinrin_security-kr' and visibility = 1";
        }
        if($lang == 'Cn'){
                $sql = "SELECT * from courseInfo where modname = 'quiz' and courseshortname = 'rinrin_security-cn' and visibility = 1";
        }
        //print $sql.$br;
        $stmt = $pdo->prepare($sql);
        try {
                $executed = $stmt->execute();
        } catch (Exception $e){
                print('Select Error:'.$e->getMessage());
                die();
        }
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                print $result['coursemoduleid'].": ".$result['modinstancename'].$br;
                array_push($cmids,  $result['coursemoduleid']);
                array_push($mnames, $result['modinstancename']);
        }
	$cmid  = $cmids[0];
	$mname = $mnames[0];
/*
	$html = callReportAPI(0, '', '', '');
	$obj = json_decode($html);
	foreach($obj as $o){
		//print "###\n";
		//var_dump($o);
		//print $o->modinstancename."\n"; 
		//print $o->courseshortname."\n"; 
		//print $o->modname."\n"; 
		//print $o->visibility."\n"; 
		//print $o->coursemoduleid."\n"; 
	
		if($lang=='Ja'){
			if($o->courseshortname == "rinrin_security-ja" && $o->modname == "quiz"){
				$cmid = $o->coursemoduleid;
			}
		}
                if($lang=='En'){
                        if($o->courseshortname == "rinrin_security-en" && $o->modname == "quiz"){
                                $cmid = $o->coursemoduleid;
                        }
                }
                if($lang=='Kr'){
                        if($o->courseshortname == "rinrin_security-cn" && $o->modname == "quiz"){
                                $cmid = $o->coursemoduleid;
                        }
                }
                if($lang=='Cn'){
                        if($o->courseshortname == "rinrin_security-kr" && $o->modname == "quiz"){
                                $cmid = $o->coursemoduleid;
                        }
                }
	}
	//print "cmid: $cmid\n";
*/
	//if($lang=='Ja'){ $cmid = 1565; };
	//if($lang=='En'){ $cmid = 1566; };
	//if($lang=='Kr'){ $cmid = 1567; };
	//if($lang=='Cn'){ $cmid = 1568; };

	// APIでGLMSからfinalTestの結果を取得
	$html = callReportAPI(1, $cmid, $lastupdate, '');

	//api出力(csv型式)を読み込み
	$arr = preg_split("/\n/", $html);
	//var_dump($arr);

	$records = count($arr)-2;
	print "$records Records Recieved.".$br;


	//print "$logtable $logdb";
	// DB接続
	$pdo = pdo_connect_db($logdb);

	//テーブルのクリア
	if($lastupdate == ''){
		print "Clear existing records\n";
		$sql='DELETE FROM '.$logtable.' where lang = ? and year = ?';
		$stmt = $pdo->prepare($sql);
		$executed = $stmt->execute( array( $lang, $year) );
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

		print "$row[2], $row[3], $row[4], $row[5], $row[6], $row[7]\n";

		//Startをdatetimeにする
		$patten = '/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+):(\d+)/';
		if(preg_match( $patten, $row[4], $t)){
			$rowdata[4] = $t[1]."-".$t[2]."-".$t[3]." ".$t[4].":".$t[5].":".$t[6];
		}
		//Endをdatetimeにする
		$patten = '/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+):(\d+)/';
        	if(preg_match( $patten, $row[5], $t)){
                	$rowdata[5] = "$t[1]-$t[2]-$t[3] $t[4]:$t[5]:$t[6]";
        	}
		//ElapsedTimeをdoubleにする
		$patten = '/(\d+)秒/';
		if(preg_match( $patten, $row[6], $t)){
			$rowdata[6] = $t[1];
		}

//		$rowdata[2] = $row[2]; //ePTID
		$rowdata[3] = $row[3]; //Status
//		$rowdata[4] = $row[4]; //Start
//		$rowdata[5] = $row[5]; //End
//		$rowdata[6] = $row[6]; //ElepsedTime
		$rowdata[7] = $row[7]; //FinalTest

		print "$rowdata[0], $rowdata[1], $rowdata[2], $rowdata[3], $rowdata[4], $rowdata[5], $rowdata[6], $rowdata[7]".$cr;

		//SQL実行
		try{
			$executed = $stmt->execute($rowdata);
		} catch (Exception $e){
			print("Import Error:".$e->getMessage());
			die();
		}
	}
}
?>
