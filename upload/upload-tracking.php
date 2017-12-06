<?php
session_start();
include_once("../conf/config.php");
include("../auth/login.php");
include_once("../lib/dblib.php");
include_once("upload-functions.php");
//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload Results</title>
</head>
<body>
<p><?php
$lang=$_POST["lang"];
$year=$_POST["year"];
$logtable="niiMoodleTracking";

if($_POST["separator"] == "CSV"){$separator=",";}
if($_POST["separator"] == "TSV"){$separator="\t";}

//update_by_files($logtable,$sql,$separator);

try {
//ファイルアップロードして，アップロード後のファイルを返す
    $uploadFile = upload_file();

  //文字コード設定
  set_upload_encoding($uploadFile);

  /* DB接続 */
  $pdo = pdo_connect_db($logdb);

  //テーブルのクリア
  //$sql ='TRUNCATE TABLE '.$logtable;
  $sql='DELETE FROM '.$logtable.' where lang = ? and year = ?';
  //print $sql;
  $stmt = $pdo->prepare($sql);
  $executed = $stmt->execute( array( $lang, $year) );

  //データ追加用sql準備
  $stmt = $pdo->prepare("INSERT INTO 
$logtable 
(lang, year, eptid, Status, Start, Latest, Score, 
0_1, 0_2, 0_3, 0_4, 
1_1, 1_2, 1_3_1, 1_3_2, 1_4_1, 1_4_2, 1_5_1, 1_6,
2_1, 2_2, 2_3_1, 2_3_2, 2_4_1, 2_4_2, 2_4_3, 2_5_1, 2_5_2, 2_6,
3_1, 3_2, 3_3_1, 3_3_2, 3_4_1, 3_4_2, 3_5_1, 3_6,
4_1, 4_2, 4_3_1, 4_3_2, 4_4_1, 4_4_2, 4_4_3, 4_4_4, 4_5_1, 4_5_2, 4_6,
5_1, 5_2, 5_3_1, 5_3_2, 5_4_1, 5_4_2, 5_4_3, 5_5_1, 5_5_2, 5_6,
6_1, 6_2, 6_3_1, 6_3_2, 6_4_1, 6_4_2, 6_4_3, 6_4_4, 6_5_1, 6_5_2, 6_6,
7_1, 7_2, 7_3_1, 7_3_2, 7_4_1, 7_4_2, 7_4_3, 7_5_1, 7_6,
8_1, 8_2, 8_3_1, 8_3_2, 8_4_1, 8_4_2, 8_5_1, 8_6, 
9_1, 9_2, 9_3)
VALUES(
?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?, ?, ?, ?, ?, ?, ? );");

  try {
    $fp = fopen($uploadFile, 'rb');
    $i=0;

    // CSV/TSV読み込み
    print "<pre>";
    while ($row = fgetcsv($fp,0,$separator)){
      $i++;
      if($i==1){
	$label=$row;
      }

      $patten ='/^https\:\/\//';
      if(preg_match($patten,$row[0])){
	$tmp = preg_split('/!/',$row[0]);
	$row[0] = $tmp[2];
        if($row[4] == "-"){
	   $row[4]=0;
        }
	$ii=$i-1;
        print $row[0]." | ".$row[1]." | ".$row[4]."  #".$ii." ";
    	// langとyearを追加
	array_unshift($row, $lang, $year);
//	array_push($row, 'now()', 'now()');
//        print_r($row);print "<br>";
	print "*".count($row)."<br>";
        // SQL実行
	$executed = $stmt->execute($row);
       }
     }
     print "</pre>";
     print "登録が終了しました<a href='index.php'>戻る</a><br>";
   } catch (Exception $e) {
     print $i."th row: Import error<br>";
     //fclose($e);
     throw $e;
   }
 } catch (Exception $e) {
   $msg = array('red', $e->getMessage());
 }

?></p>
</body>
</html>
