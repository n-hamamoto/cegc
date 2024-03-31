<?php
session_start();
include_once("../../conf/config.php");
include("../../auth/login.php");
include("../../lib/dblib.php");
include("../../lib/function.php");
//権限のない人はログアウト
requireGroupAdmin();

$pdo = pdo_connect_db($logdb);

$sql = sprintf("select groupId from groupInfo");
$stmt= pdo_query_db($pdo,$sql);

/*
$i=0;
while($data = $stmt->fetch(PDO::FETCH_ASSOC) ){
  $id[$i] = $data[groupId];
  $i++;
}
$imax = $i;
*/
$pdo = null;
?>
<html>
<head>
<?php include("../../lib/header.php");?>
</head>
<body>
    <?php include("../../lib/menu.php");?>
<div id="main">
<h1>グループ削除</h1>
<form id="inputForm" action="group/delete/form-exec.php" method="post">
<p>
グループ名:<br>
<select name="year" id="year">
<?php
  // idがinputFormの中にある，idがyearのselectboxを選択すると，
  // search/group/print-group.phpを実行して，
  // idがgroupIdsのoptionに反映する。
  // ->see common.js
  //
  // 年度選択フォームを出力
  $thisYear = date("Y");
  for($i=$thisYear; $i>=2015; $i--){
    print "<option value='".$i."'>".$i."</option>";
  }
?>
</select>
年度
<select name="name" id="groupIds">
<?php
  // groupIdsがidのフォームは，年度を選ぶと下記の形式で入力される
  // "<option value='groupId'> groupName </option>";
?>
</select>
<br>
<input type="submit" value="グループを削除" id="button">
</p>
</form>
</div>
<div id="resultField"> 
<?php
include_once("../../lib/show-grouplist.php");
?>
</div>
</body>
</html>
