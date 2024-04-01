<?php
session_start();
include_once("../conf/config.php");
include_once("../auth/login.php");
include_once("../lib/dblib.php");
require_once("../lib/callReportAPI.php");
require_once("../lib/update_niiMoodleLog.php");
require_once("../lib/printLog.php");

//権限のない人はログアウト
requireSubAdmin();
?>
<?php

$logtable = 'niiMoodleLog';
$lang = array('Ja','En','Kr','Cn');
$year = '2022';

$pdo = pdo_connect_db($logdb);
$sql = sprintf("select year from defaultAcademicYear");
$stmt = pdo_query_db($pdo,$sql);
$data= $stmt->fetch(PDO::FETCH_ASSOC);
$year = $data['year'];
$pdo = null;

printLog("sync finaltest start");

if( isset($_POST['syncall']) ){
	$syncall = $_POST['syncall'];
}else{
	$syncall = 0; // 1: データを全て取り直す, 0: 差分をとってくる, 2:指定日以降のデータを取る
}

if( $syncall ==2 && isset($_POST['syncdate']) ){
	$syncdate = $_POST['syncdate'];
	if(preg_match('/^[0-9]{8}$/', $syncdate)){	
	}else{
		die("Input date is not YYYYMMDD format");
	}
}else{
	$syncdate = 0;
}

// DB接続
$pdo = pdo_connect_db($logdb);
foreach($lang as $l){

	//成績取得&登録
	update_niiMoodleLog($l, $year, $logtable, $logdb, $eppnDomain, $syncall, $syncdate); 
}

if($_SESSION["auth"] === "true"){
	print'<a href=".">戻る</a>';
}

?>
