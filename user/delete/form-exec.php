<?php
session_start();
include_once("../../conf/config.php");
include("../../auth/login.php");
include_once("../../lib/dblib.php");
include_once("../../lib/function.php");
//権限のない人はログアウト
requireGroupAdmin();

$userId = chk_input($_POST["userId"],'ユーザID');

$pdo = pdo_connect_db($logdb);

$sql = sprintf("DELETE FROM user where userId = ?");
$stmt= $pdo->prepare($sql);
$data= $stmt->execute( array( $userId ) );

$sql = sprintf("DELETE FROM groupAdmin where userId = ?");
$stmt= $pdo->prepare($sql);
$data= $stmt->execute( array( $userId ) );

print "<p>";
xss_char_echo($userId);
print "を削除しました</p>";
print '<input type="button" value="OK" onclick="location.reload();" /> ';

//切断
$pdo = null;
?>
