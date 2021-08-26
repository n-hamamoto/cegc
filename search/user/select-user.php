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

/* DB接続 */
$pdo = pdo_connect_db($logdb);

$eptid = getEptid($userid);
//print $eptid;

//現りんりん姫のデータを表示
$oldflg=0;

$title = "総合テスト(Ja)";
$lang = "Ja";
printNiiMoodleLog($oldflg, $pdo, $lang, $title, $eptid, $userid);

$title = "総合テスト(En)";
$lang = "En";
printNiiMoodleLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$title = "総合テスト(Cn)";
$lang = "Cn";
printNiiMoodleLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$title = "総合テスト(Kr)";
$lang = "Kr";
printNiiMoodleLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$lang="Ja";
$title = "受講状況(Ja)";
printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$lang="En";
$title = "受講状況(En)";
printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$lang="Cn";
$title = "受講状況(Cn)";
printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

$lang="Kr";
$title = "受講状況(Kr)";
printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid);

?>
