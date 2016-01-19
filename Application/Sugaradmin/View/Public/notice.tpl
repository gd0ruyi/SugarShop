<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SugarAdmin-<{$msgTitle}></title>
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="/Public/Sugaradmin/css/public.css" rel="stylesheet">
<!--[if lt IE 9]>
  <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div class="container notice-box">
  <div class="panel <{if $status eq 0}>panel-success<{else}>panel-danger<{/if}>">
    <div class="panel-heading"><{$msgTitle}></div>
    <div class="panel-body">
    	<p><{$message}></p>
        <p> <font id="wait_second"><{$waitSecond}></font> 秒后自动<a id="jump" href="<{$jumpUrl}>" target="_top">【返回】</a></p>
    </div>
  </div>
</div>
<script src="/Public/jquery/jquery-1.11.3.min.js"></script> 
<script src="/Public/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	var wait_second = parseInt($("#wait_second").text());
	var countdown = setInterval(jump, 1000);
	var jumpUrl = $("#jump").attr("href");
	function jump(){
		if(wait_second == 0){
			if(jumpUrl.indexOf("javascript:") == 0){
				var script = jumpUrl.split(":");
				try{
					eval(script[1]);
				}catch(e){
					alert(e);
				}
			}else{
				window.location = jumpUrl;
			}
			clearInterval(countdown);
			return true;
		}
		wait_second --;
		$("#wait_second").text(wait_second);
	}
});
</script>
</body>
</html>