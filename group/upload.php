<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sample</title>
</head>
<body>
<p><?php
   include_once("../conf/config.php");
   include_once("../lib/dblib.php");
   include_once("../lib/csvlib.php");
$groupId = $_POST["groupName"];
$conn = connectdb($logdb);
$sql = sprintf("INSERT INTO groupInfo(groupId) VALUES('".$groupId."')");
print $sql."<br>";
$res = wrap_mysql_query($sql, $conn);
mysql_close($conn);

$sql = sprintf("INSERT INTO groupMember(idNumber, groupId) VALUES(?, '".$groupId."')");
echo $sql."<br>";
csvUpload($sql);

   //テーブルのクリア
//$sql ='TRUNCATE TABLE mdl2log';
//   $stmt = $pdo->prepare($sql);
//   $executed = $stmt->execute();
//   echo "pdo-end-2";

   //データ追加用sql準備
//   $stmt = $pdo->prepare('INSERT INTO mdl2log VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
//   echo "pdo-end-3";


?></p>
</body>
</html>
