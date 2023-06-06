<?php
session_start();
include("../../conf/config.php");
include("../../auth/login.php");
include("../../lib/dblib.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}
?>
<html>
<head>
<title>ユーザ検索(旧)</title>
<?php include("../../lib/header.php");?>
<script type="text/javascript" src="./select-user.js"></script>
</head>
<body>
<?php
include("../../lib/menu.php");
include("../../lib/displayUpdate.php");
?>
<div id="main">
<h1>ユーザ検索(旧)</h1>
<form id="inputForm" action="search/user-old/select-user.php" method="post">
<p>
userid: <input type="text" name="name" size="40">
<input type="submit" value="Search" id="button">
</p>
</form>
<?php
#    print_r($_SESSION);
#    print_r($_COOKIE);
#    print_r($_SERVER);
#print $_SERVER["eppn"];
#print "<br>";
?>

</div>
<div id="resultField"> 
</div>
</body>
</html>
