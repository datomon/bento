$(function(){
	//確定修改
	$(document).on('click', '#editok', function(){
		if(editform.newpwd.value.trim() === ''){
	        editform.newpwd.focus();
	        alert('請輸入新密碼!');
	        return false;
	    }else if(editform.repeatpwd.value.trim() === ''){
	        editform.repeatpwd.focus();
	        alert('請再次輸入新密碼!');
	        return false;
	    }else if(editform.repeatpwd.value !== editform.newpwd.value){
	        editform.repeatpwd.focus();
	        alert('二邊欄位密碼不相同，請輸入相同的密碼!');
	        return false;
	    }else{
	    	var member_val = editform.member.value;
	    	var newpwd_val = editform.newpwd.value;
	        var data ={
	        	member:member_val,
	        	newpwd:newpwd_val
	        };

	        $(this).attr("disabled", "disabled");
	        $('.blackcover').show();
	        $('#pwdmsg').html('<i class="fa fa-circle-o-notch fa-spin blue"></i> 修改中...');

			$.ajax({
				url: 'pwdedit-ok.php',
				cache: false,
				data: data,
				type:'post',
				dataType:'html',
				success: function(result){
					$('.blackcover').hide();
		        	$('#editok').removeAttr("disabled");

		        	if(result === 'ok'){
		        		$('#pwdmsg').html('<i class="fa fa-check green"></i> 密碼修改成功~');
		        		editform.reset();
		        	}else{
		        		alert('密碼修改失敗，請重新修改!');
		        	}
				},
				error:function(xhr){ 
					console.log(xhr);
				}
			});
    	}
	});
});