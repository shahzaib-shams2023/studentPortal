
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin Login for Web</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="/aptech_web/login/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/aptech_web/login/vendor/bootstrap//aptech_web/login/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0//aptech_web/login/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/aptech_web/login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="/aptech_web/login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/aptech_web/login/vendor/animsition//aptech_web/login/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/aptech_web/login/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="/aptech_web/login/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="/aptech_web/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="/aptech_web/login/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('/aptech_web/login/images/bg-01.jpg');">
			<div class="wrap-login100 p-t-30 p-b-50">
				<span class="login100-form-title p-b-41">
					Account Login
				</span>
				@if(session("error"))
				<p style="color:white;">{{session("error")}}</p>
				@endif
				<form  method="POST" class="login100-form p-b-33 p-t-5" action="/adminweblogin_">
                @csrf
					<div class="wrap-input100 validate-input">
						<input class="input100" type="email" name="email" required placeholder="User email">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" type="password" required name="password" placeholder="Password">
						<span class="focus-input100"></span>
					</div>

					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn" type="submit">
							Login
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="/aptech_web/login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="/aptech_web/login/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="/aptech_web/login/vendor/bootstrap/js/popper.js"></script>
	<script src="/aptech_web/login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="/aptech_web/login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="/aptech_web/login/vendor/daterangepicker/moment.min.js"></script>
	<script src="/aptech_web/login/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="/aptech_web/login/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="/aptech_web/login/js/main.js"></script>

</body>
</html>