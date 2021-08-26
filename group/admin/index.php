<?php
session_start();
include_once("../../conf/config.php");
include_once("../../auth/login.php");
include_once("../../lib/dblib.php");
include_once("../../lib/function.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isGroupAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}

$pdo = pdo_connect_db($logdb);

$sql = "select userId from user order by userId";
$stmt= pdo_query_db($pdo, $sql);

$i=0;
while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
  $uid[$i] = $data[userId];
  $i++;
}
$imax = $i;

$sql = "select year, groupId, groupName from groupInfo order by year desc";
$stmt= pdo_query_db($pdo, $sql);

$j=0;
while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
  $gid[$j]   = $data[groupId];
  $gname[$j] = $data[groupName];
  $year[$j]  = $data[year];
  $j++;
}
$jmax = $j;

$pdo = null;
?>
<html>
<head>
<?php include("../../lib/header.php");?>
<script type="text/javascript" src="https://<?php echo $documentRoot ?>group/admin/index.js"></script>
</head>
<body>
<?php include("../../lib/menu.php");?>
<div id="main">
<h1>グループ管理者設定</h1>
<form id="inputForm" action="group/admin/form-exec.php" method="post">
<p>
userId: <select name="userId" id="userId">
<?php
  print "<option value=''>ユーザを選択してください。</option>";
  for($i=0;$i<$imax;$i++){
    print "<option value='".$uid[$i]."'>".$uid[$i]."</option>";
  }
?>
</select><br>
<div id="groupField">
</div>
<input type="submit" value="変更する" id="button">
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
