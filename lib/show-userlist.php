<?php
  //
  // print userlist with its members
  //
$pdo = pdo_connect_db($logdb);
$sql = sprintf("select * from user");
/* クエリ */
$stmt = pdo_query_db($pdo,$sql);

$i=0;
print "<table>";
print "<tr>
<th>ユーザID</th>
<th>管理者</th>
<th>グループ管理権限</th>
<th>全成績閲覧権限</th>
<!--
<th>担当グループ成績閲覧権限</th>
-->
<th>成績閲覧できるグループ</th></tr>";

while($data = $stmt->fetch(PDO::FETCH_ASSOC) ){
  print "<tr>";
  print "<td>";
  xss_char_echo($data[userId]);
  print "</td>";
  print "<td>";
  xss_char_echo($data[isAdmin]);
  print "</td>";
  print "<td>";
  xss_char_echo($data[isGroupAdmin]);
  print "</td>";
  print "<td>";
  xss_char_echo($data[isSubAdmin]);
  print "</td>";
/*
  print "<td>";
  xss_char_echo($data[isTeacher]);
  print "</td>";
*/

  $i++;
  $sql = "select groupId from groupAdmin where userId = '".$data[userId]."' order by groupId";
  $stmt2 = pdo_query_db($pdo,$sql);

  print "<td>";
  while($data2 = $stmt2->fetch(PDO::FETCH_ASSOC) ){
    $sql = "select groupName,year from groupInfo where groupId = '".$data2[groupId]."'";
    $stmt3 = pdo_query_db($pdo,$sql);
    $data3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    xss_char_echo($data3[year]);
    print " : ";
    xss_char_echo($data3[groupName]);
    print "<br>";
  }
  print "</td></tr>";
}
print "</table>";

//切断
$pdo = null;
?>