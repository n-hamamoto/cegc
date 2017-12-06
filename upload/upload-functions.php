<?php

//文字コード設定
function set_upload_encoding($uploadFile){
  $detect_order = 'ASCII,JIS,UTF-8,CP51932,SJIS-win';
  setlocale(LC_ALL,'ja_JP.UTF-8');

  $buffer = file_get_contents($uploadFile); 
  $encoding = mb_detect_encoding($buffer, $detect_order, true);
  if(!$encoding){
     unset($buffer);
     throw new RuntimeException('Character set detection failed');
  }
  file_put_contents($uploadFile, mb_convert_encoding($buffer, 'UTF-8', $encoding));
  unset($buffer);
}

//ファイルアップロード実行する関数
function upload_file(){
//print_r($_FILES);
try{
  if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
    if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $_FILES["upfile"]["name"])) {
      chmod("files/" . $_FILES["upfile"]["name"], 0644);
      $uploadFile = "files/" . $_FILES["upfile"]["name"];
      return $uploadFile;
      echo $_FILES["upfile"]["name"] . "をアップロードしました。";
    } else {
      throw new RuntimeException("ファイルをアップロードできません。");
    }
  } else {
    throw new RuntimeException("ファイルが選択されていません。");
  }
 } catch (Exception $e) {
   $msg = array('red', $e->getMessage());
 }
}

//過去年度のCSVを登録する際に過去のテーブルを作成する関数
function create_archive_tbl($logtable,$logdb){
// archive mode
    if(isset($_POST["year"])){
      $year=$_POST["year"];
      $logtableOrg=$logtable;
      $logtable=$logtable.$year;

      /* DB接続 */
      $pdo = pdo_connect_db($logdb);

      //テーブルの作成
      $sql = "CREATE TABLE IF NOT EXISTS $logtable LIKE $logtableOrg";
      pdo_query_db($pdo,$sql);
    
      // 接続を閉じる 
      $stmt = null;
      $pdo  = null;

      //作成したテーブルを返す
      return $logtable;
    }else{
      exit("input error: select archive year");
    }
}

?>