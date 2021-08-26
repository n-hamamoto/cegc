<?php
session_start();
include_once("../conf/config.php");
include_once("../auth/login.php");
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
if(empty($lang)){
   die("言語を選んでください。<a href='index.php'>戻る</a>");
}

$year=$_POST["year"];

$old=$_POST["old"];
if($old==0){
  $logtable="niiMoodleTracking";
}else if($old==1){
  $logtable="niiMoodleTracking_old";
}

if($_POST["separator"] == "CSV"){$separator=",";}
if($_POST["separator"] == "TSV"){$separator="\t";}

//update_by_files($logtable,$sql,$separator);

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

  try {
//    $fp = fopen($uploadFile, 'rb');

    $fp = new SplFileObject($uploadFile);
    $fp->setFlags(SplFileObject::READ_CSV);
    $fp->setCsvControl($separator);

    $i=0;

    // CSV/TSV読み込み
    print "<pre>";

    //while ($row = fgetcsv($fp,0,$separator)){
    foreach ($fp as $row){
      //print 'i='.$i.' '.$row[0].'<br>';
      print '#'.$i.": ";
      //var_dump($row);
      // 出力バッファの内容を送信
      @ob_flush();
      @flush();
      $i++;

      //とにかく出力（タイムアウト対策）
      if($i%100 == 0){
         @ob_flush();
         @flush();
      }

      //最初の行を項目名として$labelに保存
      if($i==1){
	$label=$row;
        // print count($label);
        print "<br>項目一覧<br>";
        for($ii=0; $ii<count($label); $ii++){
          print "$ii $label[$ii]<br>";

          //ID(ePTIDまたはeppn)を示す列番号
          //現行りんりん姫では「メールアドレス」の列
          if($label[$ii]=="メールアドレス"){$idRow=$ii;}
          //旧りんりん姫では「ユーザ名」の列
          if($label[$ii]=="ユーザ名"){$idRow=$ii;}
        }
        echo "<br>";
        echo "IDと見なす列：".$idRow."(".$label[$idRow].")"."<br>";

        //データ追加用sql準備
        //echo count($row)."<br>";
        //echo $idRow."<br>";
        $place_holders = array_pad(array(), count($row)-$idRow-1, '(?, ?, ?, ?, ?)');
        $values_clause = implode(', ',$place_holders);
        //print $values_clause;
        $stmt = $pdo->prepare('INSERT INTO '.$logtable.' (lang, year, eptid, field, value) VALUES'.$values_clause);
        //$stmt = $pdo->prepare("INSERT INTO $logtable (lang, year, eptid, field, value) VALUES(?, ?, ?, ?, ? )");
      }

      //2行目以降の処理
      $idType = "false";
      $id=$row[$idRow];

      //ePTIDの場合
      $patten ='/^https\:\/\//';
      if(preg_match($patten,$id)){
	$idType = "ePTID";
        $istart = $idRow+1;
        //1列目（https://idp-entityID!https://sp-entityID!EPTID）からEPTID部分を取得
	$tmp = preg_split('/!/',$id);
	$eptid = $tmp[2];
       }
      // eppnの場合
      $patten ='/^(.+)@'.$eppnDomain.'$/';
      if(preg_match($patten,$id)){
        $idType="eppn";
        $istart = $idRow+1;
        //eppn
        $tmp = preg_split('/@/',$id);
        $eptid = $tmp[0];
      }
      
      print $eptid."<br>";

      //ePTIDかeppnを見つけたら登録
      if($idType !== 'false'){
        $values_array = array();
	for($ii=$istart; $ii<count($row); $ii++){
          //print "$ii $lang $year $eptid $label[$ii] $row[$ii]<br>";
          // SQL実行
	  //$executed = $stmt->execute( array($lang, $year, $eptid, $label[$ii], $row[$ii]));
          //print_r(array($lang, $year, $eptid, $label[$ii], $row[$ii]));
	  array_push($values_array, $lang, $year, $eptid, $label[$ii], $row[$ii]);
        }
//        print "array";
//        print_r($values_array);
//        print "array";

       // SQL実行
          $executed = $stmt->execute( $values_array );
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
