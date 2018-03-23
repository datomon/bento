$(function(){
	//****** 切換頁 *****
	var mlist; //包住所有菜單的外框
	var maxlistHeight; //list總長度
	var lastTop; //最後一個菜單的top點
	var minitH = 0; //預設從0開始

	$(document).on('click', '.page-up', function(){
		if (minitH >= 0) {
			mlist.css('top', lastTop+'px');
			minitH = lastTop;
		}else{
			var newHeight = minitH+540; //計算數字
			minitH = newHeight; //指派至全域變數
			mlist.css('top', newHeight+'px');
		}
		
	});

	$(document).on('click', '.page-down', function(){
		if (minitH <= lastTop) {
			mlist.css('top', '0px');
			minitH = 0;
		}else{
			var newHeight = minitH-540;
			minitH = newHeight;
			mlist.css('top', newHeight+'px');
		}
	});

	//***** 開啟菜單 *****
	$(document).on('click', '.openmenu', function(){
		$(this).attr("disabled", "disabled"); //防重覆點擊
		$('.blackcover').show();

		$.ajax({
			url: 'openmenu.php',
			cache: false,
			type:'get',
			dataType:'json',
			success: function(json){console.log(json);
	        	$('.blackcover').hide();
			
				if(json === 0){
					$('#con').html('<p class="red">目前無任何一家菜單資料，請先到菜單管理新增資料!</p>');
				}else{
					$('#con').html(viewdata(json));
					$('.openmenu').removeAttr("disabled"); //解除防重覆

					//初始化切換頁的參數
					mlist = $('#menus-list');
					maxlistHeight = mlist.height();
					lastTop = -(maxlistHeight-540);
				}
			},
			error:function(xhr){ 
				console.log(xhr);
			}
		});

		$.get("openmenu.php", function(json) {


			$('.loading').hide();
		});
	});

	//****** 點大圖 ******
	$(document).on('click', '.items-left > img', function(){
		var bimgname = $(this).attr('data-bigimg');
		var imgURL = ((bimgname === '') ? 'image/nopic.jpg':'fimgs/'+bimgname);
		$('#bigimg-con > img').attr('src',imgURL);
		$('.bigimg').show();
	});

	$('#imgoff').click(function(){
		$('.bigimg').hide();
	});

	//****** 選擇店家 ******
	var chooseSid; //店家id

	$(document).on('click', '.chosebtn', function(){
		chooseSid = $(this).attr('data-sid');
		$('#store-msg').show();
	});

	$('#chosefood-cancle').click(function(){ $('#store-msg').hide(); });

	$(document).on('click', '#chosefood-ok', function(){
		$(this).attr("disabled", "disabled");
		$('#store-msg').hide();
		$('.blackcover').show();

		$.ajax({
			url: 'chooseadd.php',
			cache: false,
			data: {sid:chooseSid},
			type:'post',
			dataType:'html',
			success: function(result){
	        	if(result === 'ok'){
	        		location.href = 'index.php';
	        	}else{
	        		$('.blackcover').hide();
	        		$('#chosefood-ok').removeAttr("disabled"); //解除防重覆
	        		alert('伺服器新增失敗，請重新選擇菜單!');
	        	}
			},
			error:function(xhr){ 
				console.log(xhr);
			}
		});
	});

	//********* 選擇今日的便當 ***********
	$(document).on('click', '.foodok', function(){ $('#food-mgs').show(); });

	$('#todayfd-cancle').click(function(){ $('#food-mgs').hide(); });

	$(document).on('click', '#todayfd-ok', function(){
		$(this).attr("disabled", "disabled");
		$('#food-mgs').hide();
		$('.blackcover').show();

		$.ajax({
			url: 'todayfood-add.php',
			cache: false,
			data: $("#choosefoodform").serialize(),
			type:'post',
			dataType:'html',
			success: function(result){
	        	if(result === 'ok'){
	        		location.href = 'index.php';
	        	}else{
	        		$('#todayfd-ok').removeAttr("disabled"); //解除防重覆
	        		alert('伺服器新增失敗，請重新選擇菜單!');
	        	}
			},
			error:function(xhr){ 
				console.log(xhr);
			}
		});
	});
	
	/** 取消今日便當 **/
	$(document).on('click', '.tfd-del', function(){
		var delid = $(this).attr('data-id');
		$('.blackcover').show();

		$.ajax({
			url: 'todayfood-del.php',
			cache: false,
			data:{id:delid},
			type:'post',
			dataType:'html',
			success: function(result){
	        	if(result === 'ok'){
	        		location.href = 'index.php';
	        	}else{
	        		alert('伺服器刪除失敗，請重新刪除!');
	        	}
			},
			error:function(xhr){ 
				console.log(xhr);
			}
		});
	});

	/********** 便當統計 ********/
	$(document).on('click', '.fcount', function(){
		$(this).attr("disabled", "disabled");
		$('.blackcover').show();

		$.ajax({
			url: 'ftotal.php',
			cache: false,
			type:'get',
			dataType:'json',
			success: function(result){
				$('.fcount').removeAttr("disabled");
				$('.blackcover').hide();
				$('#ftotal-msg').show();
	        	if(result === 0){
					$('#foodtotal').html('<p class="red">今天沒有人訂便當喔!</p>');
				}else{
					$('#foodtotal').html(foodCount(result));
					$('#ftotal-msg').show();
				}
			},
			error:function(xhr){ 
				console.log(xhr);
			}
		});
	});

	$('#ftotal-cancle').click(function(){ $('#ftotal-msg').hide(); });
});

//生成各家菜單
function viewdata(data){
	var text = '';
	//******* 生成廠商菜單區塊 *********
	text += '<div id="menus">';
	//換頁
	text += '<div class="page-nav">';
	text += '<i class="fa fa-chevron-up page-up"></i>&nbsp;&nbsp;&nbsp;';
    text += '按上、下頁切換菜單&nbsp;&nbsp;&nbsp;';        
    text += '<i class="fa fa-chevron-down page-down"></i>';        
    text += '</div>';
    text += '<div id="menus-con"><div id="menus-list">';

    //各家菜單
	for (var key in data) {
		var sid = data[key].id; //廠商id
		var sname = data[key].sname; //廠商名
		var simg = data[key].simg; //菜單圖片
		var cimg = data[key].cimg; //封面小圖
		var arr = data[key].foods; //便當資料

        text += '<div class="menus-items">';
        //左半部
        text += '<div class="items-left">';
        text += '<img src="'+ ((cimg === '') ? 'image/nopics.jpg':'coverimgs/'+cimg) +'" data-bigimg="'+ simg +'">';
        text += '<p><i class="fa fa-home"></i> '+ sname +'</p>';
        text += '<button type="button" class="chosebtn" data-sid="'+ sid +'">';
        text += '<i class="fa fa-shopping-cart"></i> 決定叫這家</button></div>';
        //右半部
        text += '<div class="items-right"><ul>';
        for (var fkey in arr) {
        	text += '<li>'+ arr[fkey].fname +' / ' + arr[fkey].fprice +'元</li>';
        }
        text += '</ul></div></div>';
	}

	text += '</div></div></div>';
	return text;
}

//數量統計
function foodCount(data) {
	var text = '';
	var allmoney = 0;
	var allfoods = 0;

	text += '<table class="menus-table ftotal-table">';
	text += '<thead><tr>';
	text += '<th width="10%">項次</th>';
	text += '<th width="30%">品名</th>';
	text += '<th width="10%">單價</th>';
	text += '<th width="10%">數量</th>';
	text += '<th width="40%">訂購人</th>';
	text += '</tr></thead>';
	text += '<tbody>';

	var nums = 1;
	for (var key in data) {
		var orderCount = data[key].members.length;
		text += '<tr>';
		text += '<td>'+ nums +'</td>';
		text += '<td>'+ data[key].fname +'</td>';
		text += '<td>'+ data[key].fprice +'</td>';
		text += '<td>'+ orderCount +'</td>';
		text += '<td>'+ data[key].members.join('、') +'</td>';
		text += '</tr>';

		nums++;
		allmoney += data[key].fprice*orderCount;
		allfoods += orderCount;
	}
	text += '<tr align="right"><td colspan="5"><i class="fa fa-usd"></i>&nbsp;總金額：'+ allmoney +'、';
	text += '<i class="fa fa-bar-chart"></i>&nbsp;總數量：' + allfoods +'</td></tr>';
	text += '</tbody>';
	
	return text;
}

