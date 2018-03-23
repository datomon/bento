<?php
session_start();
if(!isset($_SESSION['admin']) && $_SESSION['admin'] != 'master') {
	header("location: login.php");
	exit;
}
?>