<?php
session_start();
include("consql.php");

$rName = $_POST['regname'];
$rPwd = $_POST['regpwd'];

$sql = $pdo->prepare("SELECT regPwd FROM logadmin WHERE regName=?");
$sql->execute(array($rName));
$row = $sql->fetch(PDO::FETCH_ASSOC);
$num = $sql->rowCount();

if ($num <> 0){
	if($row['regPwd'] === $rPwd){
		
			$_SESSION['admin'] = 'master'; //可登入後台的身份
			$_SESSION['user'] = $rName; //使用者名稱

			echo 'ok';
			exit;

	}else{
		echo 'error';
	}

}else{
	echo 'error';
}
?>