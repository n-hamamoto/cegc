<?php 
session_start();
include_once("../../conf/config.php");
include_once("../../auth/login.php");
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
<h1>グループ登録</h1>

<form id="inputForm" method="post" action="group/add/form-exec.php" enctype="multipart/form-data">
<span>グループ名:</span>
<br />
<input type="text" name="groupName">
<br />
<span>年度:</span>
<br />
<select name="year">
<?php
  $thisYear = date("Y");
  for($i=2015; $i<=$thisYear; $i++){
    print "<option value='".$i."'>".$i."</option>";
  }
?>
</select>
<br />
<span>メンバー一覧：1行に1ユーザを記入してください</span>
<br />
<textarea name="memberList">
</textarea>
<!--
<input type="file" name="upfile" /><br />
-->
<br />
<input type="submit" value="グループ登録" />
</form>
</div>
<?php
include_once("../../conf/config.php");
include_once("../../lib/dblib.php");
?>
<div id="resultField">
<?php
include_once("../../lib/show-grouplist.php");
?>
</div>
</body>
</html>
