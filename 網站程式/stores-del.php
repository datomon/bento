<?php
include("logincheck.php");
include("consql.php");
header('Content-Type: application/json; charset=utf-8');

$delid = $_POST['delid'];

//更新為已刪除狀態
$statement = $pdo->prepare("UPDATE stores SET del=? WHERE id=?");
$statement->execute(array('y', $delid));

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