<?php

function pdo_connect_legacy_db($logdb){
  global $dbhost,$dbuser,$dbpassword;
  $initArr = array(PDO::MYSQL_ATTR_INIT_COMMAND => "set SESSION time_zone = 'Asia/Tokyo'");
  try{
    // DBへ接続
    $dsn = "mysql:dbname=$logdb;host=$dbhost;charset=utf8";
    $pdo = new PDO($dsn, $dbuser, $dbpassword, $initArr);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    return $pdo;
  }catch(PDOException $e){
     header('Content-Type: text/plain; charset=UTF-8', true, 500);
     exit($e->getMessage());
  }
}

function pdo_query_db($pdo,$sql){
  // クエリ
  try{
     $stmt = $pdo->query($sql);
     return $stmt;
  }catch(PDOException $e){
     header('Content-Type: text/plain; charset=UTF-8', true, 500);
     exit($e->getMessage());
  }
}

function connectdb($conndb){
  global $dbhost,$dbuser,$dbpassword;
//  print "<br>$dbhost,$dbuser<br>";
//  print "$conndb<br>";
  mysql_set_charset('utf8');
  $conn = mysql_connect($dbhost,$dbuser,$dbpassword) or exit('データベースに接続できません');
  mysql_select_db($conndb, $conn);
  //  mysql_set_charset('utf8');
  return $conn;
  }

function wrap_mysql_query($sql, $conn){
  mysql_set_charset('utf8');
  $result = mysql_query($sql, $conn);
  if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
  }
  return $result;
}
?>
