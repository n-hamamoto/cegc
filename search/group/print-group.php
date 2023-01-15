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
	//年度を選んだ時
	if( $_SESSION['isAdmin']==='1' ){
    		$sql = "SELECT 	groupInfo.groupName, groupInfo.year, groupInfo.groupId
			FROM   	groupInfo
			WHERE  	groupInfo.year = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute( array( $_POST["year"]) );
	}else{
    		$sql = "SELECT	groupInfo.groupName, groupInfo.year, groupAdmin.groupId
			FROM	groupInfo, groupAdmin
			WHERE	groupInfo.groupId = groupAdmin.groupId
				and groupAdmin.userId = ?
				and groupInfo.year = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute( array( $_SESSION["userId"], $_POST["year"]) );
	}
}else{
	//年度で「全」を選んだ時
	if( $_SESSION['isAdmin']==='1' ){
                $sql = "SELECT  groupInfo.groupName, groupInfo.year, groupInfo.groupId
                        FROM    groupInfo";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
	}else{
		$sql = "SELECT	groupInfo.groupName, groupInfo.year, groupAdmin.groupId
			FROM	groupInfo, groupAdmin
			WHERE 	groupInfo.groupId = groupAdmin.groupId
				and groupAdmin.userId = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute( array($_SESSION["userId"]) );
	}
}

$i=0;
print "<option value='-1'>選択してください</option>";
while($data = $stmt->fetch(PDO::FETCH_ASSOC) ){
	$groupName[$i] = $data['groupName'];
	$groupId[$i] = $data['groupId'];
	$year[$i] = $data['year'];
	// print "<option value='$groupId[$i]'>（$year[$i]）$groupName[$i]</option>";
	print "<option value='$groupId[$i]'>$groupName[$i]</option>";
	$i++;
}

$pdo = null;
