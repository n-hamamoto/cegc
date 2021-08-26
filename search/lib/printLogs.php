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
    print $result["FinalTest"]."点（";
    if($result["FinalTest"]>=80){print "合格";}else{print "不合格";};
    print "）";
    print "</div>";
    print "<div class='date'>";
    print "受験日時 ".$result["End"];
    print "</div>";
    print "</div>";
  }
}

//受講履歴を表示する
function printNiiTrackingLog($oldflg, $pdo, $lang, $title, $eptid, $userid){

  if($oldflg==0){
     $sql = "SELECT * from niiMoodleTracking where ( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? )";
  }else if($oldflg==1){
     $sql = "SELECT * from niiMoodleTracking_old where ( eptid = ? and lang = ? ) or ( eptid = ? and lang = ? )";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute( array($eptid, $lang, $userid, $lang) );

  print "<h2 class='opAndClToggle'>$title</h2>";
  print "<div class='opAndClblock'>";
  print "<table>";

  $empty=1;
  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print "<tr>";
    echo "<td>".$result['field']."</td>";
    echo "<td>".$result['value']."</td>";
    print "</tr>";
    $empty=0;
  }
  if($empty == 1){
   print "受講履歴がありません";
  }
  print "</table>";
  print "</div>";
}
?>
