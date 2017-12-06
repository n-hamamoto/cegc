<?php
// adminuser.jsから参照
session_start();
include("../../auth/login.php");
include("../../conf/config.php");
include("../../lib/dblib.php");
include_once("../../lib/function.php");

$userId = $_POST['userId'];

$pdo = pdo_connect_db($logdb);

$stmt = $pdo->prepare("select groupId from groupAdmin where userId = ? order by groupId");
$stmt->execute( array( $userId ) );

//$sql = sprintf("select groupId from groupAdmin where userId = '".$userId."' order by groupId");
//$res = wrap_mysql_query($sql, $conn);
$i=0;
while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
  $gid[$i] = $data[groupId];
  $i++;
}
$imax = $i;

$sql = "select groupId, groupName, year from groupInfo order by groupId";
$stmt = pdo_query_db($pdo, $sql);
$j=0;
while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
  $gidAll[$j] = $data[groupId];
  $gnameAll[$j] = $data[groupName];
  $yearAll[$j] = $data[year];
  $j++;
}
$jmax = $j;

$pdo = null;

#print_r($gid);
#print_r($gidall);

//inputの表示
for($j=0;$j<$jmax;$j++){
  $checked = "";
  for($i=0;$i<$imax;$i++){
    if($gidAll[$j] === $gid[$i]){
      $checked = "checked";
    }
  }
  print "<span class='checkbox'>";
  print "<label for='"; 
  xss_char_echo($gidAll[$j]); 
  print "'>";
  print "<input type='checkbox' name='groupId[]' id='";
  xss_char_echo($gidAll[$j]);
  print "' value='";
  xss_char_echo($gidAll[$j]);
  print "' ";
  xss_char_echo($checked);
  print "> ";
  xss_char_echo($gnameAll[$j]);
  print " (";
  xss_char_echo($yearAll[$j]);
  print ")";
  print "</label>";
  print "</span>";
}
?>