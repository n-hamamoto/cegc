<?php
session_start();
include("../../auth/login.php");
include("../../conf/config.php");
include("../../lib/dblib.php");
?>
<html>
<head>
</head>
<body>
<?php

$pdo = pdo_connect_db($logdb);
if($_POST["year"]>0){
  if( $_SESSION['isAdmin']==='1' ){
    $sql = "select 
groupInfo.groupName, groupInfo.year, groupInfo.groupId
from groupInfo
where groupInfo.year = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute( array( $_POST["year"]) );
  }else{
    $sql = "select 
groupInfo.groupName, groupInfo.year, groupAdmin.groupId 
from groupInfo, groupAdmin 
where groupInfo.groupId = groupAdmin.groupId 
and groupAdmin.userId = ?
and groupInfo.year = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute( array( $_SESSION["userId"], $_POST["year"]) );
  }

  $i=0;
  print "<option value='-1'>選択してください</option>";
  while($data = $stmt->fetch(PDO::FETCH_ASSOC) ){
    $groupName[$i] = $data[groupName];
    $groupId[$i] = $data[groupId];
    print "<option value='$groupId[$i]'>$groupName[$i]</option>";
    $i++;
  }
}else{

$sql = "select 
groupInfo.groupName, groupInfo.year, groupAdmin.groupId 
from groupInfo, groupAdmin 
where groupInfo.groupId = groupAdmin.groupId 
and groupAdmin.userId = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute( array($_SESSION["userId"]) );

  $i=0;
  while($data = $stmt->fetch(PDO::FETCH_ASSOC) ){
    $groupName[$i] = $data[groupName];
    $year[$i] = $data[year];
    $groupId[$i] = $data[groupId];
    print "<option value='$groupId[$i]'>（$year[$i]）$groupName[$i] </option>";
    $i++;
  }
}


$pdo = null;
