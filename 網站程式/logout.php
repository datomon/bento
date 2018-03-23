<?php
session_start();
if(!isset($_SESSION['admin']) && $_SESSION['admin'] != 'master') {
	header("location:login.php");
	exit;
}

unset($_SESSION['admin']);  //後台管理
unset($_SESSION['user']);   //使用者

echo 'ok';

?>