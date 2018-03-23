<?php
require("sql_setting.php");

try {
  $pdo = new PDO(sprintf("mysql:host=%s;dbname=%s;port=%s;charset=%s",
		  $setting['host'],
		  $setting['name'],
		  $setting['port'],
		  $setting['charset']
	  ),
	  $setting['username'],
	  $setting['password']
  );
} catch (PDOException $e) {
	echo "Database connection failed";
	exit;
}
?>