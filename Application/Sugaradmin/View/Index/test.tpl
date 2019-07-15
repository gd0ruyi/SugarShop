<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SugarAdmin-测试页面</title>
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="/Public/Sugaradmin/css/style.css" rel="stylesheet">
</head>
<body>
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal"> 测试弹窗 </button>
<a href="/test.php" class="btn btn-primary btn-lg" link-toggle="layout-load" link-target="#layout-right">测试加载内容</a>
<div class="panel panel-primary" style="margin-top:10px;">
  <div class="panel-heading">面板测试-AJAX处理</div>
  <div class="panel-body" id="layout-right"> 面板内容 </div>
  <div class="panel-footer">面板底部</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body"> ... </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!--框架加载条-->
<div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="panel loading-box">
        <div class="panel-body">
          <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%;"> Loading... </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/Public/jquery/jquery-1.11.3.min.js"></script> 
<script src="/Public/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script> 
<script language="javascript">
$(function(){
	$("[link-toggle='layout-load']").on("click", function(){
		var herf = $(this).attr("href");
		var layout = $(this).attr("link-target");
		var laoding_interval = 0;
		var $i = 1;
		
		$(document).ajaxStart(function(){
			$('#loading').modal({
				backdrop: 'static'
			});
			$('#loading').modal("show");
			laoding_interval = setInterval(function(){
				if($i > 99){
					//$i = 1;
					return false;
				}
				$('#loading .progress-bar').width( $i + "%");
				$i++;
			}, 50);
		});
			
		$(document).ajaxSuccess(function(){
		   $('#loading .progress-bar').width("100%");
		   clearInterval(laoding_interval);
		   $('#loading').modal('hide');
		});
		
		$(layout).load(herf);
		return false;
	});
});
</script>
</body>
</html>