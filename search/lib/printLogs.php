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

//成績を表示する
function printNiiMoodleLog($oldflg, $pdo, $lang, $title, $eptid, $userid){

  $sql = null;
  $input = array();

  if($oldflg == 0){
     $sql = $sql.sprintf("SELECT * from niiMoodleLog where ( eptid = '%s' and lang = '%s' ) or ( eptid = '%s' and lang = '%s' )",$eptid,$lang,$userid,$lang);
  }else if($oldflg == 1){
     $sql = $sql.sprintf("SELECT * from niiMoodleLog_old where ( eptid = '%s' and lang = '%s' ) or ( eptid = '%s' and lang = '%s' )",$eptid,$lang,$userid,$lang);
  }

  print "<h2>".$title."</h2>";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print "<div>";
    print "<div class='score'>";
    print $result["FinalTest"]."点";
//    if($result["FinalTest"]>=80){print "(合格)";}else{print "(不合格)";};
    print "</div>";
    print "<div class='date'>";
    print "受験終了日時 ".$result["End"]."(".$result['ElapsedTime']."秒)";
    print "</div>";
    print "</div>";
  }
}

//受講履歴を表示する
function printNiiTrackingLog($oldflg, $pdo, $lang, $title, $eptid, $userid){

  if($oldflg==0){
     // $sql = "SELECT * from niiMoodleTracking where ( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? )";
     $sql = "select * from niiMoodleTracking inner join courseInfo on niiMoodleTracking.cmid = courseInfo.coursemoduleid where ( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? )";
  }else if($oldflg==1){
     $sql = "SELECT * from niiMoodleTracking_old where ( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? )";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute( array($eptid, $lang, $userid, $lang) );

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

  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
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
?>
