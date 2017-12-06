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
$logtable="niiMoodleLog";

if($_POST["separator"] == "CSV"){$separator=",";}
if($_POST["separator"] == "TSV"){$separator="\t";}

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
  $stmt = $pdo->prepare($sql);
  $executed = $stmt->execute( array( $lang, $year) );

  //データ追加用sql準備
  $stmt = $pdo->prepare("INSERT INTO 
   $logtable (lang,year,eptid,Status,Start,End,ElapsedTime,FinalTest)
   VALUES(?, ?, ?, ?, ?, ?, ?, ?);");

  try {
    $fp = fopen($uploadFile, 'rb');
    $i=0;

    // CSV/TSV読み込み
    print "<pre>";
    while ($row = fgetcsv($fp,0,$separator)){
      $i++;
      $patten ='/^https\:\/\//';
      if(preg_match($patten,$row[0])){
	 //print_r($row);print "<br>";print "*".count($row)."<br>";
	 $rowdata[0]=$lang;
	 $rowdata[1]=$year;
	 //eptid
	 $tmp = preg_split('/!/',$row[0]);
	 $rowdata[2] = $tmp[2];
	 //Status
	 $rowdata[3] = $row[1];
	 //Start
	 $rowdata[4] = $row[2];
	 //End
	 $rowdata[5] = $row[3];
	 //ElapsedTime
	 $rowdata[6] = $row[4];
	 //FinalTestJa
	 $rowdata[7] = $row[5];
	 print " | ".$rowdata[0];
	 print " | ".$rowdata[1];
	 print " | ".$rowdata[2];
	 print " | ".$rowdata[3];
	 print " | ".$rowdata[4];
	 print " | ".$rowdata[5];
	 print " | ".$rowdata[6];
	 print " | ".$rowdata[7];
	 print "  #".$i;
	 print "<br>";
         
// SQL実行
         if($rowdata[6] != "-"){
  	    $executed = $stmt->execute($rowdata);
         }else{
            print "SQL excution Skipped"."<br>";
         }
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
