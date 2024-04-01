<?php

function printLog($in){
	include_once("../auth/login.php");
	echo date('Y-m-d H:i:s');
	print " $in".$br;
}

?>
