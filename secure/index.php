<?php

session_start();

include("../conf/config.php");
include("../lib/dblib.php");

//SERVER変数を返す関数（リバースプロキシの場合はHTTP_ のついた変数を返す）
function get_server_var($val){
 if( $_SERVER["$val"] ){ return $_SERVER["$val"]; };
 if( $_SERVER["HTTP_$val"] ){ return $_SERVER["HTTP_$val"]; };
 return null;
}

$pattern = '/^.+\@gunma\-u\.ac\.jp$/';

$subject = get_server_var('EPPN');

if(preg_match($pattern,$subject)){

  $t = preg_split('/\@/',$subject);
  $userid=$t[0];

  // DBへ接続
  $pdo = pdo_connect_db($logdb);

  //登録ユーザ一覧検索
  $sql = sprintf("select userId from user");
  // クエリ
  $stmt = pdo_query_db($pdo,$sql);

  //登録ユーザをuidに代入  
  $i=0;
  while($result= $stmt->fetch(PDO::FETCH_ASSOC)){
    $uid[$i] = $result['userId'];
    $i++;
  }
  $imax = $i;

  //ユーザが登録済みかを確認：登録済みならサービスを利用を認可
  $auth = "false";
  for($i=0;$i<$imax;$i++){
    if($userid === $uid[$i]){
      $auth="true";
    }
  }

  if($auth === "true"){
    //認可ユーザの情報をセッション変数に代入
    $_SESSION["auth"]="true";
    $_SESSION["userId"]=$userid;
    $_SESSION["jasn"] = get_server_var('JASN');
    $_SESSION["jaGivenName"] = get_server_var('JAGIVENNAME');
    $_SESSION["jaou"] =  get_server_var('JAOU');

    //管理ユーザかどうかを確認
    $stmt = $pdo->prepare("select isAdmin ,isTeacher, isGroupAdmin, isSubAdmin from user where userId = :userid");
    $stmt->execute( array(':userid' => $userid) );

    while($result= $stmt->fetch(PDO::FETCH_ASSOC)){
      $_SESSION["isAdmin"]=$result['isAdmin'];
      $_SESSION["isSubAdmin"]=$result['isSubAdmin'];
      $_SESSION["isGroupAdmin"]=$result['isGroupAdmin'];
      $_SESSION["isTeacher"]=$result['isTeacher'];
    }
  }

 //切断
 $pdo = null;

}

header("Location: https://".$documentRoot."main.php");
?>
