# 情報倫理eラーニング受講情報確認システム：cegc (CyberEtheics Grade Comfirmation)
これは，学認連携Moodleで提供されている「情報倫理eラーニング」の成績を確認するためのシステムです。

## 前提条件
受講情報確認システムは，phpを利用して開発しています。動作させるには以下の環境か必要です。

1. php
2. mysqlまたはmariadb
3. shibboleth

また，各大学内のIdPと連携してshibboleth SPとして動作することを前提としています。

## Install

設定は，confディレクトリ内にあります。
conf/config.phpの以下のパラメータを設定します。
<pre>
$dbhost = '';           #データベースのホスト名，または，IPアドレス
$dbuser = '';           #データベースにアクセスするためのユーザ名
$dbpassword = '';       #データベースにアクセスするためのパスワード
$logdb   = '';          #データベース名
$inituser = '';         #最初に作るユーザ
</pre>

IdPでのedupersonTargetedIDがcomputedIDかstoredIDかを指定してください。
<pre>
$sw = 0; # 0: computedID, 1: storedID
</pre>
computedIDの場合にはsaltが必要となります。
<pre>
$salt = "";
</pre>

storedIDの場合には，eduPersonTargetedID（persistent-id）が登録されているDBへのアクセス情報を記載してください。
<pre>
$idpdbhost   = '';      #idpのホスト名
$idpdbuser   = '';      #データベースにアクセスするためのユーザ名
$idpdbpassword   = '';  #データベースにアクセスするためのパスワード
$idpdb = '';            #データベース名
</pre>



