<?php

if(php_sapi_name() == 'cli'){
}else{
  if($_SESSION["auth"]==="true"){
  }else{
    if( headers_sent() ){}else{
	header("Location: https://".$documentRoot."logout.php");
	die('unauthorized auth access detected');
    }
  }
}

function requireSubAdmin(){
	if($_SESSION["isAdmin"] === 1 || $_SESSION["isSubAdmin"] === 1){
	}else{
 		header("Location: https://".$documentRoot."logout.php");
		die('unauthorized SubAdmin access detected');
	}
}

function requireGroupAdmin(){
	if($_SESSION["isAdmin"] === 1 || $_SESSION["isGroupAdmin"] === 1){
	}else{
 		header("Location: https://".$documentRoot."logout.php");
		die('unauthorized GroupAdmin access detected');
	}
}
function isAdmin(){
	if($_SESSION["isAdmin"] === 1){
		return true;
	}else{
		return false;
	}
}
function isSubAdmin(){
        if($_SESSION["isSubAdmin"] === 1){
                return true;
        }else{
                return false;
        }
}
function isGroupAdmin(){
        if($_SESSION["isGroupAdmin"] === 1){
                return true;
        }else{
                return false;
        }
}
?>
