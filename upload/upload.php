<?php
session_start();
include_once("../conf/config.php");
include_once("../auth/login.php");
include_once("../lib/dblib.php");
include_once("upload-functions.php");
//権限のない人はログアウト
requireSubAdmin();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The result of upload</title>
</head>
<body>
<p><?php
print "<h1>登録結果</h1>";

$lang=$_POST["lang"];
if(empty($lang)){
   die("言語を選んでください。<a href='index.php'>戻る</a>");
}

$year=$_POST["year"];

$old=$_POST["old"];
if($old==0){
  $logtable="niiMoodleLog";
}else if($old==1){
  $logtable="niiMoodleLog_old";
}

if($_POST["separator"] == "CSV"){$separator=",";}
if($_POST["separator"] == "TSV"){$separator="\t";}

//var_dump($_FILES);

try {
//ファイルアップロードして，アップロード後のファイルを返す
  $uploadFile = upload_file();

  //文字コード設定
  set_upload_encoding($uploadFile);

  // DB接続 
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
    $fp = new SplFileObject($uploadFile);
    $fp->setFlags(SplFileObject::READ_CSV);
    $fp->setCsvControl($separator);

//    $fp = fopen($uploadFile, 'rb') || die("ファイルが開けません。<a href='index.php'>戻る</a>");

    $i=0;

    // CSV/TSV読み込み
    print "<pre>";

//    while ($row = fgetcsv($fp,0,$separator)){
    foreach ($fp as $row){
      $i++;

      //とにかく出力（タイムアウト対策）
      if($i%100 == 0){
          @ob_flush();
          @flush();
	}

      $idType="false";
      $read_base=0;

// ePTIDの場合
      $patten ='/^https\:\/\//';
      if(preg_match($patten,$row[0])){
	$idType="ePTID";
        //eptid
        $tmp = preg_split('/!/',$row[0]);
        $rowdata[2] = $tmp[2];
      }
// eppnの場合
      $patten ='/^(.+)@'.$eppnDomain.'$/';
      if(preg_match($patten,$row[0])){
	$idType="eppn";
	//eppn
        $tmp = preg_split('/@/',$row[0]);
	$rowdata[2] = $tmp[0];
      }

// eppn(2021.6以降の新型式の場合)
      $patten ='/^(.+)@'.$eppnDomain.'$/';
      for($ii = 0; $ii < 10; $ii++){

	 if(preg_match($patten,$row[$ii])){
	    $idType="eppn";
       	    $tmp = preg_split('/@/',$row[$ii]);
            $rowdata[2] = $tmp[0];
            $read_base=$ii;
            break;
         }

      }


// ePTIDかeppnを見つけたら登録
      if($idType !== 'false'){
	 //print_r($row);print "<br>";print "*".count($row)."<br>";
	 $rowdata[0]=$lang;
	 $rowdata[1]=$year;
	 //Status
	 $rowdata[3] = $row[$read_base +1];
	 //Start
	 $rowdata[4] = $row[$read_base +2];
	 //End
	 $rowdata[5] = $row[$read_base +3];
	 //ElapsedTime
	 $rowdata[6] = $row[$read_base +4];
	 //FinalTestJa
	 $rowdata[7] = $row[$read_base +5];
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
     print "登録が終了しました";
   } catch (Exception $e) {
     print $i."th row: Import error<br>";
     //fclose($e);
     throw $e;
   }
 } catch (Exception $e) {
   $msg = array('red', $e->getMessage());
 }

print "<a href='index.php'>戻る</a><br>";

fclose($fp);
?></p>
</body>
</html>
