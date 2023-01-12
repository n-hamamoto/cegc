<?php
session_start();
include_once("../../conf/config.php");
include_once("../../auth/login.php");
include_once("../../lib/dblib.php");
include_once("../../lib/id.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}

//成績を取得する
function getNiiMoodleLog($oldflg, $lang, $eptid, $userid){
	include("../../conf/config.php");

	/* DB接続 */
	$pdo = pdo_connect_db($logdb);

	$sql = null;
	$input = array();

	if($oldflg == 0){
		$stmt = $pdo->prepare("
			SELECT * from niiMoodleLog 
			where 
			( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? )
		");
		$stmt->execute( array( $eptid, $lang, $userid, $lang ) );

	}else if($oldflg == 1){
		$stmt = $pdo->prepare("
			SELECT * from niiMoodleLog_old 
			where ( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? )
		");
		$stmt->execute( array( $eptid, $lang, $userid, $lang ) );
	}

	$res=array();
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    		array_push($res, $result);
	}

	return $res;

	/* DB切断 */
	$pdo = null;
}

//成績を表示する
function printNiiMoodleLog($oldflg, $pdo, $lang, $title, $eptid, $userid){
	include("../../conf/config.php");
  	print "<h2>".$title."</h2>";

	$res = getNiiMoodleLog($oldflg,$lang,$eptid,$userid);

	foreach($res as $value){
     		print "<div>";
     		print "<div class='score'>";
     		if($value["FinalTest"] >= $passingScore and $printPassingStatus == 1){print "<strong>";}
     		print $value["FinalTest"]."点";
     		if($value["FinalTest"] >= $passingScore and $printPassingStatus == 1){print "</strong>";}
     		print "</div>";
     		print "<div class='date'>";
     		print "受験終了日時 ".$value["End"]."(".$value['ElapsedTime']."秒)";
     		print "</div>";
     		print "</div>";
	}
}

//受講履歴を取得する
function getNiiTrackingLog($oldflg, $lang, $eptid, $userid){

        include("../../conf/config.php");

        /* DB接続 */
        $pdo = pdo_connect_db($logdb);

        $sql = null;
        $input = array();

	if($oldflg==0){
     		$stmt = $pdo->prepare("
			select * from niiMoodleTracking 
			inner join courseInfo on niiMoodleTracking.cmid = courseInfo.coursemoduleid 
			where ( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? ) 
		");
               	$stmt->execute( array( $eptid, $lang, $userid, $lang ) );

  	}else if($oldflg==1){
     		$stmt = $pdo->prepare("
			SELECT * from niiMoodleTracking_old 
			where ( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? )
		");
               	$stmt->execute( array( $eptid, $lang, $userid, $lang ) );
  	}

	$res=array();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($res, $result);
        }

        return $res;

        /* DB切断 */
        $pdo = null;
}

//受講履歴を表示する
function printNiiTrackingLog($oldflg, $pdo, $lang, $title, $eptid, $userid){

  	$res = getNiiTrackingLog($oldflg,$lang,$eptid,$userid);

  	$ths   = array(); 
  	$ths[] = array('column' =>'field', 	'title' =>'項目');
	//  $ths[] = array('column' =>'coursefullname', 	'title' =>'コース名');
  	$ths[] = array('column' =>'modinstancename', 	'title' =>'モジュール名');
  	$ths[] = array('column' =>'value', 	'title' =>'値');
  	$ths[] = array('column' =>'cmid', 	'title' =>'コースID');
  	$ths[] = array('column' =>'Score', 	'title' =>'スコア');
  	$ths[] = array('column' =>'Start', 	'title' =>'開始日時');
  	$ths[] = array('column' =>'LastAccess','title' =>'最終アクセス');
 
  	$tout = array(); 

  	$empty=1;

  	print "<h2 class='opAndClToggle'>$title</h2>";
  	print "<div class='opAndClblock'>";

  	$i=0;
  	$r = array();

  	foreach($res as $result){
    		$empty=0;

    		foreach( $ths as $th ){
			$r[$i][$th['column']] = $result[$th['column']];
			if( $result[$th['column']] != '' ){
		 		$tout[$th['column']] = 1;
			}
    		}
    		$i++;
  	}
  	$imax = $i;

  	$tb = "";

	// Table Head
  	$tb = $tb."<tr>";
  	foreach( $ths as $th ){
      		if($tout[$th['column']] == 1){
              		$tb = $tb."<th>".$th['title']."</th>";
      		}
  	}

	//Table Data
  	for($i=0; $i<$imax; $i++){
    		$tb = $tb."<tr>"; 
    		foreach( $ths as $th ){
			if($tout[$th['column']] == 1){
        			$tb = $tb."<td>".$r[$i][$th['column']]."</td>";
			} 
   		}
   		$tb = $tb."<tr>";
  	}

	// テーブル出力
  	print "<table>";
  	print "$tb";
  	print "</table>";

  	if($empty == 1){
    		print "受講履歴がありません";
  	}

  	print "</div>";
}

// 合格を判定する
function coursePassed($lang, $eptid, $userid){

	include("../../conf/config.php");
	$passed = 0;

	//最終テストの成績が設定値以上
	$passFinalTest = 0;
	$oldflg = 0;
	$res = getNiiMoodleLog($oldflg,$lang,$eptid,$userid);

	foreach($res as $value){
        	//print"<pre>";
        	// var_dump($value);
        	//print "$value[eptid] $value[FinalTest] $value[ElapsedTime] $value[Start]";
        	//print"</pre>";
		if($value['FinalTest'] >= $passingScore){
			$passFinalTest = 1;
		}
	}

        //旧りんりん姫で最終テストの成績が80点以上
	$passFinalTest_old = 0;
        $oldflg = 1;
        $res = getNiiMoodleLog($oldflg,$lang,$eptid,$userid);

        foreach($res as $value){
                //print"<pre>";
                // var_dump($value);
                // print "$value[eptid] $value[FinalTest] $value[ElapsedTime] $value[Start]";
                //print"</pre>";
                if($value['FinalTest'] >= 80){
                        $passFinalTest_old = 1;
                }
        }


	$complete_ratio = 0;
	if($requireCompleteCourses == 1){
		/* DB接続 */
		$pdo = pdo_connect_db($logdb);

		//コースを全て受講済み
		$passTracking = 0;

  		$stmt = $pdo->prepare("
		SELECT sum(T.maxscore) FROM (
			SELECT eptid, cmid, max(score) as maxscore 
			FROM niiMoodleTracking, courseInfo 
			where ( eptid = ? or eptid = ? )
				and lang = ? 
				and visibility = '1' 
				and cmid = coursemoduleid 
			group by eptid, cmid
			)T
		");
		$stmt->execute( array( $eptid, $userid, $lang ) );

  		$complete_ratio_output = "";
  		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
     			$complete_ratio = $result['sum(T.maxscore)']/8;
     			if($complete_ratio == 100){
        			$complete_ratio_output = "<strong>".htmlspecialchars($complete_ratio)."</strong>";
        			$passTracking = 1;
    			}else{
        			$complete_ratio_output = $complete_ratio;
     			}
			//print($complete_ratio);
  		}
	}

		$pdo = null;
/*
	foreach($res as $value){
        	print"<pre>";
        	// var_dump($value);
        	print "$value[eptid] $value[field] $value[modinstancename] $value[value] $value[cmid] $value[Score] $value[Start] $value[LastAccess]";
        	print"</pre>";
	}
*/

	//合否判定
	$passed_old = 0;
	$passed_new = 0;
	//旧りんりん姫で合格なら合格
	if($passFinalTest_old == 1){
		$passed_old = 1;
	}
	//新りんりん姫での合格判定
	if($requireCompleteCourses == 1){
		if($passFinalTest * $passTracking > 0){
			$passed_new = 1;
		}
	}else{
		if($passFinalTest == 1){
			$passed_new = 1;
		}
	}
	// print "$passed_old $passed_new $passFinalTest_old";
	$passed = 2*$passed_old + $passed_new; 
	// 3 passed by new and old, 
	// 2 passed by old
	// 1 passed by new
	// 0 failed
	// print $passed;
	return array($passed,$complete_ratio);

/*
	print"<pre>";
	var_dump($res_moodle);
	var_dump($res_tracking);
	print"</pre>";
*/
}
?>
