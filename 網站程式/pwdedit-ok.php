<?php
include("logincheck.php");
include("consql.php");

$member = $_POST["member"];
$newpwd = $_POST["newpwd"];

//更新資料
$statement = $pdo->prepare("UPDATE logadmin SET regPwd=? WHERE regName=?");
$statement->execute(array($newpwd, $member));

echo 'ok';

?>