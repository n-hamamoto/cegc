<?php
$dbhost = '';		#データベースのホスト名，または，IPアドレス
$dbuser = ''; 		#データベースにアクセスするためのユーザ名
$dbpassword = '';	#データベースにアクセスするためのパスワード
$logdb   = '';		#データベース名
$inituser = '';		#最初に作るユーザ
$eppnDomain = '';       #eppnで利用しているドメイン

# 0: computedID, 1: storedID
$sw = 0; 
$salt = "";

$idpdbhost   = ''; 	#idpのホスト名
$idpdbuser   = '';   	#データベースにアクセスするためのユーザ名
$idpdbpassword   = '';	#データベースにアクセスするためのパスワード
$idpdb = ''; 		#データベース名

//useridを小文字に統一する
$lowercaseId = 1;

#$documentRoot = $_SERVER['SERVER_NAME'].'/GU/cyberethics-log/';
$documentRoot = ''.'/'; #本システムのURL(例: example.ac.jp/)を指定してください

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!

