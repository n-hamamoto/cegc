<?php
session_start();
include_once("../../conf/config.php");
include_once("../../auth/login.php");
include_once("../../lib/dblib.php");
include_once("../../lib/function.php");
//権限のない人はログアウト
requireGroupAdmin();

$groupId = $_POST['groupId'];
$userId = chk_input($_POST['userId'],'ユーザID');
//print $userId;
//print_r($groupId);

$pdo = pdo_connect_db($logdb);
$stmt= $pdo->prepare("DELETE FROM groupAdmin where userId =( ? )");
$stmt->execute( array( $userId ) );

if(!empty($userId) && !empty($groupId) ){
  foreach($groupId as $gid){
    $stmt= $pdo->prepare("INSERT INTO groupAdmin (userId, groupId) VALUES( ? , ? )");
    $stmt->execute( array( $userId, $gid ) );
  }
}

$pdo = null;
print "グループ管理者を登録しました<br>";
print '<input type="button" value="OK" onclick="location.reload();" /> ';


?>
