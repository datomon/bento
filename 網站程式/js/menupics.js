$(function(){
	//取得檔案資訊
	$('#mychosefile').change(function(event){
	    var picfile = event.target.files;  //取得檔案列表(陣列)

	    //判斷是否為圖片
        if (!picfile[0].type.match('image.*')) {
            alert('你只能使用圖片!');
            upfile.reset();
        }
	});

	//上傳檔案
	$(document).on('click', '#upbtn', function(){
		if(upfile.file.value === '') {
	        alert('請選擇檔案!');
	        return false;
		}else{
			var form = $('form')[0];
			var formData = new FormData(form);
			
			$('.blackcover').show();

			$.ajax({
				url: 'picupfile.php',
				data: formData,
				type:'post',
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){
					$('.blackcover').hide();

					if (result !== 'error') {
						$('#pic-con').html('<img src="fimgs/'+ result +'">');
						upfile.reset();
					}else{
						alert('圖片上傳失敗!');
					}
				},
				error:function(xhr){ 
					console.log(xhr);
				}
			});
		}
	});
});