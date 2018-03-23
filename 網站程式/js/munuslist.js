$(function(){
	//新增資料
	$(document).on('click', '#addok', function(){
		if(addform.sname.value.trim() === ''){ //檢查是否為空
	        addform.sname.focus();
	        alert('請輸入店家名稱!');
	        return false;
	    }else if(addform.sphone.value.trim() === ''){
	        addform.sphone.focus();
	        alert('請輸入電話!');
	        return false;
	    }else{
	        var sname_val = addform.sname.value;
	        var sphone_val = addform.sphone.value;
	        var data ={
	        	sname:sname_val,
	        	sphone:sphone_val
	        };

	        $(this).attr("disabled", "disabled");
	        $('.blackcover').show();

			$.ajax({
				url: 'stores-add.php',
				cache: false,
				data: data,
				type:'post',
				dataType:'json',
				success: function(result){
					$('.blackcover').hide();
		        	$('#addok').removeAttr("disabled");

		        	if(result === 0){
		        		alert('伺服器新增失敗，請重新新增店家!');
		        	}else{
		        		$('tbody').html(viewdata(result));
		        		addform.reset();
		        		addform.sname.focus();
		        	}
				},
				error:function(xhr){ 
					console.log(xhr);
				}
			});
    	}
	});

	var actionID; //刪除or編輯時的資料id
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
			url: 'stores-del.php',
			cache: false,
			data: {delid:actionID},
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

		editform.sname.value = p_tr.find("td").eq(1).text();
		editform.sphone.value = p_tr.find("td").eq(2).text();
		$('#edit-msg').show();
	});

	$(document).on('click', '#edit-cancle', function(){ $('#edit-msg').hide(); });

	$(document).on('click', '#edit-ok', function(){
		if(editform.sname.value.trim() === ''){ //檢查是否為空
	        editform.sname.focus();
	        alert('請輸入店家名稱!');
	        return false;
	    }else if(editform.sphone.value.trim() === ''){
	        editform.sphone.focus();
	        alert('請輸入電話!');
	        return false;
	    }else{
	        var sname_val = editform.sname.value;
	        var sphone_val = editform.sphone.value;
	        var data ={
	        	editid:actionID,
	        	sname:sname_val,
	        	sphone:sphone_val
	        };

	        $(this).attr("disabled", "disabled");
	        $('#edit-msg').hide();
	        $('.blackcover').show();

			$.ajax({
				url: 'stores-edit.php',
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
		        		editform.reset();
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
	    text += '<td>'+ arr[key].sname +'</td>';
	    text += '<td>'+ arr[key].sphone +'</td>';
	    text += '<td><a href="menuitems.php?sid='+ arr[key].id +'"><i class="fa fa-file-text green"></i></a></td>';
	    text += '<td><a href="menupics.php?sid='+ arr[key].id +'"><i class="fa fa-file-image-o lightblack"></i></a></td>';
	    text += '<td><i class="fa fa-pencil-square-o fa-lg blue editbtn" data-id="'+ arr[key].id +'"></i>&nbsp;&nbsp;&nbsp;&nbsp;';
	    text += '<i class="fa fa-close fa-lg red delbtn" data-id="'+ arr[key].id +'"></i></td>';
	    text += '</tr>';

	    nums++;
	}

	return text;
}
