<?php
  //echo $_SERVER['SERVER_NAME'];
  //echo $_SERVER['DOCUMENT_ROOT'];

if($_SESSION["auth"]==="true"){
  $br = "<br>";
}
else{
  $br = "\n";
  if( headers_sent() ){}else{
	header("Location: https://".$documentRoot."logout.php");
  }
}

function requireSubAdmin(){

	if($_SESSION["isAdmin"] === 1 || $_SESSION["isSubAdmin"] === 1){
		echo "test";
	}else{
 		header("Location: https://".$documentRoot."logout.php");
	}
}

function requireGroupAdmin(){
	if($_SESSION["isAdmin"] === 1 || $_SESSION["isGroupAdmin"] === 1){
	}else{
 		header("Location: https://".$documentRoot."logout.php");
	}
}
?>
