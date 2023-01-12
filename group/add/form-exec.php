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

$groupName = chk_input($_POST["groupName"],'グループ名');
$memberList = chk_input($_POST["memberList"],'メンバー');
$year = chk_input($_POST["year"],'年度');

$pdo = pdo_connect_db($logdb);

$sql = "INSERT INTO groupInfo(groupName,year) VALUES( ?, ? )";
$stmt = $pdo->prepare($sql);
$stmt->execute( array( $groupName, $year ) );

//切断
$pdo = null;

//グループメンバー登録
//print "Add member of $groupName<br>";

$pdo = pdo_connect_db($logdb);

$sql = "SELECT groupId from groupInfo where groupName = ? AND year = ? ";
$stmt = $pdo->prepare($sql);
$stmt->execute( array( $groupName, $year ) );
$result= $stmt->fetch(PDO::FETCH_ASSOC);
$groupId = $result['groupId'];

$member = preg_split("/\n/",$memberList);

foreach($member as $m){
  $mm = trim($m);
  if(!empty($mm)){
    $sql  = "INSERT INTO groupMember(idNumber, groupId) VALUES( ?, ? )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute( array($mm, $groupId) );
  }
}

//切断
$pdo = null;

print "<p>グループを登録しました</p>";
print '<input type="button" value="OK" onclick="location.reload();" /> ';
?>
