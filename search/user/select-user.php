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

/* courseInfo内に登録されているyearを取得 */
$pdo = pdo_connect_db($logdb);
$sql = sprintf("select distinct year from courseInfo order by year desc;");
$stmt = $pdo->prepare($sql);
$stmt->execute();
$years=array();
while($data= $stmt->fetch(PDO::FETCH_ASSOC)){
	array_push($years, $data['year']);
};
$pdo = null;

if($printPassingStatus == 1){
	print "<h2>合否判定</h2>";
	print "<div class=\"passingStatus\">";
	$complete_ratio = array();
	foreach($langs as $lang){

		$out = 0; 
		[ $out, $info, $complete_ratio[$lang] ] = coursePassed($lang, $eptid, $userid, $years);

		if($out == 1){
			print "<span class=\"passed\">合格";
		}else if($out == 2){
			print "<span class=\"passed\">合格";
		}else if($out == 3){
			print "<span class=\"passed\">合格";
		}else{
			print "<span class=\"failed\">不合格";
		};
		print "(".$lang.")";
		print "</span>$info<br>";
	}
	print "</div>";
}

/* DB接続 */
$pdo = pdo_connect_db($logdb);

//現りんりん姫のデータを表示
$oldflg=0;

foreach($years as $year){

	//print "<hr>";

	foreach($langs as $lang){
		$title = "総合テスト".$year."(".$lang.")";
		printNiiMoodleLog($oldflg, $pdo, $lang, $title, $eptid, $userid, $year);
/*	}*/

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


/*	foreach($langs as $lang){*/
		$title = "受講状況".$year."(".$lang."): ".$complete_ratio[$lang][$year]."%完了";
		printNiiTrackingLog($oldflg,$pdo, $lang, $title, $eptid, $userid, $year);
	}
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
