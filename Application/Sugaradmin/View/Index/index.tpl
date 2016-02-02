<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SugarAdmin-平台管理</title>
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="/Public/Sugaradmin/css/style.css" rel="stylesheet">
</head>
<body class="">
<!--<header class="main-header navbar-fixed-top"> <span>这里是头部</span> </header>-->
<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu nav nav-pills nav-stacked">
      <li role="presentation"> <a href="/Sugaradmin/Manager/Index" link-target=".main-right .content" >管理员管理</a> </li>
      <li class="dropdown" role="presentation"> <a role="button" href="#"> 下拉菜单一 <span class="glyphicon glyphicon-chevron-up"></span> </a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider" role="separator"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </li>
      <li role="presentation"> <a href="#">菜单二</a> </li>
      <li class="dropdown" role="presentation"> <a role="button" href="#"> 下拉菜单二 <span class="glyphicon glyphicon-chevron-up"></span> </a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider" role="separator"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </li>
    </ul>
  </section>
</aside>
</div>
<div class="main-right content-wrapper">
  <div class="content">这里是加载内容</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="error_title">消息提示</h4>
      </div>
      <div class="modal-body" id="error_msg"> ... </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
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
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%;"> Loading(<font>1</font>%)... </div>
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
	// 加载弹出等待条
	$("[link-target]").on("click", function(){
		var herf = $(this).attr("href");
		var layout = $(this).attr("link-target");
		var laoding_interval = 0;
		var $i = 1;
		
		if(herf == "#"){
			return false;
		}
		
		$(document).ajaxStart(function(){
			/*$('#loading').modal({
				backdrop: 'static'
			});*/
			$('#loading').modal("show");
			laoding_interval = setInterval(function(){
				if($i > 99){
					//$i = 1;
					return false;
				}
				$('#loading .progress-bar').width( $i + "%");
				$('#loading .progress-bar font').text($i);
				$i++;
			}, 50);
		});
		
		$(document).ajaxSuccess(function(){
		   $('#loading .progress-bar').width("100%");
		   clearInterval(laoding_interval);
		   $('#loading').modal("hide");
		});
		$(document).ajaxError(function(event, XMLHttpRequest, ajaxOptions, thrownError) {
			$("#error_title").html("警告：请求失败！");
			$("#error_msg").html("Error(" +XMLHttpRequest.status+ "):" + XMLHttpRequest.readyState);
			$('#myModal').modal("show");
            $('#loading').modal("hide");
        });
		$(layout).load(herf);
		return false;
	});
	
	//自定义弹出菜单父级特效
	$(".sidebar-menu li.dropdown").children("a").on('click', function(){
		if($(this).parent().hasClass("open")){
			$(this).parent().removeClass("open");
			$(this).find(".glyphicon").removeClass("glyphicon-chevron-down");
			$(this).find(".glyphicon").addClass("glyphicon-chevron-up");
		}else{
			$(this).parent().addClass("open");
			$(this).find(".glyphicon").removeClass("glyphicon-chevron-up");
			$(this).find(".glyphicon").addClass("glyphicon-chevron-down");
		}
	});
	
	//当前点击显示颜色
	$(".sidebar-menu li a").on('click', function(){
		$(".sidebar-menu li a").removeClass("on");
		if(!$(this).parent().hasClass("dropdown")){
			$(this).addClass("on");
		}
	});
});
</script>
</body>
</html>