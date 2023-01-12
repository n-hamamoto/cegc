<?php
// index.jsから参照
session_start();
include("../../auth/login.php");
include("../../conf/config.php");
include("../../lib/dblib.php");

$groupId = $_POST['groupId'];

$pdo = pdo_connect_db($logdb);
$stmt= $pdo->prepare("select idNumber from groupMember where groupId = ? order by idNumber");
$stmt->execute( array( $groupId) );

$i=0;
while($data= $stmt->fetch(PDO::FETCH_ASSOC)){
  $uid[$i] = $data['idNumber'];
  $i++;
}
$imax = $i;

$pdo = null;

print_r($gid);
print_r($gidall);

print '<textarea name="memberList">';
//inputの表示
for($i=0;$i<$imax;$i++){
  print $uid[$i]."\n";
}
print '</textarea>';

?>
