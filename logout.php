<?php
session_start();
include("conf/config.php");


if($_SESSION["auth"]==="true"){
  //正常ログアウト
  $logoutFile="index-logout.php";
}
else{
  //ユーザ登録されていない・セッションタイムアウト等
  $logoutFile="index-error.php";
}

//セッション変数のクリア
$_SESSION = array();
//クッキーの破棄 session_name = PHPSESSID
if(isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() -3600, '/');
}
//セッションクリア
session_destroy();

header("Location: /Shibboleth.sso/Logout?return=https://".$documentRoot.$logoutFile);

?>
