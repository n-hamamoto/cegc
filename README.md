# 情報倫理eラーニング受講情報確認システム：cegc (CyberEtheics Grade Comfirmation)
これは，学認連携Moodleで提供されている「情報倫理eラーニング」の成績を確認するためのシステムです。

## 免責事項
本ソフトウエアは無保証であり，利用によって生じたいかなる損害に対しても作者は責任を負いません。

## 前提条件
受講情報確認システムは，phpを利用して開発しています。動作させるには以下の環境か必要です。

1. php
2. mysqlまたはmariadb
3. shibboleth

また，各大学内のIdPと連携してshibboleth SPとして動作することを前提としています。

インストールディレクトリ配下のsecureをshibbolethでアクセス保護するようapache等の設定を行ってください。

```
<Location /secure>
  AuthType shibboleth
  ShibRequestSetting requireSession 1
  Require valid-user
</Location>
```


## Install

ダウンロードしたソースを，適当なLAMP+Shibboleth SP環境にアップロードして公開してください。

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

storedIDの場合には，eduPersonTargetedID（persistent-id）が登録されているDBへのアクセス情報を記載してください。idpでは，本システムから，リモートでmysqlのテーブルを検索できるようあらかじめ設定しておいてください。
<pre>
$idpdbhost   = '';      #idpのホスト名
$idpdbuser   = '';      #データベースにアクセスするためのユーザ名
$idpdbpassword   = '';  #データベースにアクセスするためのパスワード
$idpdb = '';            #データベース名
</pre>

以上の変更が終了したら，config内にある下記のphpでDBの初期設定をおこなってください。
<pre>
cd [config_dir]
sh ./init.sh
</pre>

