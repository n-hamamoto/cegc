<?php

include("config.php");
include("../lib/dblib.php");

// DBへ接続
$pdo = pdo_connect_db($logdb);

/*
 niiMoodleTrackingテーブル
*/
$sql="create table niiMoodleTracking(
	lang char(255) NOT NULL, INDEX(lang),
	year int NOT NULL,
	eptid char(255) NOT NULL, INDEX(eptid),
	field char(255),
	value char(255),
	created_at  timestamp DEFAULT CURRENT_TIMESTAMP,
	updated_at  timestamp DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	Count int,
	Start datetime,
	LastAccess datetime,
	Score double,
	cmid int
)";
$stmt = pdo_query_db($pdo,$sql);

/*
 niiMoodleTracking_oldテーブル
*/
$sql="create table niiMoodleTracking_old(
	lang CHAR(255) NOT NULL, 
	year INT NOT NULL, 
	eptid CHAR(255) NOT NULL, 
	field CHAR(255) NOT NULL, 
	value CHAR(255) NOT NULL, 
	created_at timestamp not null default current_timestamp, 
	updated_at timestamp not null default current_timestamp on update current_timestamp
)";
$stmt = pdo_query_db($pdo,$sql);

// index追加 
$sql="alter table niiMoodleTracking_old add index index_created_at(lang, created_at)";
$stmt = pdo_query_db($pdo,$sql);

// index追加
$sql="alter table niiMoodleTracking_old add index index_eptid(eptid)";
$stmt = pdo_query_db($pdo,$sql);

/*
niiMoodleLogテーブル
*/
$sql="create table niiMoodleLog(
	lang char(255) NOT NULL, INDEX(lang),
	year int NOT NULL,
	eptid char(255) NOT NULL, INDEX(eptid),
	Status char(255),
	Start datetime,
	End datetime,
	ElapsedTime  double,
	FinalTest double,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP 
)";
$stmt = pdo_query_db($pdo,$sql);

/*
niiMoodleLog_oldテーブル
*/
$sql="CREATE TABLE niiMoodleLog_old (lang CHAR(255) NOT NULL, year INT NOT NULL, eptid CHAR(255) NOT NULL, Status CHAR(255), Start CHAR(255), End CHAR(255), ElapsedTime CHAR(255), FinalTest INT, 
created_at timestamp not null default current_timestamp, 
updated_at timestamp not null default current_timestamp on update current_timestamp)";
$stmt = pdo_query_db($pdo,$sql);

// index追加
$sql="alter table niiMoodleLog_old add index index_eptid(eptid)";
$stmt = pdo_query_db($pdo,$sql);

/*
courseInfoテーブル
*/
$sql="create table courseInfo (
	coursemoduleid  int  NOT NULL PRIMARY KEY,
	courseid  int,
	coursefullname varchar(255),
	courseshortname varchar(255),
	modinstanceid  int,
	visibility int,
	modname varchar(255),
	modnamelocal varchar(255),
	modinstancename varchar(255)
)";
$stmt = pdo_query_db($pdo,$sql);





/*
groupMemberテーブル
*/
$sql="CREATE TABLE groupMember (idNumber CHAR(255) not null, groupId CHAR(255) not null, PRIMARY KEY(idNumber, groupId))";
$stmt = pdo_query_db($pdo,$sql);

/*
groupInfoテーブル
*/
$sql="CREATE TABLE groupInfo (groupId INT auto_increment, groupName CHAR(255) not null, year INT not null, PRIMARY KEY(groupId), UNIQUE(groupName, year) ) ";
$stmt = pdo_query_db($pdo,$sql);

/*
userテーブル
*/
$sql="CREATE TABLE user (userId CHAR(255) not null PRIMARY KEY, isAdmin BOOLEAN, isTeacher BOOLEAN, isSubAdmin BOOLEAN, isGroupAdmin BOOLEAN)";
$stmt = pdo_query_db($pdo,$sql);

/*
groupAdminテーブル
*/
$sql="CREATE TABLE groupAdmin (userId CHAR(255) not null, groupId CHAR(255) not null)";
$stmt = pdo_query_db($pdo,$sql);

// index追加
$sql="ALTER TABLE groupAdmin ADD constraint primary key (userId, groupId)";
$stmt = pdo_query_db($pdo,$sql);

/*
初期ユーザの登録
*/
$sql="INSERT into user (userId, isAdmin) VALUES ('".$inituser."','1')";
$stmt = pdo_query_db($pdo,$sql);

/*
defaultAcademicYearテーブル
*/
$sql="CREATE TABLE defaultAcademicYear (year INT not null)";
$stmt = pdo_query_db($pdo,$sql);

//実行時点の年をデフォルトに設定
$academicYear = date( "Y" );
$sql="INSERT into defaultAcademicYear (year) VALUES ('".$academicYear."')";
$stmt = pdo_query_db($pdo,$sql);

/*
作成したテーブル一覧を表示
*/
$sql="show tables";
$stmt = pdo_query_db($pdo,$sql);

while($result = $stmt->fetch(PDO::FETCH_ASSOC) ){
      print_r($result);
}

//切断
$pdo = null;

?>
