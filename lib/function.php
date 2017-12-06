<?php
    // 文字列出力関数（XSS用エスケープ関数）
    function xss_char_echo($str) {
        echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    // XSSエスケープ関数
    function xss_char($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    // 文字列出力関数
    function char_echo($str) {
        echo $str;
    }

    // 変数受け取り時の確認
    function chk_input($val,$var){
       if( !isset($val) || $val === '' ){
       	   exit($var."が入力されていません");
       }else{
	   return $val;
       }
    }
?>