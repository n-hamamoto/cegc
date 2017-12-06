<?php
session_start();
include_once("../../conf/config.php");
include("../../auth/login.php");
include("../../lib/dblib.php");
include("../../lib/id.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}
$userid = $_POST['name'];
?>
<script type="text/javascript" src="https://<?php echo $documentRoot ?>/js/resultField.js"></script>
<?php

$eptid = getEptid($userid);

//成績を表示する
function printNiiMoodleLog($pdo, $lang, $title, $eptid){

  $sql = null;
  $input = array();
  $sql = $sql.sprintf("SELECT * from niiMoodleLog where eptid = '%s' and lang = '%s'",$eptid,$lang);

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
    print "受験日時 ".$result["Start"];
    print "</div>";
    print "</div>";
  }

}

//受講履歴を表示する
function printNiiTrackingLog($pdo, $lang, $title, $eptid){

  $sql = "SELECT * from niiMoodleTracking where eptid = ? and lang = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute( array($eptid, $lang) );
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  print "<h2 class='opAndClToggle'>$title</h2>";
  print "<div class='opAndClblock'>";
  print "<table>";
  if(is_array($result)){
   foreach($result as $key => $value){
    print "<tr>";
    if($key == "eptid"){
    }elseif($key == "Status"){
    }elseif($key == "Start"){
    }elseif($key == "Latest"){
      echo "<td>"."最終受講日時"."</td><td>".$value."</td>";
    }elseif($key == "Score"){
      echo "<td>"."完了した数"."</td><td>".$value."/82</td>";
    }else{
      echo "<td>".$key."</td><td>".$value."</td>";
    }
    print "</tr>";
   }
  }else{
   print "受講履歴がありません";
  }
  print "</table>";
  print "</div>";
}

/* DB接続 */
$pdo = pdo_connect_db($logdb);
# $dsn = "mysql:dbname=$logdb;host=$dbhost;charset=utf8";
# try {
#   $pdo = new PDO( $dsn, $dbuser, $dbpassword );
# }catch (PDOException $e){
#   print ('Error:'.$e->getMessage());
#   die(); 
# }

$title = "総合テスト(Ja)";
$lang = "Ja";
printNiiMoodleLog($pdo, $lang, $title, $eptid);

$title = "総合テスト(En)";
$lang = "En";
printNiiMoodleLog($pdo, $lang, $title, $eptid);

$title = "総合テスト(Cn)";
$lang = "Cn";
printNiiMoodleLog($pdo, $lang, $title, $eptid);

$title = "総合テスト(Kr)";
$lang = "Kr";
printNiiMoodleLog($pdo, $lang, $title, $eptid);

$lang="Ja";
$title = "受講状況(Ja)";
printNiiTrackingLog($pdo, $lang, $title, $eptid);

$lang="En";
$title = "受講状況(En)";
printNiiTrackingLog($pdo, $lang, $title, $eptid);

$lang="Cn";
$title = "受講状況(Cn)";
printNiiTrackingLog($pdo, $lang, $title, $eptid);

$lang="Kr";
$title = "受講状況(Kr)";
printNiiTrackingLog($pdo, $lang, $title, $eptid);

?>
