<?php
session_start();
include("../../conf/config.php");
include("../../auth/login.php");
include("../../lib/dblib.php");

$pdo = pdo_connect_db($logdb);

$stmt= $pdo->prepare("select groupId from groupAdmin where userId = ? order by groupId");
$stmt->execute( array( $_SESSION['userId'] ) );

$i=0;
while($data= $stmt->fetch(PDO::FETCH_ASSOC)){
  $id[$i] = $data['groupId'];
  $i++;
}
$imax = $i;

// 切断
$pdo = null;
?>

<html>
<head>
<?php include("../../lib/header.php");?>
</head>
<body>
<?php include("../../lib/menu.php"); ?>
<?php /* 最終更新の表示 */ ?>
<?php include("../../lib/displayUpdate.php"); ?>
<div id="main">
<h1>グループ検索</h1>
<form id="inputForm" action="search/group/search-group.php" method="post">
<p>
GroupName: <select name="year" id="year">
<?php
  $thisYear = date("Y");
  for($i=$thisYear; $i>=2015; $i--){
    print "<option value='".$i."'>".$i."</option>";
  }
print "<option value='-1'>"."全"."</option>";
?>
</select>
年度
<select name="groupId" id="groupIds">
<?php
  for($i=0;$i<$imax;$i++){
    print "<option value='".$id[$i]."'>".$id[$i]."</option>";
  }
?>
</select>
<input type="submit" value="Search" id="button">
</p>
<input type="checkbox" id="printFailedOnly" name="printFailedOnly" value="1">
<label for="printFailedOnly">不合格者のみを表示する</label>
</form>
</div>
<div id="resultField"> 
<?php
include_once("../../lib/show-grouplist.php");
?>
</div>
</body>
</html>
