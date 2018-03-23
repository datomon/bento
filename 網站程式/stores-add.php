<?php
include("logincheck.php");
include("consql.php");
header('Content-Type: application/json; charset=utf-8');

$sname = $_POST['sname'];
$sphone = $_POST['sphone'];

//新增
$statement = $pdo->prepare("INSERT INTO stores (sname, sphone) VALUES (?, ?)");
$statement->execute(array($sname, $sphone));
$num = $statement->rowCount();

//查詢店家資料
$statement = $pdo->prepare("SELECT id,sname,sphone FROM stores WHERE del=? ORDER BY id");
$statement->execute(array('n'));
$row = $statement->fetchAll(PDO::FETCH_ASSOC);

if($num <> 0){
	echo json_encode($row);
}else{
	echo 0;
}

?>