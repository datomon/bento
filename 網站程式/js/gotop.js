$(function () {
	//回頂點
	var gotopbtn = $('img.gotop');

	$(window).scroll(function(){
	    var scrollVal = $(this).scrollTop();
    	if(scrollVal > 250){
    		gotopbtn.css('display','block');
		}else{
			gotopbtn.css('display','none');
		}
	});

	$('img.gotop').click(function(){
		jQuery("html,body").animate({scrollTop:0},400);
	});
});