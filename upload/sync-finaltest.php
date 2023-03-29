<?php
session_start();
include_once("../conf/config.php");
include_once("../auth/login.php");
include_once("../lib/dblib.php");
require_once("../lib/callReportAPI.php");
require_once("../lib/update_niiMoodleLog.php");
require_once("../lib/printLog.php");

//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}
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

//echo date('Y-m-d H:i:s');
//print " sync finaltest start";

// print($_POST['syncall']);
if( isset($_POST['syncall']) ){
	$syncall = $_POST['syncall'];
}else{
	$syncall = 0; // 1: データを全て取り直す, 0: 差分をとってくる
}

//この日時以降のデータを取得
//$date = new DateTime('2022-10-10 00:00:00');
//$unixdate = $date->format('U');
//$unixdate = '';

// DB接続
$pdo = pdo_connect_db($logdb);
foreach($lang as $l){

	$sql = 'SELECT count(*) from niiMoodleLog where lang = ? and year = ? and eptid != ?';
	$stmt = $pdo->prepare($sql);
	$executed = $stmt->execute( array( $l, $year, 'dummy' ) );
	$data = $stmt->fetch();
	$count = $data[0];

	$sql = 'SELECT max(updated_at) from niiMoodleLog where lang = ? and year = ? and eptid != ?';
	$stmt = $pdo->prepare($sql);
	//echo $l;
	$executed = $stmt->execute( array( $l, $year, 'dummy' ) );
	if($executed){
		$data = $stmt->fetch();
		$lastupdate = new Datetime($data[0]);
		$lastupdate = $lastupdate->format('U');
		//print $lastupdate;

		//syncall=1の時はデータを全部取ってくる
		if($syncall == 1){ $lastupdate = ''; };
		//テーブルが空の時はデータを全部取ってくる 
		if($count == 0){ $lastupdate = ''; };

		//成績取得&登録
		update_niiMoodleLog($l, $year, $logtable, $logdb, $eppnDomain, $lastupdate); 
	}
}

if($_SESSION["auth"] === "true"){
	print'<a href=".">戻る</a>';
}

?>
