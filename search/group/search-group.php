<?php
session_start();
include("../../auth/login.php");
?>
<html>
<head>
</head>
<body>
<?php
include("../../conf/config.php");
include("../../lib/dblib.php");
include("../../lib/id.php");
include("../../lib/function.php");
include("../lib/printLogs.php");

function print_score($pdo,$lang,$year,$eptid,$userid){
	include("../../conf/config.php");

	//変数初期化
	$score=""; $examdate="";

	//現りんりん姫の検索
	$oldflg = 0;
	$res = getNiiMoodleLog($oldflg,$lang,$eptid,$userid);

	foreach($res as $data){
    		if($data['FinalTest']>=$passingScore and $printPassingStatus == 1){
      			$score = $score."<strong>".htmlspecialchars($data['FinalTest'])."</strong><br>";
    		}else{
      			$score = $score.htmlspecialchars($data['FinalTest'])."<br>";
    		}
    		$examdate=$examdate.htmlspecialchars($data['End'])."<br>";
  	}

	//旧りんりん姫の検索
	$oldflg = 1;
	$res = getNiiMoodleLog($oldflg,$lang,$eptid,$userid);

	foreach($res as $data){
    		if($data['FinalTest']>=80){
      			$score = $score."<strong>".htmlspecialchars($data['FinalTest'])."</strong><br>";
    		}else{
      			$score = $score.htmlspecialchars($data['FinalTest'])."<br>";
    		}
    		$examdate=$examdate.htmlspecialchars($data['End'])."<br>";
  	}

  	print "<td>";
  	print $score;
  	print "</td>";
  	print "<td>";
  	print $examdate;
  	print "</td>";
}

/*
  手続き開始
*/

$langs = array('Ja','En','Cn','Kr');

if( isset($_POST['printFailedOnly']) ){
	$printFailedOnly = $_POST['printFailedOnly'];
}else{
	$printFailedOnly = 0;
}

$pdo = pdo_connect_db($logdb);

$stmt= $pdo->prepare("select year,groupName from groupInfo where groupId = ? ");
$stmt->execute( array( $_POST['groupId'] ) );
$data= $stmt->fetch(PDO::FETCH_ASSOC);

$year=$data['year'];
$groupName=$data['groupName'];

$stmt= $pdo->prepare("select idNumber from groupMember where groupId = ? order by idNumber");
$stmt->execute( array( $_POST['groupId'] ) );

$i=0;
while($data= $stmt->fetch(PDO::FETCH_ASSOC)){
  	$id[$i] = $data['idNumber'];
  	//print "$id[$i]<br>";
  	$i++;
}
$imax = $i;

print "<h1>";
xss_char_echo($year);
print "年度:";
xss_char_echo($groupName);
print "</h1>";
print "<table>";
print "<tr>";
print "<th>ユーザID</th>";
foreach($langs as $lang){
	if($printPassingStatus == 1){$colspan = 3;}else{$colspan = 2;};
	print "<th colspan='".$colspan."'>総合テスト(".$lang.")</th>";
}
print "</tr>";
print "<tr>";
print "<th></th>";
foreach($langs as $lang){
	if($printPassingStatus == 1){
		print "<th>合否</th>";
	}
	print "<th>得点</th>";
	print "<th>受験日時</th>";
}
print "</tr>";


for($i=0;$i<$imax;$i++){
  $eptid = getEptid($id[$i]);

  $outall = 0;
  $out = array();
  $complete_ration = array();
  if($printPassingStatus == 1){
	foreach($langs as $lang){
  		//合否判定
  		[$out[$lang], $complete_ration[$lang]] = coursePassed($lang, $eptid, $id[$i]);
		if($out[$lang] > 0){$outall = 1;}
  	}
  }

  $print = 0;
  if($printFailedOnly == 1 and $outall == 0){$print = 1;}
  if($printFailedOnly != 1){$print = 1;}

  if($print == 1){
	if($outall ==1){
  		print "<tr class='passed'>";
	}else{
  		print "<tr class='failed'>";
	}
  	print "<td>";
  	xss_char_echo($id[$i]);
  	print "</td>";

  	foreach($langs as $lang){
  		print"<td>";
  		if($out[$lang] == 1){
		print "合格(新)";
  		}else if($out[$lang] == 2){
		print "合格(旧)";
  		}else if($out[$lang] == 3){
		print "合格(新・旧)";
  		}else{
		print "不合格";
  		};
		if($out[$lang] != 2){
		print "<br>(受講".$complete_ration[$lang]."%)";
		}
		print"</td>";

  		print_score($pdo,$lang,$year,$eptid,$id[$i]);
  	}
  	print "</tr>";
     }
  }
print "</table>";
?>
</body>
</html>
