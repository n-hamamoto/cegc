<?php
session_start();
include_once("../../conf/config.php");
include("../../auth/login.php");
include("../../lib/dblib.php");
include("../../lib/id.php");
include("../lib/printLogs.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}
$userid = $_POST['name'];
?>
<script type="text/javascript" src="https://<?php echo $documentRoot ?>/js/resultField.js"></script>
<?php

$eptid = getEptid($userid);
//print $eptid;

$langs = array('Ja', 'En', 'Cn', 'Kr');

if($printPassingStatus == 1){
	print "<h2>合否判定</h2>";
	print "<div class=\"passingStatus\">";
	foreach($langs as $lang){

		$out = 0; 
		$complete_ratio = 0;
		[ $out, $complete_ratio ] = coursePassed($lang, $eptid, $userid);

		if($out == 1){
			print "<span class=\"passed\">合格(新)";
		}else if($out == 2){
			print "<span class=\"passed\">合格(旧)";
		}else if($out == 3){
			print "<span class=\"passed\">合格(新・旧)";
		}else{
			print "<span class=\"failed\">不合格";
		};
		print "(".$lang.")";
		print "</span>";
	}
	print "</div>";
}

/* DB接続 */
$pdo = pdo_connect_db($logdb);

//現りんりん姫のデータを表示
$oldflg=0;

foreach($langs as $lang){
	$title = "総合テスト(".$lang.")";
	printNiiMoodleLog($oldflg, $pdo, $lang, $title, $eptid, $userid);
}

/*
$title = "総合テスト(En)";
$lang = "En";
printNiiMoodleLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$title = "総合テスト(Cn)";
$lang = "Cn";
printNiiMoodleLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$title = "総合テスト(Kr)";
$lang = "Kr";
printNiiMoodleLog($oldflg,$pdo, $lang, $title, $eptid, $userid);
*/


foreach($langs as $lang){
	$title = "受講状況(".$lang.")";
	printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid);
}

/*
$lang="En";
$title = "受講状況(En)";
printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$lang="Cn";
$title = "受講状況(Cn)";
printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$lang="Kr";
$title = "受講状況(Kr)";
printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid);
*/
?>
