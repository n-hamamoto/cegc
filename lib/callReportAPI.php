<?php
function callReportAPI($func, $cmid, $from, $to){

	include("../conf/config.php");

	$data = array(
        	'token' => $token
	);
	if($func == 0){
        	$data['func'] = $funcCourseID;
	}else if($func == 1){
        	$data['func'] = $funcReport;
	}else{
		print "func = 0 (moduleID), 1 (report)\n";
		die();
	}
	if($cmid != ''){
        	$data['cmid'] = $cmid;
	};
	if($from != '' ){
        	$data['from'] = $from;
	};
	if($to != '' ){
        	$data['to'] = $to;
	};

	// apiエンドポイント
	$url = 'https://lms.nii.ac.jp/blocks/niigradesapi/api.php';
	//$url = 'https://www.media.gunma-u.ac.jp';

	$curlerr = null;

	// cURLの初期化
	$ch = curl_init($url);

	// cURLオプション設定
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	//接続
	$html = curl_exec($ch);

	//接続エラー処理
	if(curl_errno($ch)){
        	$CURLERR .= 'curl_errno:'. curl_errno($ch)."\n";
        	$CURLERR .= 'curl_error:'. curl_error($ch)."\n";
        	$CURLERR .= 'curl_getinfo:'. "\n";
        	foreach(curl_getinfo($ch) as $key => $val){
                	$CURLERR .= ' -- '. $key .':'. $val ."\n";
        	}
        	echo nl2br($CURLERR);
		curl_close($ch);
		return 1;
	//      exit();
	}

	//接続エラーの無いとき
	curl_close($ch);
	
	return  $html;
}
?>

