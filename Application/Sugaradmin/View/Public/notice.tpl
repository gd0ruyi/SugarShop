<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="renderer" content="webkit">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SugarAdmin-<{$msgTitle}>
	</title>
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
		<div id="msg-box" class="panel">
			<div id="msg-title" class="panel-heading">提示标题</div>
			<div class="panel-body">
				<p id="msg-content">消息提示内容</p>
				<p id="msg-operate">
					<span id="msg-speed-info">
						<font id="msg-speed">N</font> 秒后自动
					</span>
					<a id="msg-jump" href="#" target="_top"> <font id="msg-jump-text"> [跳转] </font> </a>
				</p>
			</div>
		</div>
	</div>
	<script src="/Public/jquery/jquery-1.11.3.min.js"></script>
	<script src="/Public/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
	<script language="javascript" type="text/javascript">
		$(document).ready(function () {
			// 初始化处理
			var status = parseInt('<{$status}>');
			var panelCss = status ? 'panel-danger' : 'panel-success';
			var title = '<{$title}>';
			var msg = '<{$msg}>';
			var jumpUrl = '<{$jump}>';
			jumpUrl = jumpUrl == '' && status ? 'javascript:history.go(-1);' : jumpUrl;
			var jumpText = status ? '【返回】' : '【跳转】';
			var speed = parseInt('<{$speed}>');

			// 页面初始显示
			$('#msg-box').addClass(panelCss);
			$('#msg-title').html(title);
			$('#msg-content').html(msg);
			$('#msg-speed').html(speed);
			$('#msg-jump').attr('href', jumpUrl);
			$("#msg-jump-text").html(jumpText);

			// 定时处理
			if (speed) {
				// 每秒执行
				var spInterval = setInterval(function () {
					if (speed == 0) {
						$('#msg-jump-text').click();
						clearInterval(spInterval);						
					}
					// 更新等待时间数字
					$("#msg-speed").html(speed);
					speed--;					
				}, 1000);
			} else {
				$('#msg-speed-info').hide();
			}

		});
	</script>
</body>

</html>