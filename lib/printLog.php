<?php

function printLog($in){
	include("../auth/login.php");
	echo date('Y-m-d H:i:s');
	print " $in".$br;
}

?>
