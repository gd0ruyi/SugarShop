<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SugarAdmin-平台管理</title>
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet">
<link href="/Public/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="/Public/Sugaradmin/css/style.css" rel="stylesheet">
</head>
<body>
<a name="top" class="sr-only"></a>
<!-- 主体 -->
<div class="main-body">
  <div class="container">
    <nav class="main-header navbar navbar-default"> 
      <!--小屏幕按钮-->
      <div class="container-fluid">
        <div class="navbar-header top-manu-button">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-tabs-bar" aria-expanded="false"><span class="glyphicon glyphicon-file"></span></button>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-manu-bar" aria-expanded="false"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-up-bar" aria-expanded="false"><span class="glyphicon glyphicon-user"></span></button>
          <!-- logo --> 
          <a class="navbar-brand logo" href="#">Logo</a> </div>
        
        <!-- 用户操作菜单 -->
        <div class="top-up-bar collapse navbar-collapse" id="top-up-bar">
          <ul class="nav nav-pills navbar-right">
            <li role="presentation"><a href="#"><span class="glyphicon glyphicon-user"></span></a></li>
            <li role="presentation"><a href="#"><span class="glyphicon glyphicon-envelope"></span></a></li>
            <li role="presentation"><a href="#"><span class="glyphicon glyphicon-off"></span></a></li>
            <!--<li><a href="#">Profile<span class="glyphicon glyphicon-remove"></span></a></li>--> 
            <!--<li><a href="#">Messages</a></li>-->
          </ul>
        </div>
      </div>
      
      <!-- 功能菜单 -->
      <div class="container-fluid">
        <div class="top-manu-bar collapse navbar-collapse" id="top-manu-bar">
          <ul class="nav nav-pills">
            <li role="presentation" class="active"><a href="#">首页</a></li>
            <li role="presentation"> <a href="/Sugaradmin/Manager/Index" ajax-target="manager-list">管理员管理</a> </li>
            <li class="dropdown" role="presentation"> <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"> 下拉菜单一 <span class="caret"></span> </a>
              <ul class="dropdown-menu">
                <li><a href="/test.php" ajax-target="login-index">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider" role="separator"></li>
                <li><a href="#">Separated link</a></li>
              </ul>
            </li>
            <li role="presentation"> <a href="#">菜单二</a> </li>
            <li class="dropdown" role="presentation"> <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"> 下拉菜单二 <span class="caret"></span> </a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider" role="separator"></li>
                <li><a href="#">Separated link</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
      
      <!-- 动态选项卡 -->
      <div class="container-fluid">
        <div class="top-tabs-bar collapse navbar-collapse" id="top-tabs-bar">
          <ul class="nav nav-pills" role="tablist" id="top-tabs-bar-list">
            <li role="presentation" class="active"><a href="#home" >Home</a></li>
            <li role="presentation" id="top-tabs-tab-tpl" class="hidden"><a href="#"> <span class="top-tab-name">Template tab</span> <span class="glyphicon glyphicon-remove hidden"></span></a></li>
          </ul>
        </div>
      </div>
    </nav>
    
    <!-- 选项卡内容填充 -->
    <div class="main-content container-fluid">
      <div class="tab-content" id="top-tabs-contents">        
        <div role="tabpanel" class="tab-pane fade active in" id="home">home</div>
        <div role="tabpanel" class="tab-pane fade" id="top-tabs-content-tpl">Template tabs contests</div>
      </div>
    </div>
    
    <!-- 无进度加载条 -->
    <div id="loading-waiting-tpl" class="hidden">
      <div class="loading-waiting">
        <p class="text-center h2"> 
        	<span class="glyphicon glyphicon-refresh"></span>
            <span>Loading : </span>
            <span><font class="second">0</font> <font>s</font></span> 
            <span class="hidden"><font class="percent">0</font> <font>%</font></span> 
        </p>
      </div>
    </div>
    
    <!--底部-->
    <footer class="main-footer">
      <p>Sugar后台管理系统</p>
      <p> Copyright <span class="glyphicon glyphicon-copyright-mark"></span> 2016 <a href="mailto:gd0ruyi@163.com">gd0ruyi@163.com</a> 版权所有</p>
    </footer>
    <!--置顶按钮-->
    <div id="target-top" class="container-fluid navbar-fixed-bottom text-right">
    	<a href="#top"><h2 class="glyphicon glyphicon-open"></h2></a>
    </div>
  </div>
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
<script src="/Public/Sugaradmin/js/tabs.js" language="javascript"></script>
</body>
</html>