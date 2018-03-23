<?php
include("logincheck.php");
include("consql.php");
header('Content-Type: application/json; charset=utf-8');

//查詢店家資料
$statement = $pdo->prepare("SELECT id,sname,simg,cimg FROM stores WHERE del=? ORDER BY id");
$statement->execute(array('n'));
$row = $statement->fetchAll(PDO::FETCH_ASSOC);
$num = $statement->rowCount();

if($num <> 0){
	//將店家資料加入陣列
	foreach ($row as $key => $value) {
		$data[$key]['id'] = $row[$key]['id'];
		$data[$key]['sname'] = $row[$key]['sname'];
		$data[$key]['simg'] = $row[$key]['simg'];
		$data[$key]['cimg'] = $row[$key]['cimg'];

		//查詢便當資料
		$statement = $pdo->prepare("SELECT fname,fprice FROM foods WHERE sid=? ORDER BY id");
		$statement->execute(array($row[$key]['id']));
		$frow = $statement->fetchAll(PDO::FETCH_ASSOC);
		$fnum = $statement->rowCount();

		if($fnum <> 0){
			foreach ($frow as $fkey => $fvalue) {
				$data[$key]['foods'][$fkey]['fname'] = $frow[$fkey]['fname'];
				$data[$key]['foods'][$fkey]['fprice'] = $frow[$fkey]['fprice'];
			}
		}else{
			$data[$key]['foods'] = 0;
		}
	}

	echo json_encode($data);
}else{
	echo 0;
}

?>