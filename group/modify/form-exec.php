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
$memberList = chk_input($_POST["memberList"],'メンバー');

$pdo = pdo_connect_db($logdb);
$stmt = $pdo->prepare("DELETE FROM groupMember where groupId = ?");
$stmt->execute( array( $groupId ) );

$member = preg_split("/\n/",$memberList);
foreach($member as $m){
  $mm = trim($m);
  if(!empty($mm)){
    $stmt = $pdo->prepare("INSERT INTO groupMember(idNumber, groupId) VALUES( ? , ? )");
    $stmt->execute( array( $mm, $groupId ) );
  }
}

$pdo = null;

print "グループメンバーを変更しました。";
print '<input type="button" value="OK" onclick="location.reload();" /> ';

//print "$groupId<br>";
//print "$sql<br>";
//print "$sql<br>";
?>