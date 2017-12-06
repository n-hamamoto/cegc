<?php

function csvUpload($sql){

  include("../conf/config.php");

  try {
    echo "upload";
    $dir="./files/";
    echo $dir;
    if(file_exists($dir)){}else{
      echo "directory not";
      throw new RuntimeException("Directory not exists");
    }
    print "directory ".$_FILES["upfile"]["tmp_name"];
    if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
      if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $_FILES["upfile"]["name"])) {
	chmod("files/" . $_FILES["upfile"]["name"], 0644);
	$uploadFile = "files/" . $_FILES["upfile"]["name"];
	// echo $_FILES["upfile"]["name"] . "をアップロードしました。";
      } else {
	throw new RuntimeException("ファイルをアップロードできません。");
      }
    } else {
      throw new RuntimeException("ファイルが選択されていません。");
    }

    echo "kanji-code";
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

    echo "pdo";
    /* DB接続 */
    $dsn = "mysql:dbname=$logdb;host=$dbhost;charset=utf8";
    echo $dsn;
    $pdo = new PDO( $dsn,$dbuser,$dbpassword);
    //,
    //		array(
    // カラム型に合わない値がINSERTされようとしたときSQLエラーとする
    //			PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode='TRADITIONAL'",
    // SQLエラー発生時にPDOException
    //			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // プリペアドステートメントのエミュレーション無効化
    //			PDO::ATTR_EMULATE_PREPARES => false,
    //			)
    //		  ); 
    echo "pdo-end-1";

    //データ追加用sql準備
    $stmt = $pdo->prepare($sql);
    print $sql;
    echo "pdo-end-3";

    try {
      $fp = fopen($uploadFile, 'rb');
      $i=0;
      while ($row = fgetcsv($fp)){
	$i++;
	print_r($row);
	//print "*".count($row)."<br>";
	$executed = $stmt->execute($row);
	if(isset($executed)){
	}else{
	  print $i."th row: Import error<br>";
	};
      }
    } catch (Exception $e) {
      fclose($e);
      throw $e;
    }
    
  } catch (Exception $e) {
    //    $msg = array('red', $e->getMessage());
    echo $e->getMessage();
  }
  }
?>
