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
?>
<html>
<head>
<?php include("../../lib/header.php");?>
</head>
<body>
<?php include("../../lib/menu.php");?>
<div id="main">
<h1>ユーザ登録</h1>
<form id="inputForm" action="user/add/form-exec.php" method="post">
<p>
ユーザID<input type="text" name="userId" size="30">
<div style="padding-left:10px;margin:10px;">
<!--
<label for="isTeacher">
<input type="checkbox" name="isTeacher" id="isTeacher" value="1">
担当グループ成績閲覧権限（授業担当教員）
</label>
-->
<br>
<?php
if($_SESSION['isAdmin'] == 1){
print '
<label for="isSubAdmin">
<input type="checkbox" name="isSubAdmin" id="isSubAdmin" value="1">
全成績閲覧権限（成績確認担当者）</label>
<br>
<label for="isGroupAdmin">
<input type="checkbox" name="isGroupAdmin" id="isGroupAdmin" value="1">
グループ管理権限（学務担当職員）</label>
<br>
<label for="isAdmin">
<input type="checkbox" name="isAdmin" id="isAdmin" value="1">
システム管理者</label>
';
}
?>
</div>
<input type="submit" value="登録" id="button">
</p>
</form>
</div>

<div id="resultField"> 
<?php
include_once("../../lib/show-userlist.php");
?>
</div>
</body>
</html>
