<?php

include("config.php");
include("../lib/dblib.php");

// DBへ接続
$pdo = pdo_connect_db($logdb);

$sql="CREATE TABLE mdl2log (LastName CHAR(255), FirstName CHAR(255), IdNumber CHAR(255), Faculty CHAR(255), Department CHAR(255), MailAddress CHAR(255) NOT NULL PRIMARY KEY, Kadai CHAR(255), PrefaceJp INT, Chapter1Jp INT, Chapter2Jp INT, Chapter3Jp INT, Chapter4Jp INT, Chapter5Jp INT, Chapter6Jp INT, Chapter7Jp INT, Chapter8Jp INT, Chapter9Jp INT, FinalTestJp INT, PrefaceEn INT, Chapter1En INT, Chapter2En INT, Chapter3En INT, Chapter4En INT, Chapter5En INT, Chapter6En INT, Chapter7En INT, Chapter8En INT, Chapter9En INT, FinalTestEn INT, PrefaceCn INT, Chapter1Cn INT, Chapter2Cn INT, Chapter3Cn INT, Chapter4Cn INT, Chapter5Cn INT, Chapter6Cn INT, Chapter7Cn INT, Chapter8Cn INT, Chapter9Cn INT, FinalTestCn INT, PrefaceKr INT, Chapter1Kr INT, Chapter2Kr INT, Chapter3Kr INT, Chapter4Kr INT, Chapter5Kr INT, Chapter6Kr INT, Chapter7Kr INT, Chapter8Kr INT, Chapter9Kr INT, FinalTestKr INT, Total DOUBLE)";
/* クエリ */
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleTracking (lang CHAR(255) NOT NULL, year INT NOT NULL, eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), Latest CHAR(255), Score INT, 0_1 CHAR(255), 0_2 CHAR(255), 0_3 CHAR(255),  0_4 CHAR(255), 1_1 CHAR(255), 1_2 CHAR(255), 1_3_1 CHAR(255), 1_3_2 CHAR(255), 1_4_1 CHAR(255), 1_4_2 CHAR(255), 1_5_1 CHAR(255), 1_6 CHAR(255), 2_1 CHAR(255), 2_2 CHAR(255), 2_3_1 CHAR(255), 2_3_2 CHAR(255), 2_4_1 CHAR(255), 2_4_2 CHAR(255), 2_4_3 CHAR(255), 2_5_1 CHAR(255), 2_5_2 CHAR(255), 2_6 CHAR(255), 3_1 CHAR(255), 3_2 CHAR(255), 3_3_1 CHAR(255), 3_3_2 CHAR(255), 3_4_1 CHAR(255), 3_4_2 CHAR(255), 3_5_1 CHAR(255), 3_6 CHAR(255), 4_1 CHAR(255), 4_2 CHAR(255), 4_3_1 CHAR(255), 4_3_2 CHAR(255), 4_4_1 CHAR(255), 4_4_2 CHAR(255), 4_4_3 CHAR(255), 4_4_4 CHAR(255), 4_5_1 CHAR(255), 4_5_2 CHAR(255), 4_6 CHAR(255), 5_1 CHAR(255), 5_2 CHAR(255), 5_3_1 CHAR(255), 5_3_2 CHAR(255), 5_4_1 CHAR(255), 5_4_2 CHAR(255), 5_4_3 CHAR(255), 5_5_1 CHAR(255), 5_5_2 CHAR(255), 5_6 CHAR(255), 6_1 CHAR(255), 6_2 CHAR(255), 6_3_1 CHAR(255), 6_3_2 CHAR(255), 6_4_1 CHAR(255), 6_4_2 CHAR(255), 6_4_3 CHAR(255), 6_4_4 CHAR(255), 6_5_1 CHAR(255), 6_5_2 CHAR(255), 6_6 CHAR(255), 7_1 CHAR(255), 7_2 CHAR(255), 7_3_1 CHAR(255), 7_3_2 CHAR(255), 7_4_1 CHAR(255), 7_4_2 CHAR(255), 7_4_3 CHAR(255), 7_5_1 CHAR(255), 7_6 CHAR(255), 8_1 CHAR(255), 8_2 CHAR(255), 8_3_1 CHAR(255), 8_3_2 CHAR(255), 8_4_1 CHAR(255), 8_4_2 CHAR(255), 8_5_1 CHAR(255), 8_6 CHAR(255), 9_1 CHAR(255), 9_2 CHAR(255), 9_3 CHAR(255), 
created_at timestamp not null default current_timestamp, 
updated_at timestamp not null default current_timestamp on update current_timestamp)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleLog (lang CHAR(255) NOT NULL, year INT NOT NULL, eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), End CHAR(255), ElapsedTime CHAR(255), FinalTest INT, 
created_at timestamp not null default current_timestamp, 
updated_at timestamp not null default current_timestamp on update current_timestamp)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE groupMember (idNumber CHAR(255) not null, groupId CHAR(255) not null, PRIMARY KEY(idNumber, groupId))";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE groupInfo (groupId INT auto_increment, groupName CHAR(255) not null, year INT not null, PRIMARY KEY(groupId), UNIQUE(groupName, year) ) ";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE user (userId CHAR(255) not null PRIMARY KEY, isAdmin BOOLEAN, isTeacher BOOLEAN, isSubAdmin BOOLEAN, isGroupAdmin BOOLEAN)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE groupAdmin (userId CHAR(255) not null, groupId CHAR(255) not null)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="ALTER TABLE groupAdmin ADD constraint primary key (userId, groupId)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="INSERT into user (userId, isAdmin) VALUES ('".$inituser."','1')";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE defaultAcademicYear (year INT not null)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$academicYear = date( "Y" );
$sql="INSERT into defaultAcademicYear (year) VALUES ('".$academicYear."')";
$stmt = pdo_query_db($pdo,$sql);


$sql="CREATE TABLE niiMoodleTrackingJa (eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), Latest CHAR(255), Score INT, 0_1 CHAR(255), 0_2 CHAR(255), 0_3 CHAR(255),  0_4 CHAR(255), 1_1 CHAR(255), 1_2 CHAR(255), 1_3_1 CHAR(255), 1_3_2 CHAR(255), 1_4_1 CHAR(255), 1_4_2 CHAR(255), 1_5_1 CHAR(255), 1_6 CHAR(255), 2_1 CHAR(255), 2_2 CHAR(255), 2_3_1 CHAR(255), 2_3_2 CHAR(255), 2_4_1 CHAR(255), 2_4_2 CHAR(255), 2_4_3 CHAR(255), 2_5_1 CHAR(255), 2_5_2 CHAR(255), 2_6 CHAR(255), 3_1 CHAR(255), 3_2 CHAR(255), 3_3_1 CHAR(255), 3_3_2 CHAR(255), 3_4_1 CHAR(255), 3_4_2 CHAR(255), 3_5_1 CHAR(255), 3_6 CHAR(255), 4_1 CHAR(255), 4_2 CHAR(255), 4_3_1 CHAR(255), 4_3_2 CHAR(255), 4_4_1 CHAR(255), 4_4_2 CHAR(255), 4_4_3 CHAR(255), 4_4_4 CHAR(255), 4_5_1 CHAR(255), 4_5_2 CHAR(255), 4_6 CHAR(255), 5_1 CHAR(255), 5_2 CHAR(255), 5_3_1 CHAR(255), 5_3_2 CHAR(255), 5_4_1 CHAR(255), 5_4_2 CHAR(255), 5_4_3 CHAR(255), 5_5_1 CHAR(255), 5_5_2 CHAR(255), 5_6 CHAR(255), 6_1 CHAR(255), 6_2 CHAR(255), 6_3_1 CHAR(255), 6_3_2 CHAR(255), 6_4_1 CHAR(255), 6_4_2 CHAR(255), 6_4_3 CHAR(255), 6_4_4 CHAR(255), 6_5_1 CHAR(255), 6_5_2 CHAR(255), 6_6 CHAR(255), 7_1 CHAR(255), 7_2 CHAR(255), 7_3_1 CHAR(255), 7_3_2 CHAR(255), 7_4_1 CHAR(255), 7_4_2 CHAR(255), 7_4_3 CHAR(255), 7_5_1 CHAR(255), 7_6 CHAR(255), 8_1 CHAR(255), 8_2 CHAR(255), 8_3_1 CHAR(255), 8_3_2 CHAR(255), 8_4_1 CHAR(255), 8_4_2 CHAR(255), 8_5_1 CHAR(255), 8_6 CHAR(255), 9_1 CHAR(255), 9_2 CHAR(255), 9_3 CHAR(255))"; 
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleTrackingEn (eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), Latest CHAR(255), Score INT, 0_1 CHAR(255), 0_2 CHAR(255), 0_3 CHAR(255),  0_4 CHAR(255), 1_1 CHAR(255), 1_2 CHAR(255), 1_3_1 CHAR(255), 1_3_2 CHAR(255), 1_4_1 CHAR(255), 1_4_2 CHAR(255), 1_5_1 CHAR(255), 1_6 CHAR(255), 2_1 CHAR(255), 2_2 CHAR(255), 2_3_1 CHAR(255), 2_3_2 CHAR(255), 2_4_1 CHAR(255), 2_4_2 CHAR(255), 2_4_3 CHAR(255), 2_5_1 CHAR(255), 2_5_2 CHAR(255), 2_6 CHAR(255), 3_1 CHAR(255), 3_2 CHAR(255), 3_3_1 CHAR(255), 3_3_2 CHAR(255), 3_4_1 CHAR(255), 3_4_2 CHAR(255), 3_5_1 CHAR(255), 3_6 CHAR(255), 4_1 CHAR(255), 4_2 CHAR(255), 4_3_1 CHAR(255), 4_3_2 CHAR(255), 4_4_1 CHAR(255), 4_4_2 CHAR(255), 4_4_3 CHAR(255), 4_4_4 CHAR(255), 4_5_1 CHAR(255), 4_5_2 CHAR(255), 4_6 CHAR(255), 5_1 CHAR(255), 5_2 CHAR(255), 5_3_1 CHAR(255), 5_3_2 CHAR(255), 5_4_1 CHAR(255), 5_4_2 CHAR(255), 5_4_3 CHAR(255), 5_5_1 CHAR(255), 5_5_2 CHAR(255), 5_6 CHAR(255), 6_1 CHAR(255), 6_2 CHAR(255), 6_3_1 CHAR(255), 6_3_2 CHAR(255), 6_4_1 CHAR(255), 6_4_2 CHAR(255), 6_4_3 CHAR(255), 6_4_4 CHAR(255), 6_5_1 CHAR(255), 6_5_2 CHAR(255), 6_6 CHAR(255), 7_1 CHAR(255), 7_2 CHAR(255), 7_3_1 CHAR(255), 7_3_2 CHAR(255), 7_4_1 CHAR(255), 7_4_2 CHAR(255), 7_4_3 CHAR(255), 7_5_1 CHAR(255), 7_6 CHAR(255), 8_1 CHAR(255), 8_2 CHAR(255), 8_3_1 CHAR(255), 8_3_2 CHAR(255), 8_4_1 CHAR(255), 8_4_2 CHAR(255), 8_5_1 CHAR(255), 8_6 CHAR(255), 9_1 CHAR(255), 9_2 CHAR(255), 9_3 CHAR(255))"; 
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleTrackingCn (eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), Latest CHAR(255), Score INT, 0_1 CHAR(255), 0_2 CHAR(255), 0_3 CHAR(255),  0_4 CHAR(255), 1_1 CHAR(255), 1_2 CHAR(255), 1_3_1 CHAR(255), 1_3_2 CHAR(255), 1_4_1 CHAR(255), 1_4_2 CHAR(255), 1_5_1 CHAR(255), 1_6 CHAR(255), 2_1 CHAR(255), 2_2 CHAR(255), 2_3_1 CHAR(255), 2_3_2 CHAR(255), 2_4_1 CHAR(255), 2_4_2 CHAR(255), 2_4_3 CHAR(255), 2_5_1 CHAR(255), 2_5_2 CHAR(255), 2_6 CHAR(255), 3_1 CHAR(255), 3_2 CHAR(255), 3_3_1 CHAR(255), 3_3_2 CHAR(255), 3_4_1 CHAR(255), 3_4_2 CHAR(255), 3_5_1 CHAR(255), 3_6 CHAR(255), 4_1 CHAR(255), 4_2 CHAR(255), 4_3_1 CHAR(255), 4_3_2 CHAR(255), 4_4_1 CHAR(255), 4_4_2 CHAR(255), 4_4_3 CHAR(255), 4_4_4 CHAR(255), 4_5_1 CHAR(255), 4_5_2 CHAR(255), 4_6 CHAR(255), 5_1 CHAR(255), 5_2 CHAR(255), 5_3_1 CHAR(255), 5_3_2 CHAR(255), 5_4_1 CHAR(255), 5_4_2 CHAR(255), 5_4_3 CHAR(255), 5_5_1 CHAR(255), 5_5_2 CHAR(255), 5_6 CHAR(255), 6_1 CHAR(255), 6_2 CHAR(255), 6_3_1 CHAR(255), 6_3_2 CHAR(255), 6_4_1 CHAR(255), 6_4_2 CHAR(255), 6_4_3 CHAR(255), 6_4_4 CHAR(255), 6_5_1 CHAR(255), 6_5_2 CHAR(255), 6_6 CHAR(255), 7_1 CHAR(255), 7_2 CHAR(255), 7_3_1 CHAR(255), 7_3_2 CHAR(255), 7_4_1 CHAR(255), 7_4_2 CHAR(255), 7_4_3 CHAR(255), 7_5_1 CHAR(255), 7_6 CHAR(255), 8_1 CHAR(255), 8_2 CHAR(255), 8_3_1 CHAR(255), 8_3_2 CHAR(255), 8_4_1 CHAR(255), 8_4_2 CHAR(255), 8_5_1 CHAR(255), 8_6 CHAR(255), 9_1 CHAR(255), 9_2 CHAR(255), 9_3 CHAR(255))"; 
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleTrackingKr (eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), Latest CHAR(255), Score INT, 0_1 CHAR(255), 0_2 CHAR(255), 0_3 CHAR(255),  0_4 CHAR(255), 1_1 CHAR(255), 1_2 CHAR(255), 1_3_1 CHAR(255), 1_3_2 CHAR(255), 1_4_1 CHAR(255), 1_4_2 CHAR(255), 1_5_1 CHAR(255), 1_6 CHAR(255), 2_1 CHAR(255), 2_2 CHAR(255), 2_3_1 CHAR(255), 2_3_2 CHAR(255), 2_4_1 CHAR(255), 2_4_2 CHAR(255), 2_4_3 CHAR(255), 2_5_1 CHAR(255), 2_5_2 CHAR(255), 2_6 CHAR(255), 3_1 CHAR(255), 3_2 CHAR(255), 3_3_1 CHAR(255), 3_3_2 CHAR(255), 3_4_1 CHAR(255), 3_4_2 CHAR(255), 3_5_1 CHAR(255), 3_6 CHAR(255), 4_1 CHAR(255), 4_2 CHAR(255), 4_3_1 CHAR(255), 4_3_2 CHAR(255), 4_4_1 CHAR(255), 4_4_2 CHAR(255), 4_4_3 CHAR(255), 4_4_4 CHAR(255), 4_5_1 CHAR(255), 4_5_2 CHAR(255), 4_6 CHAR(255), 5_1 CHAR(255), 5_2 CHAR(255), 5_3_1 CHAR(255), 5_3_2 CHAR(255), 5_4_1 CHAR(255), 5_4_2 CHAR(255), 5_4_3 CHAR(255), 5_5_1 CHAR(255), 5_5_2 CHAR(255), 5_6 CHAR(255), 6_1 CHAR(255), 6_2 CHAR(255), 6_3_1 CHAR(255), 6_3_2 CHAR(255), 6_4_1 CHAR(255), 6_4_2 CHAR(255), 6_4_3 CHAR(255), 6_4_4 CHAR(255), 6_5_1 CHAR(255), 6_5_2 CHAR(255), 6_6 CHAR(255), 7_1 CHAR(255), 7_2 CHAR(255), 7_3_1 CHAR(255), 7_3_2 CHAR(255), 7_4_1 CHAR(255), 7_4_2 CHAR(255), 7_4_3 CHAR(255), 7_5_1 CHAR(255), 7_6 CHAR(255), 8_1 CHAR(255), 8_2 CHAR(255), 8_3_1 CHAR(255), 8_3_2 CHAR(255), 8_4_1 CHAR(255), 8_4_2 CHAR(255), 8_5_1 CHAR(255), 8_6 CHAR(255), 9_1 CHAR(255), 9_2 CHAR(255), 9_3 CHAR(255))"; 
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleLogJa (eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), End CHAR(255), ElapsedTime CHAR(255), FinalTest INT)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleLogEn (eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), End CHAR(255), ElapsedTime CHAR(255), FinalTest INT)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleLogCn (eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), End CHAR(255), ElapsedTime CHAR(255), FinalTest INT)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="CREATE TABLE niiMoodleLogKr (eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), End CHAR(255), ElapsedTime CHAR(255), FinalTest INT)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="show tables";
$stmt = pdo_query_db($pdo,$sql);

while($result = $stmt->fetch(PDO::FETCH_ASSOC) ){
      print_r($result);
}

//切断
$pdo = null;

?>
