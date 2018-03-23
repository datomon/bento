<?php
include("logincheck.php");
include("consql.php");

$sid = $_GET['sid'];

//查詢店家
$statement = $pdo->prepare("SELECT sname,simg,cimg FROM stores WHERE id=?");
$statement->execute(array($sid));
$srow = $statement->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>編輯圖片</title>
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
    <p><i class="fa fa-home"></i> 店家名稱：<span class="red"><?php echo $srow['sname'];?></span></p>
    <div id="upload">
        <form name="upfile" enctype="multipart/form-data">
            <input type="hidden" name="sid" value="<?php echo $sid;?>">
            <input type="hidden" name="bigpicname" value="<?php echo $srow['simg'];?>">
            <input type="hidden" name="spicname" value="<?php echo $srow['cimg'];?>">

            <p id="filechoose">
                <i class="fa fa-file-image-o"></i> 選擇你要上傳的圖片：
                <input type="file" name="file" id="mychosefile">
                <button type="button" class="blue" id="upbtn"><i class="fa fa-upload"></i> 上傳圖片</button>
            </p>
        </form>
    </div>

    <div id="pic-con">
        <?php if ($srow['simg'] != '') { ?>
            <img src="fimgs/<?php echo $srow['simg'];?>">
        <?php }else{ ?>
            <img src="image/nopic.jpg">
        <?php }?>
    </div>
</div>

<!-- loading -->
<div class="blackcover">
    <i class="fa fa-spinner fa-spin loading-icon"></i>
</div>

<script src="js/menupics.js"></script>
<img src="image/gotop.png" class="gotop">
<script src="js/gotop.js"></script>
</body>
</html>