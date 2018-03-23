<?php
include("logincheck.php");
$user = $_SESSION['user']; //使用者

?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>密碼修改</title>
	<link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/logout.js"></script>
</head>

<body>
<!-- nav -->
<?php include("header.php");?>

<!-- 主內容 -->
<div id="menuscon">
    <form name="editform" id="editform" class="editpwd">
        <p>
            <i class="fa fa-key green"></i> 新密碼 <input type="password" name="newpwd">
            <i class="fa fa-key blue"></i> 重覆密碼 <input type="password" name="repeatpwd">
            <input type="hidden" name="member" value="<?php echo $user;?>">
            <button type="button" id="editok"><i class="fa fa-pencil-square-o"></i> 確定修改</button>
        </p>
    </form>

    <p id="pwdmsg"><i class="fa fa-exclamation-circle red"></i> 請輸入新的密碼，二邊欄位要輸入相同的密碼喔!</p>
</div>

<!-- loading -->
<div class="blackcover">
    <i class="fa fa-spinner fa-spin loading-icon"></i>
</div>

<script src="js/pwdedit.js"></script>
</body>
</html>