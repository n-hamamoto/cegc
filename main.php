<?php
session_start();
include("./conf/config.php");
include("./auth/login.php");
?>
<html>
<head>
<?php
include("./lib/header.php");
?>
<title>情報倫理eラーニング成績確認システム</title>
</head>
<body>
<?php
include("./lib/menu.php");
include("./lib/displayUpdate.php");
?>
<div id="main">
<?php
  print $_SESSION['jasn'];
  print $_SESSION['jaGivenName'];
?>
<?php
  print "（".$_SESSION['jaou']."）";
?>様<br>

<h1>情報倫理eラーニング成績確認システムへようこそ</h1>
<p>上のメニューから作業を開始してください</p>
</div>
<div id = "resultField">
<?php 
//print_r($_SESSION)
?>
</div>
</body>
</html>
