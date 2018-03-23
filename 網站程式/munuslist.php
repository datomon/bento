<?php
include("logincheck.php");
include("consql.php");

//查詢店家
$statement = $pdo->prepare("SELECT id,sname,sphone FROM stores WHERE del=? ORDER BY id");
$statement->execute(array('n'));
$row = $statement->fetchAll(PDO::FETCH_ASSOC);
$num = $statement->rowCount();

?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>菜單管理</title>
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
    <form name="addform" id="addform">
        <p>
            <i class="fa fa-home"></i> 店家名稱 <input type="text" name="sname">
            <i class="fa fa-phone"></i> 電話 <input type="text" name="sphone">
            <button type="button" id="addok"><i class="fa fa-plus"></i> 新增</button>
        </p>
    </form>

    <div id="con">
        <table class="menus-table">
            <thead>
                <tr>
                    <th width="6%">項次</th>
                    <th width="30%">店家名稱</th>
                    <th width="18%">電話</th>
                    <th width="10%">菜單</th>
                    <th width="10%">圖片</th>
                    <th width="16%">編輯 / 刪除</th>
                </tr>
            </thead>

            <tbody>
            <?php if($num > 0) {
                $itemnum=1;
                foreach($row as $key=>$val) { ?>
                <tr>
                    <td><?php echo $itemnum;?></td>
                    <td><?php echo $row[$key]['sname'];?></td>
                    <td><?php echo $row[$key]['sphone'];?></td>
                    <td><a href="menuitems.php?sid=<?php echo $row[$key]['id'];?>"><i class="fa fa-file-text green"></i></a></td>
                    <td><a href="menupics.php?sid=<?php echo $row[$key]['id'];?>"><i class="fa fa-file-image-o lightblack"></i></a></td>
                    <td><i class="fa fa-pencil-square-o fa-lg blue editbtn" data-id="<?php echo $row[$key]['id'];?>"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="fa fa-close fa-lg red delbtn" data-id="<?php echo $row[$key]['id'];?>"></i></td>
                </tr>
            <?php $itemnum++;}
            }else{ ?>
                <tr><td class="red" colspan="6">目前無任何店家資料喔~</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- loading -->
<div class="blackcover">
    <i class="fa fa-spinner fa-spin loading-icon"></i>
</div>

<!-- 編輯 msg -->
<div class="chosemsg" id="edit-msg">
    <div class="chosestore-con storeedit-con">
        <div>
            <form name="editform" id="editform">
                <p><i class="fa fa-home"></i> 店家名稱 <input type="text" name="sname"></p>
                <p><i class="fa fa-phone" id="sphone-input"></i> 電話 <input type="text" name="sphone"></p>
            </form>
        </div>
        <p>
            <button type="button" class="msg-ok" id="edit-ok">
                <i class="fa fa-check"></i> 確定修改
            </button>
            <button type="button" class="msg-cancle" id="edit-cancle">
                <i class="fa fa-close fa-lg"></i> 取消
            </button>
        </p>
    </div>
</div>

<!-- 刪除 msg -->
<div class="chosemsg" id="del-msg">
    <div class="chosestore-con">
        <div>
            <i class="fa fa-exclamation-circle red"></i> 真的確定要<span class="red">刪除</span>這家店嗎??
        </div>
        <p>
            <button type="button" class="msg-ok" id="del-ok">
                <i class="fa fa-check"></i> 確定
            </button>
            <button type="button" class="msg-cancle" id="del-cancle">
                <i class="fa fa-close fa-lg"></i> 取消
            </button>
        </p>
    </div>
</div>

<script src="js/munuslist.js"></script>
<img src="image/gotop.png" class="gotop">
<script src="js/gotop.js"></script>
</body>
</html>