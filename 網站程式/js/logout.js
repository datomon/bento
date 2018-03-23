$(function(){
	$(document).on('click', '#logout', function(){
		var host = location.hostname;
		$.get( "logout.php", function(data) {
			if(data === 'ok') location.href = 'http://' + host +'/login.php';
		});
	});
});