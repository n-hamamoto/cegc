<?php

if($_POST["userid"]==="admin" and $_POST["passwd"]==="10miok@se14"){
  // ログイン画面からの認証の場合
  $_SESSION["userId"]=$_POST["userid"];
  //  $_SESSION["passwd"]=$_POST["passwd"];
  $_SESSION["auth"]="true";
}else{
  $_SESSION["auth"]="false";
}

?>