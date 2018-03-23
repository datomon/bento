<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>帳號登入</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bubbly-bg.js"></script>
</head>
<body>
<form class="form-inline" name="logform">
	<div class="logdiv">
		<h4><i class="fa fa-user-secret fa-lg"></i> 請輸入帳號密碼</h4>
		
		<div class="login-inputs">
			<p>
				<label for="regname">帳號：</label>
				<input type="text" id="regname" name="regname">
			</p>

			<p>
				<label for="regpwd">密碼：</label>
				<input type="password"id="regpwd" name="regpwd">
			</p>
		</div>
		<p class="log-error"><i class="fa fa-close fa-lg"></i> 帳號或密碼錯誤，請重新輸入!</p>
		<p class="logbtn"><button type="button">
			<i class="fa fa-check fa-lg"></i> 確定登入</button>
		</p>
	</div>
</form>

<!-- loading -->
<div class="blackcover">
	<i class="fa fa-spinner fa-spin loading-icon"></i>
</div>
<script>
bubbly({
    colorStart: "#fff4e6",
    colorStop: "#ffe9e4",
    blur: 1,
    compose: "source-over",
    bubbleFunc: () => `hsla(${Math.random() * 50}, 100%, 50%, .3)`
});
</script>
<script src="js/login.js"></script>
</body>
</html>