<?php
include("logincheck.php");
$user = $_SESSION['user']; //使用者
$today = date("Y-m-d");

include("consql.php");

//查詢學員今日所訂的便當
$statement = $pdo->prepare("SELECT id,fid FROM Morders WHERE members=? AND odate=?");
$statement->execute(array($user, $today));
$row = $statement->fetchAll(PDO::FETCH_ASSOC);
$num = $statement->rowCount();

if($num > 0) {
    //列出今日所訂的便當
    $fstatue .= '你今天訂了 ';
    foreach($row as $key=>$val) {
        $statement = $pdo->prepare("SELECT fname FROM foods WHERE id=?");
        $statement->execute(array($row[$key]['fid']));
        $frow = $statement->fetch(PDO::FETCH_ASSOC);
        $fnum = $statement->rowCount();

        $fstatue .= '<span class="green">'.$frow['fname'].'</span>';
        $fstatue .= ' <i class="fa fa-close fa-lg red tfd-del" data-id="'.$row[$key]['id'].'"></i>';
    }

}else{
    $fstatue='<span class="red">你今天還沒訂便當喔~</span>';
}

//查詢值日生今日訂哪家便當
$statement = $pdo->prepare("SELECT sid FROM Sorders WHERE odate=?");
$statement->execute(array($today));
$srow = $statement->fetch(PDO::FETCH_ASSOC);
$snum = $statement->rowCount();

if($snum > 0) {
    $statement = $pdo->prepare("SELECT sname,sphone,simg,cimg FROM stores WHERE id=? AND del=?");
    $statement->execute(array($srow['sid'], 'n'));
    $srow2 = $statement->fetch(PDO::FETCH_ASSOC);
    $snum2 = $statement->rowCount();

    if($snum2 > 0) {
        //列出今日決定的店家名稱、電話
        $storeInfo .= '<i class="fa fa-home"></i> '.$srow2['sname'].'&nbsp;&nbsp;&nbsp;&nbsp;';
        $storeInfo .= '<i class="fa fa-phone"></i> '.$srow2['sphone'].'&nbsp;&nbsp;&nbsp;&nbsp;';
        $storeInfo .= '<button type="button" class="fcount">';
        $storeInfo .= '<i class="fa fa-usd"></i>&nbsp;數量統計</button>&nbsp;&nbsp;&nbsp;&nbsp;';
        $storeInfo .= '<button type="button" class="openmenu">';
        $storeInfo .= '<i class="fa fa-hand-pointer-o"></i>&nbsp;更換菜單</button>';

        //列出該店家的菜單
        $todayItems = viewMenu($pdo, $srow['sid'], $srow2['sname'], $srow2['simg'], $srow2['cimg']);
    }else{
        $storeInfo .= '<span class="red">這家廠商資料已被刪除!</span>&nbsp;&nbsp;';
        $storeInfo .= '<button type="button" class="openmenu">';
        $storeInfo .= '<i class="fa fa-hand-pointer-o"></i>&nbsp;選擇菜單</button>';
    }

}else{
    $storeInfo .= '<span class="red">別急，值日生還沒選好菜單喔 </span>&nbsp;&nbsp;';
    $storeInfo .= '<button type="button" class="openmenu">';
    $storeInfo .= '<i class="fa fa-hand-pointer-o"></i>&nbsp;選擇菜單</button>';
}

//列出店家菜單
/*
$pdo：pdo物件、$sid：廠商id、$sname：廠商名稱
$simg：菜單大圖、$cimg：菜單封面小圖、$fid：學員已選的便當id
*/
function viewMenu($pdo, $sid, $sname, $simg, $cimg){
    //查詢便當資料
    $statement = $pdo->prepare("SELECT id,fname,fprice FROM foods WHERE sid=?");
    $statement->execute(array($sid));
    $frow = $statement->fetchAll(PDO::FETCH_ASSOC);
    $fnum = $statement->rowCount();

    //菜單上所有便當的名稱
    if($fnum <> 0){
        foreach ($frow as $fkey => $fvalue) {
            $items .= '<input type="checkbox" value="'.$frow[$fkey]['id'].'" name="myokfoods[]" id="f'.$frow[$fkey]['id'].'">';
            $items .= '<label for="f'.$frow[$fkey]['id'].'"><li class="fitems">';
            $items .= $frow[$fkey]['fname'].' / '.$frow[$fkey]['fprice'].'元</li></label>';
        }
    }

    //******* 生成廠商菜單區塊 *********
    $text = '';
    $text .= '<div id="menus">';
    //提示訊息
    $text .= '<div class="chosehelp">';
    $text .= '<i class="fa fa-hand-o-down"></i>&nbsp;請在下方的空白菜單中，點選想訂購的項目後送出!';
    $text .= '</div>';
    $text .= '<div id="menus-con">';
    //內容區
    $text .= '<div class="menus-items">';
    //左半部
    $text .= '<div class="items-left">';
    $text .= '<img src="'.(($cimg === '') ? 'image/nopics.jpg':'coverimgs/'.$cimg).'" data-bigimg="'.$simg.'">';
    $text .= '<p><i class="fa fa-home"></i> '.$sname.'</p>';
    $text .= '<button type="button" class="foodok">';
    $text .= '<i class="fa fa-arrow-up"></i> 送出訂單</button></div>';
    //右半部
    $text .= '<div class="items-right"><ul><form name="choosefoodform" id="choosefoodform">';
    $text .= $items;
    $text .= '</ul></form></div></div></div></div></div>';
    return $text;
}

?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>訂便當喔</title>
	<link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/logout.js"></script>
</head>

<body>
<!-- nav -->
<?php include("header.php");?>

<div id="container">
    <!-- 歡迎訊息 -->
    <P><i class="fa fa-commenting fa-lg green"></i> 嗨 ~ 
        <?php echo $user.'號，'.$fstatue;?></P>
    
    <!-- 廠商 -->
    <div id="today-store">
        <div id="store-title">今日便當店家</div>
        <div id="store-con"><?php echo $storeInfo;?></div>
    </div>

    <!-- 主內容 -->
    <div id="con"><?php echo $todayItems?></div>
</div>

<!-- 版權宣告 -->
<address id="copyright">Copyright © 2017 Chia Ming Ku. All rights reserved.</address>

<!-- loading -->
<div class="blackcover">
    <i class="fa fa-spinner fa-spin loading-icon"></i>
</div>

<!-- bigimg -->
<div class="bigimg">
    <div id="bigimg-con">
        <p class="red" id="imgoff"><i class="fa fa-close fa-lg"></i> 關閉圖片</p>
        <img src="">
    </div>
</div>

<!-- 選擇店家msg -->
<div class="chosemsg" id="store-msg">
    <div class="chosestore-con">
        <div>
            <i class="fa fa-exclamation-circle red"></i> 真的確定要選這家店嗎??
        </div>
        <p>
            <button type="button" class="msg-ok" id="chosefood-ok">
                <i class="fa fa-check"></i> 確定
            </button>
            <button type="button" class="msg-cancle" id="chosefood-cancle">
                <i class="fa fa-close fa-lg"></i> 取消
            </button>
        </p>
    </div>
</div>

<!-- 選擇便當msg -->
<div class="chosemsg" id="food-mgs">
    <div class="chosestore-con foods-msgcon">
        <div>
            <i class="fa fa-exclamation-circle red"></i> 
            真的確定好了嗎??
        </div>
        <p>
            <button type="button" class="msg-ok" id="todayfd-ok">
                <i class="fa fa-check"></i> 確定
            </button>
            <button type="button" class="msg-cancle" id="todayfd-cancle">
                <i class="fa fa-close fa-lg"></i> 取消
            </button>
        </p>
    </div>
</div>

<!-- 便當統計 -->
<div class="chosemsg" id="ftotal-msg">
    <div class="ftotal-con">
        <div id="foodtotal"></div>
        <div class="ftotal-btn">
            <button type="button" class="msg-cancle" id="ftotal-cancle">
                <i class="fa fa-close fa-lg"></i> 關閉
            </button>
        </div>
    </div>
</div>

<script src="js/index.js"></script>
</body>
</html>