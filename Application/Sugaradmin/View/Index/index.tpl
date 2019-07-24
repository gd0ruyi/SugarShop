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
    <div class="container max-box">
      <nav class="main-header navbar navbar-default">
        <!--小屏幕按钮-->
        <div class="container-fluid">
          <div class="navbar-header top-manu-button">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-tabs-bar"
              aria-expanded="false"><span class="glyphicon glyphicon-file"></span></button>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-manu-bar"
              aria-expanded="false"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-up-bar"
              aria-expanded="false"><span class="glyphicon glyphicon-user"></span></button>
            <!-- logo -->
            <a class="navbar-brand logo" href="#">Logo</a> </div>

          <!-- 用户操作菜单 -->
          <div class="top-up-bar collapse navbar-collapse" id="top-up-bar">
            <ul class="nav nav-pills navbar-right">
              <li role="presentation"><a href="#"><span class="glyphicon glyphicon-user"></span></a></li>
              <li role="presentation"><a href="#"><span class="glyphicon glyphicon-envelope"></span></a></li>
              <li role="presentation"><a href="/Sugaradmin/Login/logout"><span class="glyphicon glyphicon-off"></span></a></li>
              <!--<li><a href="#">Profile<span class="glyphicon glyphicon-remove"></span></a></li>-->
              <!--<li><a href="#">Messages</a></li>-->
            </ul>
          </div>
        </div>

        <!-- 功能菜单 -->
        <div class="container-fluid">
          <div class="top-manu-bar collapse navbar-collapse" id="top-manu-bar">
            <ul class="nav nav-pills">
              <li role="presentation" class="active">
                <a href="/Sugaradmin/Index/home" sgtab-target="#home" id="default-home-page">首页</a>
              </li>
              <li role="presentation">
                <a href="/Sugaradmin/Manager/index" sgtab-target="#manager-list">管理员管理</a>
              </li>
              <li class="dropdown" role="presentation">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                  示例 <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="/Sugaradmin/Test/loadSmarty" sgtab-target="#test-loadSmarty">加载smarty模版示例</a>
                  </li>
                  <li>
                    <a href="/Sugaradmin/Test/loadAjax" sgtab-target="#test-loadAjax">加载Ajax动态表格示例</a>
                  </li>
                  <li>
                    <a href="/Sugaradmin/Test/loadProgress" sgtab-target="#login-index">进度条加载测试</a>
                  </li>
                  <!-- <li><a href="/test.php" sgtab-target="#login-index">百分比加载</a></li> -->
                  <li>
                    <a href="#">Something else here</a>
                  </li>
                  <li class="divider" role="separator"></li>
                  <li>
                    <a href="#">Separated link</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>

        <!-- 动态选项卡按钮 -->
        <div class="container-fluid">
          <div class="top-tabs-bar collapse navbar-collapse" id="top-tabs-bar">
            <ul class="nav nav-pills" role="tablist" id="top-tabs-bar-list">
              <li role="presentation" class="hidden" id="tabs-tab-tpl">
                <a href="#target" role="tab" id="target-tab" data-toggle="tab" aria-controls="target"
                  aria-expanded="false">
                  <span class="top-tab-name">Template tab</span>
                  <span class="glyphicon glyphicon-remove hidden"></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- 选项卡内容填充 -->
      <div class="main-content container-fluid">
        <div class="tab-content" id="top-tabs-contents">
          <div role="tabpanel" class="tab-pane fade" id="target" aria-labelledby="target-tab">Loading...</div>
        </div>
      </div>

      <!--底部-->
      <footer class="main-footer">
        <p>Sugar后台管理系统</p>
        <p> Copyright <span class="glyphicon glyphicon-copyright-mark"></span> 2016 <a
            href="mailto:gd0ruyi@163.com">gd0ruyi@163.com</a>
          版权所有</p>
      </footer>
      <!--置顶按钮-->
      <div id="target-top" class="container-fluid navbar-fixed-bottom text-right">
        <a href="#top">
          <h2 class="glyphicon glyphicon-open"></h2>
        </a>
      </div>
    </div>
  </div>

  <!-- ****************************** -->
  <!-- ***自定义控件js的模版html内容start*** -->
  <!-- ****************************** -->
  <div class="html-tpl">
    <!-- 简单读秒或百分比等待loading，植入 -->
    <div id="loading-waiting-tpl">
      <div class="loading-waiting alert alert-success" id="loading-waiting-inner">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="text-center h5">
          <div class="text-left h4">
            <span class="glyphicon glyphicon-time"></span>
            <span class="loading-title">Loading title</span>
          </div>
          <span class="glyphicon glyphicon-refresh animation"></span>
          <span>Loading : </span>
          <span>
            <font class="loading-second">0</font>
            <font>s</font>
          </span>
          <p class="loading-loaded-span">
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span>Loaded:</span>
            <span>
              <font class="loading-loaded">0</font>
              <font class="loading-loaded-unit">kb</font>
            </span>
            /
            <span>
              <font class="loading-total">0</font>
              <font class="loading-total-unit">kb</font>
            </span>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span>Link Speed:</span>
            <span>
              <font class="loading-kbs">0</font>
              <font>kb/s</font>
            </span>
          </p>
        </div>
        <div class="progress">
          <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0"
            aria-valuemax="100" style="min-width: 0%;">
            <span>
              <font class="loading-percent">0</font>
              <font>%</font>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!--分页模版-->
    <div class="html-tpl" id="pager-default-tpl">
      <ul class="pager" id="pagee-default-id">
        <li><a href="#" class="pager-first"><span class="glyphicon glyphicon-step-backward"></span></a></li>
        <li><a href="#" class="pager-pre"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
        <li>
          <div class="input-group"> <span class="input-group-addon"><b>P.</b></span>
            <input name="p" type="text" class="form-control page-now" placeholder="Page" value="1" />
            <span class="input-group-btn">
              <button class="btn btn-primary pager-go" type="submit">Go</button>
            </span> </div>
        </li>
        <li><a href="#" class="pager-next"><span class="glyphicon glyphicon-chevron-right"></span></a></li>
        <li><a href="#" class="pager-last"><span class="glyphicon glyphicon-step-forward"></a></li>
        <li>
          <div class="input-group">
            <span class="input-group-addon color-white small">共计 <span class="page-total">0</span> 页</span>
            <!-- <span class="input-group-addon color-white border-radius-none small">每页</span> -->
            <select name="pager_size" class="form-control small pager-size">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
        </li>
        <li>
          <span class="small">总计条数:<font class="pager-rows">0</font></span>
        </li>
      </ul>
    </div>

    <!-- 表格工具模版 -->
    <div class="html-tpl" id="table-toolbar-tpl-id">
      <div class="table-toolbar form-group form-group-sm btn-group btn-group-xs pull-right">
        <button type="button" class="btn btn-default" aria-label="Sort Multiply" title="联合排序"><span
            class="glyphicon glyphicon-magnet" aria-hidden="true"></span></button>
        <button type="button" class="btn btn-default" aria-label="Tbale Setting" title="表格设置"><span class="glyphicon glyphicon-cog"
            aria-hidden="true"></span></button>
      </div>
    </div>

  </div>
  <!-- ****************************** -->
  <!-- ***自定义控件js的模版html内容end*** -->
  <!-- ****************************** -->

  <!-- 弹窗容器 -->
  <div class="out-window">
    <!-- 空的弹窗容器 -->
    <div class="modal" id="task-dialog-tpl" tabindex="-1" role="dialog" aria-labelledby="dialog-tpl-title">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="dialog-tpl-title">
              <span class="glyphicon glyphicon-tasks"></span>
              Loading Tasks History
              (<font class="loading-tasks-count">0</font>)
            </h4>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>

    <!-- Modal消息提示弹窗 -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="error_title">消息提示</h4>
          </div>
          <div class="modal-body" id="error_msg">
            ...
            <button type="button" id="test-modal" class="btn btn-primary btn-lg" data-toggle="modal"
              data-target="#dialog-tpl">
              显示正在加载的任务(Loading Task)
            </button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <!--<button type="button" class="btn btn-primary">Save changes</button>-->
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- 弹窗容器结束 -->

  <script src="/Public/jquery/jquery-1.11.3.min.js"></script>
  <script src="/Public/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
  <script language="javascript" type="text/javascript" src="/Public/Sugaradmin/js/common.js"></script>
  <script language="javascript" type="text/javascript" src="/Public/Sugaradmin/js/timekeeper.js"></script>
  <script src="/Public/Sugaradmin/js/tabs.js" language="javascript"></script>
  <script language="javascript" type="text/javascript" src="/Public/Sugaradmin/js/tables.js"></script>
  <script language"javascript" type="text/javascript">
    // 加载完成后执行
    $(document).ready(function () {
      // 使用dialog方式加载loading
      SugarTabs.loading_waiting_style = 'dialog';
      // 运行自定义Tabs控件
      SugarTabs.run();
      // 默认加载点击首页
      $("#default-home-page").click();

      // 支持多开
      // 通过该方法来为每次弹出的模态框设置最新的zIndex值，从而使最新的modal显示在最前面
      $(document).on('show.bs.modal', '.modal', function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function () {
          $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
      });
    });
  </script>
</body>

</html>