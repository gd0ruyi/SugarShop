<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SugarAdmin-平台管理</title>
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet">
<link href="/Public/Sugaradmin/css/style.css" rel="stylesheet">
</head>
<body>
<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-navbar" aria-controls="bs-navbar" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="../" class="navbar-brand">Bootstrap</a>
    </div>
    <nav id="bs-navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li>
          <a href="../getting-started/" onclick="_hmt.push(['_trackEvent', 'docv3-navbar', 'click', 'V3导航-起步'])">起步</a>
        </li>
        <li class="active">
          <a href="../css/" onclick="_hmt.push(['_trackEvent', 'docv3-navbar', 'click', 'V3导航-全局CSS样式'])">全局 CSS 样式</a>
        </li>
        <li>
          <a href="../components/" onclick="_hmt.push(['_trackEvent', 'docv3-navbar', 'click', 'V3导航-组件'])">组件</a>
        </li>
        <li>
          <a href="../javascript/" onclick="_hmt.push(['_trackEvent', 'docv3-navbar', 'click', 'V3导航-JavaScript插件'])">JavaScript 插件</a>
        </li>
        <li>
          <a href="../customize/" onclick="_hmt.push(['_trackEvent', 'docv3-navbar', 'click', 'V3导航-定制'])">定制</a>
        </li>
        <li><a href="http://expo.bootcss.com" onclick="_hmt.push(['_trackEvent', 'docv3-navbar', 'click', 'V3导航-网站实例'])" target="_blank">网站实例</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="http://www.bootcss.com/" onclick="_hmt.push(['_trackEvent', 'docv3-navbar', 'click', 'V3导航-Bootstrap中文网'])" target="_blank">Bootstrap中文网</a></li>
      </ul>
    </nav>
  </div>
</header>
<!-- 头部 -->
<header class="main-header navbar-fixed-top bs-docs-nav">
	<div class="container">
        <div class="logo">Logo</div>
        <div class="header-nav">
            <ul nowrap id="top-tabs" class="nav nav-pills">
              <li role="presentation" class="active"><a href="#">Home</a></li><li role="presentation" class="active"><a href="#">Home</a></li><li role="presentation" class="active"><a href="#">Home</a></li><li role="presentation" class="active"><a href="#">Home</a></li><li role="presentation" class="active"><a href="#">Home</a></li><li role="presentation" class="active"><a href="#">Home</a></li>
              <li role="presentation"><a href="#">Profile<span class="glyphicon glyphicon-remove"></span></a></li>
              <li role="presentation"><a href="#">Messages</a></li>
            </ul>
        </div>
    </div>
</header>
<!--左侧菜单-->
<aside class="main-sidebar navbar-fixed-top">
  <section class="sidebar">
    <ul class="sidebar-menu nav nav-pills nav-stacked">
      <li role="presentation"> <a href="/Sugaradmin/Manager/Index" link-target=".main-right .content" >管理员管理</a> </li>
      <li role="presentation"> <a href="/Sugaradmin/Manager/Index" link-target=".main-right .content" >管理员管理|管理员管理管理员管理管理员管理</a> </li>
      <li class="dropdown" role="presentation"> <a role="button" href="#"> 下拉菜单一 <span class="glyphicon glyphicon-plus"></span> </a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider" role="separator"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </li>
      <li role="presentation"> <a href="#">菜单二</a> </li>
      <li class="dropdown" role="presentation"> <a role="button" href="#"> 下拉菜单二 <span class="glyphicon glyphicon-plus"></span> </a>
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
<!--右侧内容-->
<div id="top-tabs-contents" class="main-right content-wrapper">
  <div class="content">这里是加载内容</div>
</div>
<!--底部-->
<footer class="main-footer">
	<p>Sugar后台管理系统</p>
	<p> Copyright <span class="glyphicon glyphicon-copyright-mark"></span> 2016 <a href="mailto:gd0ruyi@163.com">gd0ruyi@163.com</a> 版权所有</p>
</footer>

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
	//　菜单构建
	var SugarManu = {
		// 选项卡的ID
		tabs_id : 'top-tabs',
		// 选项卡自增索引
		tab_index : 0,
		// 选项卡历史路径
		history_url : [],
		// 选项卡标题长度
		title_length : 12,
		// 选项卡标题缩写后省略符号
		title_sort_suff : '...',
		// 保存历史访问url数组
		saveHistoryUrl : function(url){
			for(var i=0;i<this.history_url.length;i++){
				if(this.history_url[i] == url){
					return false;
				}
			}
			return this.history_url.push(url);
		},
		//　删除历史访问url
		removeHistoryUrl : function(url){
			for(var i=1;i<=this.history_url.length;i++){
				if(this.history_url[i] == url){
					return this.history_url.splice(i,1);
				}
			}
			return true;
		},
		// 创建顶部导航tab选项卡
		addTabs : function(title, href, close_btn){
			// 初始化
			var tab_str = '';
			var content_str = '';
			var sort_title = '';
			href = href ? href : '#';
			close_btn = close_btn ? close_btn : true;
			
			sort_title = title.length > SugarManu.title_length ? title.substring(0, SugarManu.title_length) + SugarManu.title_sort_suff : title;
			
			tab_str = '<li id="' + SugarManu.tabs_id + '-tab-' + SugarManu.tab_index + '" role="presentation"><a href="' + href + '" title="' + title + '">' + sort_title + '</a>';
			tab_str += close_btn ? '<span class="glyphicon glyphicon-remove"></span>' : '';
			tab_str += '</li>';
			
			content_str = '<div id="' + SugarManu.tabs_id + '-content-' + SugarManu.tab_index + '" class="content">这里是加载内容</div>';
			
			$("#"+SugarManu.tabs_id).append(tab_str);
			$("#"+SugarManu.tabs_id + "-contents").append(content_str);
		},
		// 指向指定的选项卡（参数可为当前对象或者ID）
		pointTabs : function(tab_id){
			var tab_obj = {};
			tab_obj = typeof(tab_id) == 'undefined' && typeof(tab_id) == 'object' ? tab_id : $("#"+tab_id);
			tab_obj
		}
	};
	
	//自定义弹出菜单父级特效
	$(".sidebar-menu li.dropdown").children("a").on('click', function(){
		if($(this).parent().hasClass("open")){
			$(this).parent().removeClass("open");
			$(this).find(".glyphicon").removeClass("glyphicon-minus");
			$(this).find(".glyphicon").addClass("glyphicon-plus");
		}else{
			$(this).parent().addClass("open");
			$(this).find(".glyphicon").removeClass("glyphicon-plus");
			$(this).find(".glyphicon").addClass("glyphicon-minus");
		}
	});
	
	//菜单栏当前点击显示颜色
	$(".sidebar-menu li a").on('click', function(){
		$(".sidebar-menu li a").removeClass("on");
		if(!$(this).parent().hasClass("dropdown")){
			$(this).addClass("on");
		}
	});
	
	// 加载弹出等待条
	$("[link-target]").on("click", function(){
		var herf = $(this).attr("href");
		var layout = $(this).attr("link-target");
		var laoding_interval = 0;
		var $i = 1;
		
		if(herf == "#"){
			return false;
		}
		
		// 记录历史
		if(!SugarManu.saveHistoryUrl(herf)){
			return false;
		};
		SugarManu.addTabs($(this).text());
		
		// 全局调用ajax开始时处理
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
		// 全局调用ajax成功时处理
		$(document).ajaxSuccess(function(){
		   $('#loading .progress-bar').width("100%");
		   clearInterval(laoding_interval);
		   $('#loading').modal("hide");
		});
		//  全局调用ajax错误时处理
		$(document).ajaxError(function(event, XMLHttpRequest, ajaxOptions, thrownError) {
			$("#error_title").html("警告：请求失败！");
			$("#error_msg").html("Error(" +XMLHttpRequest.status+ "):" + thrownError);
			$('#myModal').modal("show");
            $('#loading').modal("hide");
        });
		$(layout).load(herf);
		return false;
	});
});
</script>
</body>
</html>