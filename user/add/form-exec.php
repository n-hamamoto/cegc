<?php
session_start();
include_once("../../conf/config.php");
include("../../auth/login.php");
include_once("../../lib/dblib.php");
include_once("../../lib/function.php");
//権限のない人はログアウト
requireGroupAdmin();

$userId = chk_input($_POST["userId"],'ユーザID');

$isAdmin    = $_POST["isAdmin"] ?? 0;// nullの場合は 0
$isTeacher  = $_POST["isTeacher"] ?? 0;
$isSubAdmin = $_POST["isSubAdmin"] ?? 0;
$isGroupAdmin = $_POST["isGroupAdmin"] ?? 0;

$sql = "INSERT INTO user (userId, isAdmin, isTeacher, isSubAdmin, isGroupAdmin) 
VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE 
isAdmin = ?,
isTeacher = ?,
isSubAdmin = ?,
isGroupAdmin = ?;
";

$pdo  = pdo_connect_db($logdb);
$stmt = $pdo->prepare($sql);
$data = $stmt->execute( 
array( 
$userId, $isAdmin, $isTeacher, $isSubAdmin, $isGroupAdmin,
$isAdmin, $isTeacher, $isSubAdmin, $isGroupAdmin
) );

print "<p>";
xss_char_echo($userId);
print "を作成しました</p>";
print '<input type="button" value="OK" onclick="location.reload();" /> ';

//切断
$pdo = null;
?>
