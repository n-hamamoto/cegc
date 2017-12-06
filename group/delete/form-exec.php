<?php
session_start();
include_once("../../auth/login.php");
include_once("../../conf/config.php");
include_once("../../lib/dblib.php");
include_once("../../lib/function.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isGroupAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}

$groupId = chk_input($_POST["name"],'グループ名');
$year = chk_input($_POST["year"],'年度');

if($groupId < 0){
  print "グループを選択してください<br>";
  print '<input type="button" value="OK" onclick="location.reload();" /> ';
  exit();	    
}
$pdo = pdo_connect_db($logdb);

$stmt = $pdo->prepare("DELETE FROM groupMember where groupId = ? ");
$stmt->execute( array($groupId) );

$stmt = $pdo->prepare("DELETE FROM groupInfo where groupId = ? ");
$stmt->execute( array($groupId) );

$stmt = $pdo->prepare("DELETE FROM groupAdmin where groupId = ? ");
$stmt->execute( array($groupId) );

$pdo = null;

print "グループを削除しました<br>";
print '<input type="button" value="OK" onclick="location.reload();" /> ';

?>