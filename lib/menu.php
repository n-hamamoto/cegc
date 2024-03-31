<div id="logout" >
<a href="logout.php">ログアウト</a>
</div>

<div id="kanban">
情報倫理eラーニング成績確認システム
</div>
<div id="menu">
<div id="upper">
<ul class="cf">
<li class="long" >
<a href="search/group/">担当クラスの実施結果</a>
</li>
<?php
if($_SESSION["isAdmin"]===1 || $_SESSION["isSubAdmin"]===1){
print '
<li><a href="search/user/">ユーザ検索</a></li>
<li><a href="search/user-old/">ユーザ検索(旧)</a></li>
';
};
?>
<?php
if($_SESSION["isAdmin"]===1 || $_SESSION["isSubAdmin"]===1 ||  $_SESSION["isGroupAdmin"]===1){
print '
<li class="opAndClToggle" id="admin_t"><a >管理用</a></li>
';
};
?>
</ul>
</div>
<?php
if($_SESSION["isAdmin"]===1 || $_SESSION["isSubAdmin"]===1 ||  $_SESSION["isGroupAdmin"]===1){
print '
<div id="lower">
<ul class="opAndClblock cf" id="admin_b">
';
};
if($_SESSION["isAdmin"]===1 || $_SESSION["isSubAdmin"]===1){
print '<li class="long"><a href="upload/">データ登録</a></li>';
};
if($_SESSION["isAdmin"]===1 || $_SESSION["isGroupAdmin"]===1){
print '
<li class="long"><a href="group/add">グループ登録</a></li>
<li class="long"><a href="group/delete">グループ削除</a></li>
<li class="long"><a href="group/modify">グループ変更</a></li>
<li class="long"><a href="group/admin">グループ管理者</a></li>
<li class="long"><a href="user/add">ユーザ登録</a></li>
<li class="long"><a href="user/delete">ユーザ削除</a></li>
';
};
?>
</ul>
</div>
</div>
