<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="zh">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title>SugarAdmin | Login</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="/Public/sugaradmin/css/bootstrap.min.css" rel="stylesheet"
	type="text/css" />
<link href="/Public/sugaradmin/css/bootstrap-responsive.min.css"
	rel="stylesheet" type="text/css" />
<link href="/Public/sugaradmin/css/font-awesome.min.css"
	rel="stylesheet" type="text/css" />
<link href="/Public/sugaradmin/css/style-metro.css" rel="stylesheet"
	type="text/css" />
<link href="/Public/sugaradmin/css/style.css" rel="stylesheet"
	type="text/css" />
<link href="/Public/sugaradmin/css/style-responsive.css"
	rel="stylesheet" type="text/css" />
<link href="/Public/sugaradmin/css/default.css" rel="stylesheet"
	type="text/css" id="style_color" />
<link href="/Public/sugaradmin/css/uniform.default.css" rel="stylesheet"
	type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="/Public/sugaradmin/css/login.css" rel="stylesheet"
	type="text/css" />
<!-- END PAGE LEVEL STYLES -->
<link rel="shortcut icon" href="/Public/sugaradmin/image/favicon.ico?new" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
	<!-- BEGIN LOGO -->
	<div class="logo">
		<img src="/Public/sugaradmin/image/logo-big.png" alt="" />
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
		<form class="form-vertical login-form" method="post" action="<{U url='/Sugaradmin/Login/login'}>">
			<h3 class="form-title"><font color="#FF80C0">Sugar</font><font color="#393">Shop</font>后台管理系统</h3>
			<div class="alert alert-error hide">
				<button class="close" data-dismiss="alert"></button>
				<span>请输入您的密码</span>
			</div>
			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">账户</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-user"></i>
						<input name="username" class="m-wrap placeholder-no-fix" type="text"
							placeholder="请输入账户" name="username" />
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">密码</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input name="password" class="m-wrap placeholder-no-fix" type="password"
							placeholder="请输入密码" name="password" />
					</div>
				</div>
			</div>
			<div class="form-actions">
				<label class="checkbox">
					<input type="checkbox" name="remember" value="1" />
					保持登录
				</label>
				<button type="submit" class="btn purple pull-right">
					登 录
					<i class="m-icon-swapright m-icon-white"></i>
				</button>
			</div>
		</form>
		<!-- END LOGIN FORM -->
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">2015 &copy; SugarShop</div>
	<!-- END COPYRIGHT -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->
	<script src="/Public/sugaradmin/js/jquery-1.10.1.min.js"
		type="text/javascript"></script>
	<script src="/Public/sugaradmin/js/jquery-migrate-1.2.1.min.js"
		type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="/Public/sugaradmin/js/jquery-ui-1.10.1.custom.min.js"
		type="text/javascript"></script>
	<script src="/Public/sugaradmin/js/bootstrap.min.js"
		type="text/javascript"></script>
	<!--[if lt IE 9]>
	<script src="/Public/sugaradmin/js/excanvas.min.js"></script>
	<script src="/Public/sugaradmin/js/respond.min.js"></script>  
	<![endif]-->
	<script src="/Public/sugaradmin/js/jquery.slimscroll.min.js"
		type="text/javascript"></script>
	<script src="/Public/sugaradmin/js/jquery.blockui.min.js"
		type="text/javascript"></script>
	<script src="/Public/sugaradmin/js/jquery.cookie.min.js"
		type="text/javascript"></script>
	<script src="/Public/sugaradmin/js/jquery.uniform.min.js"
		type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="/Public/sugaradmin/js/jquery.validate.min.js"
		type="text/javascript"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="/Public/sugaradmin/js/app.js" type="text/javascript"></script>
	<script src="/Public/sugaradmin/js/login.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL SCRIPTS -->
	<script>
		jQuery(document).ready(function() {
			App.init();
			Login.init();
		});
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>