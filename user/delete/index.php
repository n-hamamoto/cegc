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

$pdo = pdo_connect_db($logdb);

$sql =  sprintf("select userId from user");
$stmt = pdo_query_db($pdo, $sql);

$i=0;
while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
  $id[$i] = $data['userId'];
  $i++;
}
$imax = $i;

$pdo=null;
?>
<html>
<head>
<?php include("../../lib/header.php");?>
</head>
<body>
<?php include("../../lib/menu.php");?>
<div id="main">

<h1>ユーザ削除</h1>
<form id="inputForm" action="user/delete/form-exec.php" method="post">
<p>
userId: <select name="userId">
<?php
  for($i=0;$i<$imax;$i++){
    print "<option value='".$id[$i]."'>".$id[$i]."</option>";
  }
?>
</select>
<input type="submit" value="ユーザを削除" id="button">
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
