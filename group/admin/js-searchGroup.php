<?php
// adminuser.jsから参照
session_start();
include("../../auth/login.php");
include("../../conf/config.php");
include("../../lib/dblib.php");
include_once("../../lib/function.php");

$userId = $_POST['userId'];

$pdo = pdo_connect_db($logdb);

//ユーザの管理しているグループのグループIDを取得
$stmt = $pdo->prepare("select groupId from groupAdmin where userId = ? order by groupId");
$stmt->execute( array( $userId ) );

while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
  $adminGids[] = $data[groupId];//配列に要素を追加(pushと同等)
}

//年度を取得
$sql = "SELECT DISTINCT year from groupInfo order by year desc";
$stmt = pdo_query_db($pdo, $sql);
while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
    $years[]=$data[year];//配列に要素を追加(pushと同等)
}

//チェックボックス出力
foreach($years as $y){
    print "<h4>$y 年度</h4>";
    $sql = "select groupId, groupName, year from groupInfo where year = $y order by groupName";
    $stmt = pdo_query_db($pdo, $sql);
    while($data = $stmt->fetch(PDO::FETCH_ASSOC)){

  	$checked = "";
        foreach($adminGids as $gid){
    		if($data[groupId] === $gid){
      			$checked = "checked";
    		}
  	}

  	print "<span class='checkbox'>";
  	print "<label for='";
  	xss_char_echo($data[groupId]);
  	print "'>";
  	print "<input type='checkbox' name='groupId[]' id='";
  	xss_char_echo($data[groupId]);
  	print "' value='";
  	xss_char_echo($data[groupId]);
  	print "' ";
  	xss_char_echo($checked);
  	print "> ";
  	xss_char_echo($data[groupName]);
  	print " (";
  	xss_char_echo($data[year]);
  	print ")";
  	print "</label>";
  	print "</span>";        
	//print "$data[year], $data[groupId], $data[groupName]"."<br>";
    }
}
?>
