<?php
include_once("../conf/config.php");
include_once("../lib/dblib.php");
include_once("../lib/callReportAPI.php");

function update_courseInfo(){
	include("../conf/config.php");
	include("../auth/login.php");

	// DB接続
	$pdo = pdo_connect_db($logdb);
	$sql = 'INSERT INTO courseInfo (coursemoduleid, courseid, coursefullname, courseshortname, modinstanceid, visibility, modname, modnamelocal, modinstancename) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)';
	$stmt = $pdo->prepare($sql);


	// APIでCourseIDを取得
        $html = callReportAPI(0, '', '', '');
        $obj = json_decode($html);
        foreach($obj as $o){
                print "###".$br;
                //var_dump($o);
                print $o->modname.", ";
                print $o->coursemoduleid.", ";
                print $o->courseshortname.", ";
                print $o->coursefullname.", ";
                print $o->visibility.$br;

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
						$o->modinstancename
					 ));
           	} catch (Exception $e){
                 	print('Import Error:'.$e->getMessage());
			print $br;
			//die();
           	}

	}
}

?>