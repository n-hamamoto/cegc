<?php

function updateDummy( $logtable, $lang, $year ){

	include("../conf/config.php");

        // DB接続
        $pdo = pdo_connect_db($logdb);

        //アップデート日時登録用のダミーデータを登録
        //旧dummyを削除
        $sql = 'DELETE FROM '.$logtable.' where lang = ? and year = ? and eptid = ?';
        $stmt = $pdo->prepare($sql);

        try{
                $executed = $stmt->execute(array($lang, $year, 'dummy'));
        } catch (Exception $e){
                print('Import Error:'.$e->getMessage());
                die();
        }

        //dummyを登録
        $sql = 'INSERT INTO '.$logtable.' (lang, year, eptid) VALUES (?, ?, ?)';
        $stmt = $pdo->prepare($sql);

        try{
                $executed = $stmt->execute( array($lang, $year, 'dummy') );
        } catch (Exception $e){
                print('Import Error:'.$e->getMessage());
                die();
        }

	$pdo = null;
}
?>
