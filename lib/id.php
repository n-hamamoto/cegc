<?php

function getEptid($principalName){

  global $salt,$idpdbhost,$idpdbuser,$idpdbpassword,$idpdb,$sw,$lowercaseId;

  if($lowercaseId == 1){ 
  // idを小文字する
  $principalName = mb_strtolower($principalName);
  }

  if($sw == 0){
    /* computedIdの場合 */
    $str ="https://security-learning.nii.ac.jp/shibboleth-sp!".$principalName."!".$salt;
    $eptid = base64_encode(sha1($str,TRUE));
  }else{
    /* storedIDの場合 */
    /* DB connection */
    $dsn = "mysql:dbname=$idpdb;host=$idpdbhost;charset=utf8";
    try {
      $pdo = new PDO( $dsn, $idpdbuser, $idpdbpassword );
    }catch (PDOException $e){
      print ('Error:'.$e->getMessage());
      die();
    }
    
    $sql = sprintf("select persistentId from shibpid where principalName = '%s' and peerEntity = 'https://security-learning.nii.ac.jp/shibboleth-sp'",$principalName);
    $stmt = $pdo->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $eptid = $result["persistentId"];
  }

  return $eptid;

  }
?>
