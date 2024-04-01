<?php
include_once("function.php");
include_once("dblib.php");
$pdo = pdo_connect_db($logdb);
?>

<?php

function print_lastupdate($pdo, $tbl, $lang){
// $langtbl=$tbl.$lang;
 $stmt = $pdo->prepare("select max(created_at) from ".$tbl." where lang = :lang");
// $stmt = $pdo->prepare("show table status LIKE :table");
 $stmt->execute( array(':lang' => $lang) );
 $data = $stmt->fetch(PDO::FETCH_ASSOC);
 print "<div class='lang'>";
 xss_char_echo($lang);
 print "</div>";
 print "<div class='updateTime'>";
// print_r($data);
if(!is_null($data['max(created_at)']))
{
 xss_char_echo(substr($data['max(created_at)'],0,-3));
}
// xss_char_echo(substr($data['created_at'],0,-3));
 print "</div>";
}

if( isAdmin() || isSubAdmin() ){
 print "<div class='updateNotice tracking'>";
 print "<div>最終更新（受講履歴）</div>";
 print_lastupdate($pdo,'niiMoodleTracking','Ja');
 print_lastupdate($pdo,'niiMoodleTracking','En');
 print_lastupdate($pdo,'niiMoodleTracking','Cn');
 print_lastupdate($pdo,'niiMoodleTracking','Kr');
 print"</div>";
}
?>

<?php
print "<div class='updateNotice finalTest'>";
if( isAdmin() ){
 print "<div>最終更新（総合テスト）</div>";
}else{
 print "<div>最終更新</div>";
}
 print_lastupdate($pdo,'niiMoodleLog','Ja');
 print_lastupdate($pdo,'niiMoodleLog','En');
 print_lastupdate($pdo,'niiMoodleLog','Cn');
 print_lastupdate($pdo,'niiMoodleLog','Kr');
 print "</div>";
?>

<?php
//切断
$pdo = null;
?>
