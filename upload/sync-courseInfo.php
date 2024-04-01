<?php
session_start();
include_once("../conf/config.php");
include_once("../auth/login.php");
include_once("../lib/dblib.php");
require_once("../lib/callReportAPI.php");
require_once("../lib/update_courseInfo.php");

//権限のない人はログアウト
requireSubAdmin();
?>
<?
update_courseInfo();

if($_SESSION["auth"] === "true"){
        print'<a href=".">戻る</a>';
}
?>

