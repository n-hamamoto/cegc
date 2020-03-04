<?php
session_start();
include_once("../../conf/config.php");
include("../../auth/login.php");
include("../../lib/dblib.php");
include("../../lib/function.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isGroupAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}

$pdo = pdo_connect_db($logdb);
$sql = "select groupId, groupName, year from groupInfo";
$stmt= pdo_query_db($pdo, $sql);

$i=0;
while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
  $id[$i] = $data[groupId];
  $name[$i] = $data[groupName];
  $year[$i] = $data[year];
  $i++;
}
$imax = $i;

$pdo = null;
?>
<html>
<head>
<?php include("../../lib/header.php");?>
<script type="text/javascript" src="https://<?php echo $documentRoot ?>group/modify/index.js"></script>
</head>
<body>
  <?php include("../../lib/menu.php");?>
<div id="main">
<h1>グループ変更</h1>
<form id="inputForm" action="group/modify/form-exec.php" method="post">
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
</p>
<div id="memberField">
</div>
<input type="submit" value="変更する" id="button" disabled>
</form>
</div>
<div id="resultField"> 
</div>
</body>
</html>
