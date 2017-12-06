<?php
session_start();
include_once("../conf/config.php");
include("../auth/login.php");
include_once("../lib/dblib.php");
include_once("../lib/function.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>年度のアップデート</title>
</head>
<body>
<p><?php
$year = $_POST[year];
/*DB接続*/
  $pdo = pdo_connect_db($logdb);
  $sql = sprintf("update defaultAcademicYear set year = ?");
  $stmt = $pdo->prepare($sql);
  $data = $stmt->execute( array($year) );

xss_char_echo($year);
print "年度に設定しました";
print '<input type="button" value="OK" onclick="history.back();" /> ';

?></p>
</body>
</html>
