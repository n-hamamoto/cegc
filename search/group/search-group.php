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

function print_score($pdo,$lang,$year,$eptid){
  $table= "niiMoodleLog";
  $stmt= $pdo->prepare("select FinalTest,End from ".$table." where eptid = ? and lang = ? order by Start");
  $stmt->execute( array( $eptid, $lang ) );

  $score=""; $examdate="";
  while($data= $stmt->fetch(PDO::FETCH_ASSOC)){
    if($data[FinalTest]>=80){
      $score = $score."<strong>".htmlspecialchars($data[FinalTest])."</strong><br>";
    }else{
      $score = $score.htmlspecialchars($data[FinalTest])."<br>";
    }
    $examdate=$examdate.htmlspecialchars($data[End])."<br>";
  }
  print "<td>";
  print $score;
  print "</td>";
  print "<td>";
  print $examdate;
  print "</td>";
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
  $id[$i] = $data[idNumber];
  //print "$id[$i]<br>";
  $i++;
}
$imax = $i;
print "<h1>";
xss_char_echo($groupName);
xss_char_echo($year);
print "</h1>";
print "<table>";
print "<tr>";
print "<th>ユーザID</th>";
print "<th colspan='2'>総合テスト(Ja)</th>";
print "<th colspan='2'>総合テスト(En)</th>";
print "<th colspan='2'>総合テスト(Cn)</th>";
print "<th colspan='2'>総合テスト(Kr)</th>";
print "</tr>";
print "<tr>";
print "<th></th>";
print "<th>得点</th>";
print "<th>受験日時</th>";
print "<th>得点</th>";
print "<th>受験日時</th>";
print "<th>得点</th>";
print "<th>受験日時</th>";
print "<th>得点</th>";
print "<th>受験日時</th>";
print "</tr>";
for($i=0;$i<$imax;$i++){
  $eptid = getEptid($id[$i]);

  print "<tr>";
  // Japanese
  print "<td>";
  xss_char_echo($id[$i]);
  print "</td>";
  print_score($pdo,"Ja",$year,$eptid);
  // English
  print_score($pdo,"En",$year,$eptid);
  // Chinise
  print_score($pdo,"Cn",$year,$eptid);
  // Korean
  print_score($pdo,"Kr",$year,$eptid);
  print "</tr>";
}
print "</table>";
?>
</body>
</html>