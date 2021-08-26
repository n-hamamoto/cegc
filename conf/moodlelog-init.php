<?php

include("config.php");
include("../lib/dblib.php");

// DBへ接続
$pdo = pdo_connect_db($logdb);

//niiMoodleTrackingテーブルの作成
$sql="create table niiMoodleTracking(lang CHAR(255) NOT NULL, year INT NOT NULL, eptid CHAR(255) NOT NULL, field CHAR(255) NOT NULL, value CHAR(255) NOT NULL, created_at timestamp not null default current_timestamp, updated_at timestamp not null default current_timestamp on update current_timestamp)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="alter table niiMoodleTracking add index index_created_at(lang, created_at)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="alter table niiMoodleTracking add index index_eptid(eptid)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

//niiMoodleTracking_oldテーブル
$sql="CREATE TABLE niiMoodleTracking_old LIKE niiMoodleTracking";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

//niiMoodleLogテーブルの作成
$sql="CREATE TABLE niiMoodleLog (lang CHAR(255) NOT NULL, year INT NOT NULL, eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), End CHAR(255), ElapsedTime CHAR(255), FinalTest INT, 
created_at timestamp not null default current_timestamp, 
updated_at timestamp not null default current_timestamp on update current_timestamp)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

$sql="alter table niiMoodleLog add index index_eptid(eptid)";
// クエリ
$stmt = pdo_query_db($pdo,$sql);

//niiMoodleTracking_oldテーブル
$sql="CREATE TABLE niiMoodleLog_old LIKE niiMoodleLog";
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

$sql="show tables";
$stmt = pdo_query_db($pdo,$sql);

while($result = $stmt->fetch(PDO::FETCH_ASSOC) ){
      print_r($result);
}

//切断
$pdo = null;

?>
