<html>
<head>
<?php include("./conf/config.php") ?>
<?php include("./lib/header.php") ?>
<title>エラー</title>
</head>
<body>
<div id="main">
<h1>情報倫理eラーニング成績確認システム</h1>
<div id="warn">ログインできません。ユーザ登録されていません。</div>
<p>（ログインしていた場合には，セッションがタイムアウトしましたので，再度ログインを行ってください。）</p>
<div>
<a href="https://<?php print $documentRoot ?>">ログイン画面に戻る</a> 
</div>
</div>
</body>
</html>
