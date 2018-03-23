$(function(){
	//今日日期
	var nowDate = new Date();
	var nY = nowDate.getFullYear();
	var nm = (nowDate.getMonth() + 1);
	if (nm < 10) nm = '0' + nm;
	var nd = nowDate.getDate();
	var today = nY + '-' + nm + '-' + nd;

	var data = {};  //商品資料

	//進入網頁時，從後端select資料
	window.onload = function(){
		$('.loading').show(); //loading圖出現

		$.post( "orders-select.php", function(json) {
			$('.loading').hide();  //loading圖消失

			if(json === 0){
				$('.content').html('<p class="lead text-center text-danger">目前無資料!</p>');
			}else{
				$('.content').html(viewdata(json)).show();
			}
		});
	}

	//新增資料
	$(document).on('click', '#addok', function(){
		if(addform.odate.value.trim() === ''){
	        addform.odate.focus();
	        alert('請輸入日期');
	        return false;
	    }else if(addform.customer.value.trim() === ''){
	        addform.customer.focus();
	        alert('請輸入客戶名稱');
	        return false;
	    }else if(addform.product.value.trim() === ''){
	        addform.product.focus();
	        alert('請輸入產品名稱');
	        return false;
	    }else if(addform.pcount.value.trim() === ''){
	        addform.pcount.focus();
	        alert('請輸入加入數量');
	        return false;
	    }else{
	        var v_odate = addform.odate.value;
	        var v_area = addform.area.value;
	        var v_customer = addform.customer.value;
	        var v_product = addform.product.value;
	        var v_pcount = addform.pcount.value;
	        var v_price = addform.price.value;
	        var v_newname = addform.newname.value;
	        var v_newdata = addform.newdata.value;
	        var data ={
	        	odate:v_odate,
	        	area:v_area,
	        	customer:v_customer,
	        	product:v_product,
	        	pcount:v_pcount,
	        	price:v_price,
	        	newname:v_newname,
	        	newdata:v_newdata
	        };

	        $.post("orders-add.php", data, function(result){
				$('.content').empty();

				if(result === 0){
					$('.content').html('<p class="lead text-center text-danger">伺服器忙錄中，請稍後重新操作!</p>');
				}else{
					$('.content').html(viewdata(result)).show();
					$('#addModal').modal('hide');
					addform.reset();
					addform.odate.value = today;
				}
			});
    	}
	});

	//點擊分頁搜尋
	$(document).on('click', '.pagination > li', function(){
		$('.loading').show(); //loading圖出現
		data.pg = $(this).attr("data-pg"); //分頁加至商品的資料物件中，方便編輯時可以立即回到該頁
		$('.content').empty();

		$.post( "orders-select.php",{pg:data.pg} , function(json) {
			$('.loading').hide();  //loading圖消失

			if(json === 0){
				$('.content').html('<p class="lead text-center text-danger">目前無資料!</p>').fadeIn();
			}else{
				$('.content').html(viewdata(json)).show();
			}
		});
	});

	//想修改or刪除的該筆資料id
	var active_id;

	//呼叫修改視窗
	$(document).on('click', '.editbtn', function(){
		active_id = $(this).attr("data-id");
		var p_tr = $(this).parents("tr"); //該按鈕的tr

		editform.odate.value = p_tr.find("td").eq(0).text();
		editform.area.value = p_tr.find("td").eq(1).text();
		editform.customer.value = p_tr.find("td").eq(2).text();
		editform.product.value = p_tr.find("td").eq(3).text();
		editform.pcount.value = p_tr.find("td").eq(4).text();
		editform.price.value = p_tr.find("td").eq(5).text();
		editform.newname.value = p_tr.find("td").eq(7).text();
		editform.newdata.value = p_tr.find("td").eq(8).text();
        $('#editModal').modal('show');
	});

	//確定修改
	$(document).on('click', '#editok', function(){
		if(editform.odate.value.trim() === ''){
	        editform.odate.focus();
	        alert('請輸入日期');
	        return false;
	    }else if(editform.customer.value.trim() === ''){
	        editform.customer.focus();
	        alert('請輸入客戶名稱');
	        return false;
	    }else if(editform.product.value.trim() === ''){
	        editform.product.focus();
	        alert('請輸入產品名稱');
	        return false;
	    }else if(editform.pcount.value.trim() === ''){
	        editform.pcount.focus();
	        alert('請輸入加入數量');
	        return false;
	    }else{
	        var v_odate = editform.odate.value;
	        var v_area = editform.area.value;
	        var v_customer = editform.customer.value;
	        var v_product = editform.product.value;
	        var v_pcount = editform.pcount.value;
	        var v_price = editform.price.value;
	        var v_newname = editform.newname.value;
	        var v_newdata = editform.newdata.value;

	        //設定data資料
	        data.id = active_id;
	        data.odate = v_odate;
	        data.area = v_area;
	        data.customer = v_customer;
	        data.product = v_product;
	        data.pcount = v_pcount;
	        data.price = v_price;
	        data.newname = v_newname;
	        data.newdata = v_newdata;

	        $.post("orders-editok.php", data, function(result){
				$('.content').empty();

				if(result === 0){
					$('.content').html('<p class="lead text-center text-danger">伺服器忙錄中，請稍後重新操作!</p>');
				}else{
					$('.content').html(viewdata(result)).show();
					$('#editModal').modal('hide');
					editform.reset();
				}
			});
    	}
	});

	//刪除
	$(document).on('click', '.delbtn', function(){
		active_id = $(this).attr("data-id"); //將記錄編號指定
	});

	//確定刪除
	$(document).on('click', '#delok', function(){
		$.post("orders-delok.php", {delid:active_id}, function(result) {
			$('.content').empty();

			if(result === 0){
				$('.content').html('<p class="lead text-center text-danger">伺服器忙錄中，請稍後重新操作!</p>');
			}else{
				$('.content').html(viewdata(result)).show();
				$('#delModal').modal('hide');
			}
		});
	});
});

//生成列表
function viewdata(jsondata){
	var text = '';
	text += '<p><span class="text-success">總筆數：' + jsondata.num + '</span>';
	text += '&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp<span class="text-primary">總頁數：' + jsondata.totalPg + '</span>';
	text += '&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp<span class="text-danger">目前頁數：' + jsondata.nowPg + '</span></p>';
	text += '<div class="table-responsive">';
    text += '<table class="table table-bordered">';
	text += '<thead>';
	text += '<tr class="success">';
	text += '<th width="10%">建單日期</th>';
	text += '<th width="10%">地區</th>';
	text += '<th width="10%">客戶名稱</th>';
	text += '<th width="10%">產品名稱</th>';
	text += '<th width="8%">數量</th>';
	text += '<th width="10%">單價</th>';
	text += '<th width="10%">建立者</th>';
	text += '<th width="10%">新客戶<br>負責人姓名</th>';
	text += '<th width="14%">新客戶<br>電話與地址</th>';
	text += '<th width="8%">編輯</th>';
	text += '</tr>';
	text += '</thead>';
	text += '<tbody>';

	var arr = jsondata.data;
	for (var key in arr) {
		text += '<tr>';
		text += '<td>'+ ((arr[key].odate === '0000-00-00') ? '' : arr[key].odate) +'</td>';
	    text += '<td>'+ arr[key].area +'</td>';
	    text += '<td>'+ arr[key].customer +'</td>';
	    text += '<td>'+ arr[key].product +'</td>';
	    text += '<td>'+ arr[key].pcount +'</td>';
	    text += '<td>'+ arr[key].price +'</td>';
	    text += '<td>'+ arr[key].creatMan +'</td>';
	    text += '<td>'+ arr[key].newName +'</td>';
	    text += '<td>'+ arr[key].newData +'</td>';
	    text += '<td><button type="button" data-id="'+ arr[key].id +'" class="btn btn-primary btn-xs editbtn">編輯</button><br><br>';
	    text += '<button type="button" data-id="'+ arr[key].id +'" class="btn btn-danger btn-xs delbtn" data-toggle="modal" data-target="#delModal">刪除</button></td>';
	    text += '</tr>';
	}

	text += '</tbody></table></div>';

	//分頁(超過1頁才顯示頁碼)
	if(jsondata.totalPg > 1) {
		text += '<nav class="text-center pages"><ul class="pagination">';
		//第1頁、上一頁
		if(jsondata.nowPg > 1) {
			text += '<li data-pg="1"><a href="#" role="button" class="btn"><span class="glyphicon glyphicon-step-backward"></span></a></li>';
			text += '<li data-pg="' + (Number(jsondata.nowPg)-1) + '"><a href="#" role="button" class="btn"><span class="glyphicon glyphicon-backward"></span></a></li>';
		}else{
			text += '<li><a href="#" role="button" class="btn disabled"><span class="glyphicon glyphicon-step-backward"></span></a></li>';
			text += '<li><a href="#" role="button" class="btn disabled"><span class="glyphicon glyphicon-backward"></span></a></li>';
		}

		//數字頁碼
		var one_prow = 3; //每排4頁
    	var now_prow = Math.ceil(jsondata.nowPg / one_prow); //目前排數 = 目前頁數 / 每排幾頁
    	var all_prow = Math.ceil(jsondata.totalPg / one_prow); //總排數 = 總頁數 / 每排幾頁
		//for的i參數
		if(now_prow == 1) {
			var p_statr = 1;
		}else{
			var p_statr = ((now_prow - 1)*one_prow) + 1; //(目前排-1)*每排幾頁+1
		}
		//for的結束參數
		if(now_prow == all_prow) {
			var p_end = jsondata.totalPg;
		}else{
			var p_end = now_prow * one_prow; //目前排*每排幾頁
		}
		//for迴圈產頁碼
		for(var i = p_statr; i <= p_end; i++) {
			if(i == jsondata.nowPg) {
				text += '<li><a href="#" role="button" class="btn btn-primary active">'+ i + '</a></li>';
			}else{
				text += '<li data-pg="'+ i + '"><a href="#" role="button" class="btn">'+ i + '</a></li>';
			}
		}

		//最後一頁、下一頁
		if(jsondata.nowPg < jsondata.totalPg) {
			text += '<li data-pg="' + (Number(jsondata.nowPg)+1) + '"><a href="#" role="button" class="btn"><span class="glyphicon glyphicon-forward"></span></a></li>';
			text += '<li data-pg="' + jsondata.totalPg + '"><a href="#" role="button" class="btn"><span class="glyphicon glyphicon-step-forward"></span></a></li>';
		}else{
			text += '<li><a href="#" role="button" class="btn disabled"><span class="glyphicon glyphicon-forward"></span></a></li>';
			text += '<li><a href="#" role="button" class="btn disabled"><span class="glyphicon glyphicon-step-forward"></span></a></li>';
		}

		text += '</ul></nav>';
	}

	return text;
}