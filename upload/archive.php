<?php
session_start();
include_once("../conf/config.php");
include("../auth/login.php");
include("../auth/admin.php");
include_once("../lib/dblib.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Archive Data</title>
</head>
<body>
<p><?php

  function archiveTable($pdo,$tablename){
  //  print date( "Y/m/d (D) H:i:s" );
  $academicYear = date( "Y" ) -1;
  $archiveTablename=$tablename.$academicYear;

  print $archiveTablename."<br>";
  $sql = sprintf("create table %s like %s",
		 mysql_real_escape_string($archiveTablename),
		 mysql_real_escape_string($tablename)
		 );
  print $sql."<br>";
  $stmt = $pdo->query($sql);

  $sql = sprintf("insert into %s select * from %s",
		 mysql_real_escape_string($archiveTablename),
		 mysql_real_escape_string($tablename)
		 );
  print $sql."<br>";
  $stmt = $pdo->query($sql);
  
}

/*DB接続*/
$dsn = "mysql:dbname=$logdb;host=$dbhost;charset=utf8";
try {
  $pdo = new PDO( $dsn, $dbuser, $dbpassword );
}catch (PDOException $e){
  print ('Error:'.$e->getMessage());
  die();
 }

$tablename="niiMoodleLogJa"; archiveTable($pdo,$tablename);
$tablename="niiMoodleLogEn"; archiveTable($pdo,$tablename);
$tablename="niiMoodleLogCn"; archiveTable($pdo,$tablename); 
$tablename="niiMoodleLogKr"; archiveTable($pdo,$tablename); 

$tablename="niiMoodleTrackingJa"; archiveTable($pdo,$tablename);
$tablename="niiMoodleTrackingEn"; archiveTable($pdo,$tablename);
$tablename="niiMoodleTrackingCn"; archiveTable($pdo,$tablename); 
$tablename="niiMoodleTrackingKr"; archiveTable($pdo,$tablename); 

print "Archive Finished<br>";
print 'Please Add $tblname[] = $tablename."YEAR" in select-user.php'."<br>"

?></p>
</body>
</html>
