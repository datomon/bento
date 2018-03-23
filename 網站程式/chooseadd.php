<?php
include("logincheck.php");
include("consql.php");

$sid = $_POST['sid'];
$today = date("Y-m-d");

//查詢是否是今天初次決定
$statement = $pdo->prepare("SELECT id FROM Sorders WHERE odate =?");
$statement->execute(array($today));
$num = $statement->rowCount();

if($num <> 0){
	//有選過，做修改
	$statement = $pdo->prepare("UPDATE Sorders SET sid=? WHERE odate=?");
	$statement->execute(array($sid, $today));

	//清空舊的訂單
	$statement = $pdo->prepare("DELETE FROM Morders");
	$statement->execute(array());

	echo 'ok';
}else{
	//沒選過，做新增
	$statement = $pdo->prepare("INSERT INTO Sorders (odate, sid) VALUES (?, ?)");
	$statement->execute(array($today, $sid));
	$add_num = $statement->rowCount();

	//清空舊的訂單
	$statement = $pdo->prepare("DELETE FROM Morders");
	$statement->execute(array());
	
	echo ($add_num <> 0)? 'ok':'error';
}

?>