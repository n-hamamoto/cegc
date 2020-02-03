<?php
  //
  // print grouplist with its members
  //
$pdo = pdo_connect_db($logdb);
if( $_SESSION['isAdmin']==='1' || $_SESSION['isGroupAdmin']==='1'){
  print "あなたは管理者なので，自身が管理するグループ以外も表示しています";
  $sql = sprintf("select groupId from groupInfo order by year, groupName");
  $stmt = $pdo->prepare($sql);	 
  $stmt->execute();
}else{
  $sql = sprintf("select groupId from groupAdmin where userId = ? order by groupId");
  $stmt = $pdo->prepare($sql);	 
  $stmt->execute( array($_SESSION['userId']) );
}

$i=0;
while($data= $stmt->fetch(PDO::FETCH_ASSOC)){
  $groupId[$i] = $data['groupId'];
  $i++;
}
$imax = $i;
for($i=0;$i<$imax;$i++){

  $sql = sprintf("select groupName, year from groupInfo where groupId = ? ");
  $stmt = $pdo->prepare($sql);	 
  $stmt->execute( array($groupId[$i]) );
  $data= $stmt->fetch(PDO::FETCH_ASSOC);
  $groupName[$i] = $data['groupName'];
  $year[$i] = $data['year'];

  $sql = sprintf("select idNumber from groupMember where groupId = ? ");
  $stmt = $pdo->prepare($sql);	 
  $stmt->execute( array($groupId[$i]) );

  print "<div class=\"opAndClToggle\">";
  print "（";
  xss_char_echo($year[$i]);
  print "年度）";
  xss_char_echo($groupName[$i]);
  print "のメンバー";
  print "</div>\n";
  print "<div class=\"opAndClblock\">";

  $ii=0;
  while($data= $stmt->fetch(PDO::FETCH_ASSOC)){
    $ii++;
    xss_char_echo($data['idNumber']);
    print ' ';
    if($ii===5){
      print "<br>";
      $ii=0;
    }
  }
  print "</div>\n";
}

//切断
$pdo = null;
?>
