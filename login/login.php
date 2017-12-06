<html>
<head>
<?php include("lib/header.php") ?>
</head>
<body>
<div id="menu">
   <ul><li>&nbsp;</li></ul>
</div>
<div id="main">
<h1>情報倫理eラーニング成績確認システム</h1>
<?php
   if($_SESSION["auth"]==="false"){
   echo '<div id="warn">ユーザIDまたはパスワードが違います。<div>';
   }
?>
<form method="POST" action="./index.php">
<table>
<tr>
<td>ID:</td><td><input type="text" name="userid"></td>
</tr>
<tr>
<td>Password:</td><td><input type="password" name="passwd"></td>
</tr>
<tr>
<td></td><td><input type="submit" name="formtype" value="login"></td>
</tr>
</table>
</form>
</div>
</body>