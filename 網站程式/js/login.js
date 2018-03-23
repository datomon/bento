$(function(){
	//進網頁focus第一個欄位
	window.onload = function(){
		logform.regname.focus();
	}

	$(document).on('click', '.logbtn', function(){
		if(logform.regname.value.trim() === ''){ //檢查是否為空
	        logform.regname.focus();
	        alert('請輸入帳號！');
	        return false;
	    }else if(logform.regpwd.value.trim() === ''){
	        logform.regpwd.focus();
	        alert('請輸入密碼！');
	        return false;
	    }else{
	    	$('.blackcover').show(); //loading圖出現
	        var rname = logform.regname.value;
	        var rpwd = logform.regpwd.value;
	        var data = {
	        	regname:rname,
	        	regpwd:rpwd
	        }

			$.ajax({
				url: 'login2.php',
				cache: false,
				data: data,
				type:'post',
				dataType:'html',
				success: function(result){
					$('.blackcover').hide();

		        	if(result === 'ok'){
		        		location.href = 'index.php';
		        	}else{
		        		$('.log-error').show();
		        	}
				},
				error:function(xhr){ 
					console.log(xhr);
				}
			});
    	}
	});
});