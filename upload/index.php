<?php 
session_start();
include_once("../conf/config.php");
include("../auth/login.php");

//権限のない人はログアウト
if($_SESSION["isAdmin"] === "1" || $_SESSION["isSubAdmin"] === "1"){}else{
 header("Location: https://".$documentRoot."logout.php");
}

?>
<html>
<head>
<script type="text/javascript" src="../../jquery.min.js"></script>
<script type="text/javascript" src="./select-user.js"></script>
<?php include("../lib/header.php");?>
<title>Upload Data</title>
</head>
<body>
<?php
include("../lib/menu.php");
include("../lib/displayUpdate.php");
?>
<div id="main">
<div class="csvSubmitForm">
<h1>総合テストデータ登録</h1>
<form action="./upload/upload.php" method="post" enctype="multipart/form-data">
ファイル：<br />
<input type="file" name="upfile" size="30" /><br />
<br />
<div>
  <input type="radio" name="lang" value="Ja"/>日本語
  <input type="radio" name="lang" value="En"/>英語
  <input type="radio" name="lang" value="Cn"/>中国語
  <input type="radio" name="lang" value="Kr"/>韓国語
</div>

<!--過去年度への登録オプション -->
<div class="opAndClToggle">
  過去年度のDBへデータ登録する場合にはクリック
</div>
<div class="opAndClblock">
  <input type="checkbox" name="archive" value="1">過去年度のデータを登録する<br />
  <select name="year">
<?php
  //$academicYear = date( "Y" ) -1;
  $pdo = pdo_connect_db($logdb);
  $sql = sprintf("select year from defaultAcademicYear");
  $stmt = pdo_query_db($pdo,$sql);
  $data= $stmt->fetch(PDO::FETCH_ASSOC);
  $academicYear = $data['year'];

  //print "<option value='current' selected>選択してください</option>";
  for($year=$academicYear; $year>2011; $year--){
    if($year == $academicYear){
        printf("<option value='%s' selected>%s年度</option>", $year, $year);
    }else{
        printf("<option value='%s'>%s年度</option>", $year, $year);
    }
  }
  $pdo = null;
?>
  </select>
  <br /> 
</div>

<br/>
<div>
登録データの入手元<br>
  <input type="radio" name="old" value="0" checked/>
  <a href="https://lms.nii.ac.jp/mod/quiz/report.php?id=1282&mode=overview" target="_blank">現行（倫倫姫の情報セキュリティ教室）</a>
  <br/>
  <input type="radio" name="old" value="1"/>
  <a href="https://lms.nii.ac.jp/mod/quiz/report.php?id=674&mode=overview" target="_blank">旧版（倫倫姫と学ぼう！情報倫理）</a>
</div>

<!--過去年度への登録（ここまで） -->
<br />
<div>
ファイルフォーマット<br />
  <input type="radio" name="separator" value="CSV" checked/>CSV形式<br />
  <input type="radio" name="separator" value="TSV"/>テキスト（TSV）形式
</div>
<br />
<input type="submit" value="アップロード" />
</form>
</div>
<div class="csvSubmitForm">
<h1>コーストラッキングデータ登録</h1>
<form action="./upload/upload-tracking.php" method="post" enctype="multipart/form-data">
  ファイル：<br />
  <input type="file" name="upfile" size="30" /><br />
  <br />
  <input type="radio" name="lang" value="Ja"/>日本語
  <input type="radio" name="lang" value="En"/>英語
  <input type="radio" name="lang" value="Cn"/>中国語
  <input type="radio" name="lang" value="Kr"/>韓国語

<!--過去年度への登録オプション -->
  <div class="opAndClToggle">
  過去年度のDBへデータ登録する場合にはクリック
  </div>
  <div class="opAndClblock">
  <input type="checkbox" name="archive" value="1">  過去年度のデータを登録する<br />
  <select name="year">
<?php
  $academicYear = date( "Y" ) -1;
  $pdo = pdo_connect_db($logdb);
  $sql = sprintf("select year from defaultAcademicYear");
  $stmt = pdo_query_db($pdo,$sql);
  $data= $stmt->fetch(PDO::FETCH_ASSOC);
  $academicYear = $data['year'];

  //print "<option value='current' selected>選択してください</option>";
  for($year=$academicYear; $year>2011; $year--){
    if($year == $academicYear){
        printf("<option value='%s' selected>%s年度</option>", $year, $year);
    }else{
        printf("<option value='%s'>%s年度</option>", $year, $year);
    }
  }
  $pdo = null;
?>
  </select>
  <br />
  </div>

<br/>
<div>
登録データの入手元<br>
  <input type="radio" name="old" value="0" checked/>
  <a href="https://lms.nii.ac.jp/grade/export/txt/index.php?id=56" target="_blank">現行（倫倫姫の情報セキュリティ教室）</a>
  <br/>
  <input type="radio" name="old" value="1"/>
  <a href="https://lms.nii.ac.jp/mod/scorm/report.php?id=670" target="_blank">旧版（倫倫姫と学ぼう！情報倫理）</a>
</div>

<br />
<div>
ファイルフォーマット<br />
  <input type="radio" name="separator" value="CSV" />CSV形式<br />
  <input type="radio" name="separator" value="TSV" checked/>テキスト（TSV）形式
</div>
  <br />
<!--過去年度への登録（ここまで） -->
  <input type="submit" value="アップロード" />
  <br />
</form>
</div>
<div class="csvSubmitForm">
<h1>年度の設定</h1>
<form action="./upload/form-setAcademicYear.php" method="post" >
<p>
本システムでは，NIIがコースをリセットした時点で年度が替わることを想定しています。
</p>
<div style="margin-top: 10px; margin-bottom: 10px;">
<?php
  $current_year = date( "Y" );
  $pdo = pdo_connect_db($logdb);
  $sql = sprintf("select year from defaultAcademicYear");
  $stmt = pdo_query_db($pdo,$sql);
  $data= $stmt->fetch(PDO::FETCH_ASSOC);
  $configured_year = $data['year'];

  printf("現在は「%s年度」に設定されています。<br>",$configured_year);
  printf('<select name="year">');

  for($year=$current_year; $year>2011; $year--){
    if($year == $configured_year){
        printf("<option value='%s' selected>%s年度</option>", $year, $year);
    }else{
        printf("<option value='%s'>%s年度</option>", $year, $year);
    }
  }
  $pdo = null;
?>
  </select>
  <input type="submit" value="年度を設定する" />
  </div>
</form>
</div>
</div>
</body>
</html>
