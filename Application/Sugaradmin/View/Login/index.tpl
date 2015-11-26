<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SugarAdmin | Login</title>
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="/Public/Sugaradmin/css/login.css" rel="stylesheet">
<!--[if lt IE 9]>
  <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div class="container login-box">
  <div class="panel panel-primary">
    <div class="panel-heading">SugarShop管理登录</div>
    <div class="panel-body">
      <form class="form-horizontal row">
        <div class="form-group col-xs-12">
          <label for="usename" class="col-sm-2 control-label hidden-xs">用户名</label>
          <div class="col-sm-10 input-group">
            <span class="input-group-addon"><font class="glyphicon glyphicon-user"></font></span>
            <input type="text" class="form-control" id="username" name="usename" placeholder="请输入用户名">
          </div>
        </div>
        <div class="form-group col-xs-12">
          <label for="password" class="col-sm-2 control-label hidden-xs">密码</label>
          <div class="col-sm-10 input-group">
            <span class="input-group-addon"><font class="glyphicon glyphicon-lock"></font></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="请输入密码">
          </div>
        </div>
        <div class="form-group col-xs-12">
          <label for="verification" class="col-sm-2 control-label hidden-xs">验证码</label>
          <div class="input-group verify-box">
            <input type="text" class="form-control input-lg" id="verify" name="verify" placeholder="请输入验证码" />
            <span class="input-group-addon"><img id="verify-img" class="img-rounded" height="40" width="120" alt="点击刷新" title="点击刷新" src="<{U url='/Home/Verify/index' size=36}>" /></span>
          </div>
        </div>
        <div class="form-group text-center">
            <input type="submit" class="btn btn-lg btn-primary" name="submit" value="登 录" />
        </div>
      </form>
    </div>
  </div>
</div>
<script src="/Public/jquery/jquery-1.11.3.min.js"></script> 
<script src="/Public/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
	$('#verify-img').click(function(){
		var $src = $(this).attr('src');
		$(this).attr('src', $src + '?' + Math.random());
	});
})
</script>
</body>
</html>