<?php
include("logincheck.php");
include("consql.php");

set_time_limit(600); //為避免上傳過慢，執行時間拉長至10分鐘

$sid = $_POST['sid'];
$bigpicname = $_POST['bigpicname']; //大圖名稱
$spicname = $_POST['spicname']; //大圖名稱

//刪除原先的檔案
unlink('fimgs/'.$bigpicname);
unlink('coverimgs/'.$spicname);

$savePath = "coverimgs/";  //封面圖片路徑
$bigPigSavePath = "fimgs/";  //大圖片路徑

$quality = 90; /* JPEG存檔品質 */
	
if($_FILES['file']['error'] != true) {
  if(empty($_FILES['file']['name'])) continue; /*假如欄位沒有上傳(空值)，則跳下一個*/
  
  $saveName = date('YmdHis').rand(10000,99999); /*封面圖檔名*/
  $bigsaveName = date('YmdHis').rand(10000,99999); /*大圖檔名*/
  $pic_tmp = $_FILES['file']['tmp_name'];

  /* 利用暫存檔取得圖檔資訊 */
  $getImg = getimagesize($_FILES['file']['tmp_name']); 
  
  $src_w = $getImg[0]; /* 取得圖片的寬 */
  $src_h = $getImg[1];  /*  取得圖片的高 */

  /***** 檔名設定 *****/
  /* $srcImg = 取得來源圖片(暫存檔)---類似PS的開啟圖片 */
  if ($getImg[2]==1){
  	$srcImg = imagecreatefromgif($pic_tmp);
  	$saveName = $saveName.'.gif';
    $bigsaveName = $bigsaveName.'.gif';
  }
	
  if ($getImg[2]==2){
  	$srcImg = imagecreatefromjpeg($pic_tmp);
  	$saveName = $saveName.'.jpg';
    $bigsaveName = $bigsaveName.'.jpg';
  }
	
  if ($getImg[2]==3){
  	$srcImg = imagecreatefrompng($pic_tmp);
  	$saveName = $saveName.'.png';
    $bigsaveName = $bigsaveName.'.png';
  }

  /******* 製作小圖，先將圖片切成1:1的比例 ******/
  if ($src_w > $src_h) {
    $new_w = $src_h;
    $new_h = $src_h;
  }elseif($src_h > $src_w) {
    $new_w = $src_w;
    $new_h = $src_w;
  }else{
    $new_w = $src_w;
    $new_h = $src_h;
  }

  /* 取得左上方原點座標 */
  $axis_x = ($src_w-$new_w)/2;
  $axis_y = ($src_h-$new_h)/2;

  /* 建立一張新的圖檔，尺寸為 $new_w, $new_h，做為複製出來的圖片用 */
  $copyImg = imagecreatetruecolor($new_w, $new_h);

  /* 從來源圖片中，複製出所需位置點及大小的圖片 */
  imagecopy($copyImg, $srcImg, 0, 0, $axis_x, $axis_y, $new_w, $new_h);

  /********* 再縮小為不超過 218x218 *********/
  if ($new_w > 218) {
    $s_weight = 218;
    $s_height = 218;
  }else{
    $s_weight = $new_w;
    $s_height = $new_h;
  }

  /* 建立一張新的圖檔，做為存檔圖片用 */
  $okImg = imagecreatetruecolor($s_weight, $s_height);

  /* 將指定的來源圖片，重新調整位置及大小 */
  imagecopyresampled($okImg, $copyImg, 0, 0, 0, 0, $s_weight, $s_height, $new_w, $new_h);

  /* 最後將完成的圖檔，依檔案類型進行存檔 */
  if ($getImg[2]==1) imagegif($okImg, $savePath.$saveName);
  if ($getImg[2]==2) imagejpeg($okImg, $savePath.$saveName, $quality);
  if ($getImg[2]==3) imagepng($okImg, $savePath.$saveName);
	  

  /******* 製作大圖，按原比例縮小 ******/
  /* 建立一張新的圖檔，尺寸用原圖大小，做為複製出來的圖片用 */
  $copyImg = imagecreatetruecolor($src_w, $src_h);

  /* 從來源圖片中，複製出所需位置點及大小的圖片 */
  imagecopy($copyImg, $srcImg, 0, 0, 0, 0, $src_w, $src_h);

  /* 長、寬縮小成不超過 938px */
  if($src_w >= $src_h) {
    //寬大於等於高
    if ($src_w >= 938) {
      $s_weight = 938;
      $s_height = ($src_h*938)/$src_w;
    }else{
      $s_weight = $src_w;
      $s_height = $src_h;
    }
  }else{
    //長大於寬
    if ($src_h >= 938) {
      $s_weight = ($src_w*938)/$src_h;
      $s_height = 938;
    }else{
      $s_weight = $src_w;
      $s_height = $src_h;
    }
  }

  /* 建立一張新的圖檔，做為存檔圖片用 */
  $okImg = imagecreatetruecolor($s_weight, $s_height);

  /* 將指定的來源圖片，重新調整位置及大小 */
  imagecopyresampled($okImg, $copyImg, 0, 0, 0, 0, $s_weight, $s_height, $src_w, $src_h);

  /* 最後將完成的圖檔，依檔案類型進行存檔 */
  if ($getImg[2]==1) imagegif($okImg, $bigPigSavePath.$bigsaveName);
  if ($getImg[2]==2) imagejpeg($okImg, $bigPigSavePath.$bigsaveName, $quality);
  if ($getImg[2]==3) imagepng($okImg, $bigPigSavePath.$bigsaveName);


	/********** 更新檔名到資料庫 **********/
	$statement = $pdo->prepare("UPDATE stores SET simg=?,cimg=? WHERE id=?");
  $statement->execute(array($bigsaveName, $saveName, $sid));
  $num = $statement->rowCount();

  if ($num <> 0) {
    echo $bigsaveName;
  }else{
    echo 'error';
  }

}

?>