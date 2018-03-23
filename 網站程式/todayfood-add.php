<?php
include("logincheck.php");
include("consql.php");

$user = $_SESSION['user']; //使用者
$myokfoods = $_POST['myokfoods'];
$today = date("Y-m-d");

//新增
foreach($myokfoods as $key=>$value){
	$fid = $myokfoods[$key];

	//查詢是否是今天是否新增過
	$statement = $pdo->prepare("SELECT id FROM Morders WHERE fid=? AND odate=? AND members=?");
	$statement->execute(array($fid, $today, $user));
	$row = $statement->fetch(PDO::FETCH_ASSOC);
	$num = $statement->rowCount();

	if($num == 0){
		$statement = $pdo->prepare("INSERT INTO Morders (fid, odate, members) VALUES (?, ?, ?)");
		$statement->execute(array($fid, $today, $user));
	}
}

echo 'ok';
?>