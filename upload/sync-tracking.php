<?php
session_start();
include_once("../conf/config.php");
include_once("../auth/login.php");
include_once("../lib/dblib.php");
require_once("../lib/callReportAPI.php");
require_once("../lib/update_niiMoodleTracking.php");
require_once("../lib/printLog.php");

//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}
?>
<?php

$logtable = 'niiMoodleTracking';
$lang = array('Ja','En','Kr','Cn');
$year = '2022';

printLog("sync tracking start");
//echo date('YMD H:i:s');
//print " sync tracking start";

if( isset($_POST['syncall']) ){
        $syncall = $_POST['syncall'];
}else{
        $syncall = 0; // 1: データを全て取り直す, 0: 差分をとってくる
}
//$syncall = $_POST['syncall'];

// DB接続
$pdo = pdo_connect_db($logdb);
$sql = 'SELECT max(updated_at) from '.$logtable." where lang = ? and year = ? and eptid != 'dummy'";
$stmt = $pdo->prepare($sql);

foreach($lang as $l){
	$executed = $stmt->execute( array( $l, $year ) );
	if($executed){
		$data = $stmt->fetch();
		$lastupdate = new Datetime($data[0]);
		$lastupdate = $lastupdate->format('U');

//		$lastupdate = 1660000000;  //2022-08-09 08:06:40		
//		$lastupdate = 1673917200;  //2023-01-17 10:00:00		

		//syncall=1の時はデータを全部取ってくる
		if($syncall == 1){ $lastupdate = ''; }; 

		//成績取得&登録
		update_niiMoodleTracking($l, $year, $logtable, $logdb, $eppnDomain, $lastupdate); 
	}
}

if($_SESSION["auth"] === "true"){
        print'<a href=".">戻る</a>';
}

?>
