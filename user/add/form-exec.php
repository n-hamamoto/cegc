<?php
session_start();
include_once("../../conf/config.php");
include("../../auth/login.php");
include_once("../../lib/dblib.php");
include_once("../../lib/function.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isGroupAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}

//nullでない場合0を返す関数
function conv_null_zero($var){
	 if(isset($var)){return $var;}else{return 0;}
}

$userId = chk_input($_POST["userId"],'ユーザID');
$isAdmin = conv_null_zero($_POST["isAdmin"]);
$isTeacher = conv_null_zero($_POST["isTeacher"]);
$isSubAdmin = conv_null_zero($_POST["isSubAdmin"]);
$isGroupAdmin = conv_null_zero($_POST["isGroupAdmin"]);

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

xss_char_echo($userId);
print "を作成しました";
print '<input type="button" value="OK" onclick="location.reload();" /> ';

//切断
$pdo = null;
?>