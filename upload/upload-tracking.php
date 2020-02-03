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

  try {
    $fp = fopen($uploadFile, 'rb');
    $i=0;

    // CSV/TSV読み込み
    print "<pre>";
    while ($row = fgetcsv($fp,0,$separator)){
    print 'i='.$i.' '.$row[0].'<br>';
    // 出力バッファの内容を送信
    @ob_flush();
    @flush();
    //  print_r($row);
      $i++;
      //最初の行を項目名として$labelに保存
      if($i==1){
	$label=$row;
        print count($label);
        for($ii=0; $ii<count($label); $ii++){
          print "$ii $label[$ii]<br>";
        }
        //とにかく出力（タイムアウト対策）
	if($i%100 == 0){
          @ob_flush();
          @flush();
	}
       //データ追加用sql準備
       print count($row);
       $place_holders = array_pad(array(), count($row)-1, '(?, ?, ?, ?, ?)');
       $values_clause = implode(', ',$place_holders);
       //print $values_clause;
       $stmt = $pdo->prepare('INSERT INTO '.$logtable.' (lang, year, eptid, field, value) VALUES'.$values_clause);
       //$stmt = $pdo->prepare("INSERT INTO $logtable (lang, year, eptid, field, value) VALUES(?, ?, ?, ?, ? )");
      }

      //2行目以降の処理
      $idType = "false";

      //ePTIDの場合
      $patten ='/^https\:\/\//';
      if(preg_match($patten,$row[0])){
	$idType = "ePTID";
        //1列目（https://idp-entityID!https://sp-entityID!EPTID）からEPTID部分を取得
	$tmp = preg_split('/!/',$row[0]);
	$eptid = $tmp[2];
       }
       // eppnの場合
      $patten ='/^(.+)@'.$eppnDomain.'$/';
      if(preg_match($patten,$row[0])){
        $idType="eppn";
        //eppn
        $tmp = preg_split('/@/',$row[0]);
        $eptid = $tmp[0];
      }

      //ePTIDかeppnを見つけたら登録
      if($idType !== 'false'){
        $values_array = array();
	for($ii=1; $ii<count($row); $ii++){
          // print "$ii $lang $year $eptid $label[$ii] $row[$ii]<br>";
          // SQL実行
//	  $executed = $stmt->execute( array($lang, $year, $eptid, $label[$ii], $row[$ii]));
//          print_r(array($lang, $year, $eptid, $label[$ii], $row[$ii]));
	  array_push($values_array, $lang, $year, $eptid, $label[$ii], $row[$ii]);
        }
        //print "array";
        //print_r($values_array);
        //print "array";

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
