<?php
include("logincheck.php");
include("consql.php");
header('Content-Type: application/json; charset=utf-8');

$today = date("Y-m-d");

//查詢今日所訂便當的人
$statement = $pdo->prepare("SELECT fid,members FROM Morders WHERE odate=?");
$statement->execute(array($today));
$row = $statement->fetchAll(PDO::FETCH_ASSOC);
$num = $statement->rowCount();

//查詢今日名稱、價格
$statement = $pdo->prepare("SELECT DISTINCT fid FROM Morders WHERE odate=?");
$statement->execute(array($today));
$frow = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($frow as $fkey=>$fval) {
	$fid = $frow[$fkey]['fid'];
	$statement = $pdo->prepare("SELECT fname,fprice FROM foods WHERE id=?");
	$statement->execute(array($fid));
	$food = $statement->fetch(PDO::FETCH_ASSOC);

	$data[$fkey]['fname'] = $food['fname'];
	$data[$fkey]['fprice'] = $food['fprice'];

	foreach ($row as $key=>$val) {
		if($row[$key]['fid'] == $fid) {
			$data[$fkey]['members'][] = $row[$key]['members'];
		}
	}

	sort($data[$fkey]['members']); //陣列重新排序
}

if($num <> 0){
	echo json_encode($data);
}else{
	echo 0;
}

?>