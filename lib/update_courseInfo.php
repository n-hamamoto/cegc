<?php
include_once("../conf/config.php");
include_once("../lib/dblib.php");
include_once("../lib/callReportAPI.php");

function update_courseInfo(){
	include("../conf/config.php");
	include("../auth/login.php");

	// 設定されている年度をDBから取得
	$pdo = pdo_connect_db($logdb);
	$sql = sprintf("select year from defaultAcademicYear");
	$stmt = pdo_query_db($pdo,$sql);
	$data= $stmt->fetch(PDO::FETCH_ASSOC);
	$year = $data['year'];
	$pdo = null;

        // APIでCourseIDを取得
        $html = callReportAPI(0, '', '', '');
        $obj = json_decode($html);
        if( count($obj) == 0 ){
		print('Something wrong about API connetion');
                die();
        }
	//取得成功

	// DB接続してcourseInfoの全レコードを削除
	$pdo = pdo_connect_db($logdb);
	$sql = 'DELETE FROM courseInfo where year = ?';
	$stmt = $pdo->prepare($sql);
	try{
		$executed = $stmt->execute(
			array( $year )
		);
	} catch (Exception $e){
     		print('Import Error:'.$e->getMessage());
         	print $br;
                die();
	}
	$pdo = null;

        // DB接続
        $pdo = pdo_connect_db($logdb);
        $sql = 'INSERT INTO courseInfo (coursemoduleid, courseid, coursefullname, courseshortname, modinstanceid, visibility, modname, modnamelocal, modinstancename, year) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);

        foreach($obj as $o){
                //print "###".$br;
                //var_dump($o);
                print $o->modname.", ";
                print $o->coursemoduleid.", ";
                print $o->courseshortname.", ";
                print $o->coursefullname.", ";
                print $o->visibility.",";
                print $year.$br;

                //SQL実行
                try{
             		$executed = $stmt->execute(
					array(
						$o->coursemoduleid, 
						$o->courseid, 
						$o->coursefullname, 
						$o->courseshortname, 
						$o->modinstanceid, 
						$o->visibility, 
						$o->modname, 
						$o->modnamelocal, 
						$o->modinstancename,
						$year
					 ));
           	} catch (Exception $e){
                 	print('Import Error:'.$e->getMessage());
			print $br;
			//die();
           	}

	}
}

?>
