<?php
  //echo $_SERVER['SERVER_NAME'];
  //echo $_SERVER['DOCUMENT_ROOT'];

if($_SESSION["auth"]==="true"){
  $br = "<br>";
}
else{
  header("Location: https://".$documentRoot."logout.php");
  $br = "\n";
}
?>
