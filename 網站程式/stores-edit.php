<?php
include("logincheck.php");
include("consql.php");
header('Content-Type: application/json; charset=utf-8');

$id = $_POST['editid'];
$sname = $_POST['sname'];
$sphone = $_POST['sphone'];

//更新
$statement = $pdo->prepare("UPDATE stores SET sname=?,sphone=? WHERE id=?");
$statement->execute(array($sname, $sphone, $id));

//查詢店家資料
$statement = $pdo->prepare("SELECT id,sname,sphone FROM stores WHERE del=? ORDER BY id");
$statement->execute(array('n'));
$row = $statement->fetchAll(PDO::FETCH_ASSOC);
$num = $statement->rowCount();

if($num <> 0){
	echo json_encode($row);
}else{
	echo 0;
}

?>