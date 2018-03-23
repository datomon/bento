<?php
include("logincheck.php");
include("consql.php");
header('Content-Type: application/json; charset=utf-8');

$sid = $_POST['sid'];
$fname = $_POST['fname'];
$fprice = $_POST['fprice'];

//新增
$statement = $pdo->prepare("INSERT INTO foods (sid, fname, fprice) VALUES (?, ?, ?)");
$statement->execute(array($sid, $fname, $fprice));
$num = $statement->rowCount();

//查詢便當資料
$statement = $pdo->prepare("SELECT id,fname,fprice FROM foods WHERE sid=? ORDER BY id");
$statement->execute(array($sid));
$row = $statement->fetchAll(PDO::FETCH_ASSOC);
$num = $statement->rowCount();

if($num <> 0){
	echo json_encode($row);
}else{
	echo 0;
}

?>