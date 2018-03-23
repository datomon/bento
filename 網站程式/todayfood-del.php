<?php
include("logincheck.php");
include("consql.php");

$id = $_POST['id']; 

$statement = $pdo->prepare("DELETE FROM Morders WHERE id=?");
$statement->execute(array($id));
$num = $statement->rowCount();

if($num <> 0){
	echo 'ok';
}else{
	echo 0;
}
?>