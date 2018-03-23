$(function(){
	//新增資料
	$(document).on('click', '#addok', function(){
		if(addform.fname.value.trim() === ''){ //檢查是否為空
	        addform.fname.focus();
	        alert('請輸入便當名稱!');
	        return false;
	    }else if(addform.fprice.value.trim() === ''){
	        addform.fprice.focus();
	        alert('請輸入價錢!');
	        return false;
	    }else if(!checknumber(addform.fprice.value)){
		    addform.fprice.focus();
			addform.fprice.value = ''; //清空價錢
	    }else{
	        $(this).attr("disabled", "disabled");
	        $('.blackcover').show();

			$.ajax({
				url: 'items-add.php',
				cache: false,
				data: $("#addform").serialize(),
				type:'post',
				dataType:'json',
				success: function(result){
					$('.blackcover').hide();
		        	$('#addok').removeAttr("disabled");

		        	if(result === 0){
		        		alert('伺服器新增失敗，請重新新增便當!');
		        	}else{
		        		$('tbody').html(viewdata(result));
		        		addform.reset();
		        		addform.fname.focus();
		        	}
				},
				error:function(xhr){ 
					console.log(xhr);
				}
			});
    	}
	});

	var actionID; //刪除or編輯時的資料id
	var foodid = addform.sid.value; //店家id
	//刪除資料
	$(document).on('click', '.delbtn', function(){
		actionID = $(this).attr('data-id');
		$('#del-msg').show();
	});

	$(document).on('click', '#del-cancle', function(){ $('#del-msg').hide(); });

	$(document).on('click', '#del-ok', function(){
		$('#del-msg').hide();
		$('.blackcover').show();

		$.ajax({
			url: 'items-del.php',
			cache: false,
			data: {delid:actionID, sid:foodid},
			type:'post',
			dataType:'json',
			success: function(result){
				$('.blackcover').hide();

	        	if(result === 0){
	        		window.location.reload();
	        	}else{
	        		$('tbody').html(viewdata(result));
	        	}
			},
			error:function(xhr){ 
				console.log(xhr);
			}
		});
	});

	//修改資料
	$(document).on('click', '.editbtn', function(){
		actionID = $(this).attr('data-id');
		var p_tr = $(this).parents("tr"); //該按鈕的tr

		editform.fname.value = p_tr.find("td").eq(1).text();
		editform.fprice.value = p_tr.find("td").eq(2).text();
		$('#edit-msg').show();
	});

	$(document).on('click', '#edit-cancle', function(){ $('#edit-msg').hide(); });

	$(document).on('click', '#edit-ok', function(){
		if(editform.fname.value.trim() === ''){ //檢查是否為空
	        editform.fname.focus();
	        alert('請輸入便當名稱!');
	        return false;
	    }else if(editform.fprice.value.trim() === ''){
	        editform.fprice.focus();
	        alert('請輸入價錢!');
	        return false;
	    }else if(!checknumber(editform.fprice.value)){
		    editform.fprice.focus();
			editform.fprice.value = ''; //清空價錢
	    }else{
	    	var fname_val = editform.fname.value;
	        var fprice_val = editform.fprice.value;
	        var data ={
	        	id:actionID,
	        	sid:foodid,
	        	fname:fname_val,
	        	fprice:fprice_val
	        };

	        $(this).attr("disabled", "disabled");
	        $('#edit-msg').hide();
	        $('.blackcover').show();

			$.ajax({
				url: 'items-edit.php',
				cache: false,
				data: data,
				type:'post',
				dataType:'json',
				success: function(result){
					$('.blackcover').hide();
		        	$('#edit-ok').removeAttr("disabled");

		        	if(result === 0){
		        		alert('伺服器修改失敗，請重新修改店家資料!');
		        	}else{
		        		$('tbody').html(viewdata(result));
		        	}
				},
				error:function(xhr){ 
					console.log(xhr);
				}
			});
    	}
	});
});

//生成資料
function viewdata(arr){
	var text = '';
	var nums = 1;
	for (var key in arr) {
		text += '<tr>';
		text += '<td>'+ nums +'</td>';
	    text += '<td>'+ arr[key].fname +'</td>';
	    text += '<td>'+ arr[key].fprice +'</td>';
	    text += '<td><i class="fa fa-pencil-square-o fa-lg blue editbtn" data-id="'+ arr[key].id +'"></i>&nbsp;&nbsp;&nbsp;&nbsp;';
	    text += '<i class="fa fa-close fa-lg red delbtn" data-id="'+ arr[key].id +'"></i></td>';
	    text += '</tr>';

	    nums++;
	}

	return text;
}

//驗證數字
function checknumber(data){
	var filter = /^\d+$/;
	if(filter.test(data)) return true;
	alert('價錢請全部輸入整數數字！');
	return false;
}
