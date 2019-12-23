<!--导航条-->
<ol class="breadcrumb">
  <li><a href="#">首页</a></li>
  <li class="active">用户管理</li>
</ol>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">用户列表</h3>
  </div>
  <div class="panel-body form-toolbar">
    <!-- 工具组 -->
    <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
      <div class="btn-group" role="group" aria-label="Setting groups">
        <button type="button" class="btn btn-primary" sugar-dialog="true" sugar-target-id="user-edit" sugar-url="/Sugaradmin/User/edit" sugar-data="" title="创建用户">
          <span class="glyphicon glyphicon-plus"></span>
        </button>
      </div>
      <!-- <div class="btn-group" role="group" aria-label="Second group">
          <button type="button" class="btn btn-default">5</button>
          <button type="button" class="btn btn-default">6</button>
          <button type="button" class="btn btn-default">7</button>
        </div>
        <div class="btn-group" role="group" aria-label="Third group">
          <button type="button" class="btn btn-default">8</button>
        </div> -->
    </div>
  </div>
  <div class="panel-body">
    <!-- 表单 -->
    <form class="form-inline" id="user-table-form">

      <div class="form-group form-group-sm">
        <div class="input-group">
          <div class="input-group-btn" sugar-selector="true">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <font>关键字</font> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#" value="name">姓名</a></li>
              <li><a href="#" value="email">邮箱</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#" value="all">关键字</a></li>
            </ul>
            <input type="hidden" name="keyword_type" value="">
          </div><!-- /btn-group -->
          <input type="text" class="form-control" id="user_keyword" name="user_keyword" placeholder="请输入关键字">
        </div>
      </div>

      <!-- <div class="form-group form-group-sm">
        <div class="btn-group" sugar-selector="true">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <font>全部类型</font> <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="#" value="0">管理员</a></li>
            <li><a href="#" value="1">普通用户</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#" value="all">全部类型</a></li>
          </ul>
          <input type="hidden" name="use_type" value="">
        </div>
      </div> -->

      <div class="form-group form-group-sm">
        <div class="btn-group" sugar-selector="true">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <font>全部状态</font> <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="#" value="0">启用</a></li>
            <li><a href="#" value="1">禁用</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#" value="all">全部状态</a></li>
          </ul>
          <input type="hidden" name="status" value="all">
        </div>
      </div>

      <div class="form-group form-group-sm">
        <button name="search" type="submit" class="btn btn-primary">搜 索</button>
      </div>

      <div class="table-responsive">
        <table id="user-table" title="管理员列表-表格内容" class="table table-striped table-bordered table-hover ajax-tables">
        </table>
      </div>
    </form>
  </div>
</div>
<script language="javascript" type="text/javascript">
  // 加载完成后执行
  $(document).ready(function () {
    // 创建下拉，使用标签属性为<element sugar-selector="true" ></element>
    // SugarCommons.createSelectInput();

    // 加载自定义插件
    SugarCommons.createPlugin();
    // 开启debug
    // SugarCommons.debug = true  
    // alert(SugarCommons.debug);

    // 执行创建表格
    var columns = {
      'user_id': {
        th: { title: 'ID', sort_icon: true, sort_default: 'desc', class: 'text-center', checkbox: true },
        td: { style: "color:red; text-align:center" }
      },
      'username': {
        th: { title: '帐号', sort_icon: true, class: 'text-center' },
      },
      // 'use_type': {
      //   th: { title: '帐号类型', sort_icon: true, class: 'text-center' },
      //   td: { template: "{use_type_name}", class: 'text-center' }
      // },
      'truename': {
        th: { title: '姓名', sort_icon: true, class: 'text-center' },
      },
      'email': {
        th: { title: '邮箱', sort_icon: true, class: 'text-center' },
      },
      'mobile': {
        th: { title: '电话', sort_icon: true, class: 'text-center' },
      },
      'add_time': {
        th: { title: '创建时间', class: 'text-center', sort_icon: true },
        td: { template: "{add_time_format}", class: 'text-center' }
      },
      'upd_time': {
        th: { title: '更新时间', class: 'text-center', sort_icon: true },
        td: { template: "{upd_time_format}", class: 'text-center' }
      },
      'las_time': {
        th: { title: '登录时间', class: 'text-center', sort_icon: true },
        td: { template: "{las_time_format}", class: 'text-center' }
      },
      'status': {
        th: { title: '状态', sort_icon: true, class: 'text-center' },
        // td: { template: "{status_name}", class: 'text-center' }
        // 特殊内容通过方法回调处理，当为禁用时显示红色，启用显示绿色
        td: {
          template: function (index, row) {
            var $btnHtml = $('<button type="button" class="btn btn-sm"><span class="glyphicon"></span></button>');
            var $title = '';
            var $msg = '';
            var $url = '/Sugaradmin/User/changeFieldValueByPK';
            var $data = { 'user_id': row['user_id'] };

            // 判断使用状态
            if (row['status'] == 0) {
              $btnHtml.attr('title', '点击禁用');
              $btnHtml.addClass('btn-success');
              $btnHtml.find('span').addClass('glyphicon-play');

              $title = '用户【' + row['username'] + '】将被禁用';
              $msg = '请您确认是否【禁用】ID为【' + row['user_id'] + '】，<br/>用户名为【' + row['username'] + '】的用户？';
              $data.status = 1;
            } else {
              $btnHtml.attr('title', '点击启用');
              $btnHtml.addClass('btn-warning');
              $btnHtml.find('span').addClass('glyphicon-pause');

              $title = '用户【' + row['username'] + '】将被启用';
              $msg = '请您确认是否【启用】ID为【' + row['user_id'] + '】，<br/>用户名为【' + row['username'] + '】的用户？';
              $data.status = 0;
            }

            // 点击事件
            $btnHtml.click(function (e) {
              // 调用弹出确认窗口
              SugarCommons.makeConfirm({
                title: $title,
                msg: $msg,
                url: $url,
                data: $data,
                complete: function (e) {
                  // 完成时刷新列表
                  $('#user-table-form [name="search"]').click();
                }
              });
            });

            // 返回按钮对象
            return $btnHtml;
          },
          class: 'text-center'
        }
      },
      'other': {
        th: { title: '描述说明', title_length: 12, class: 'text-center', display: false },
        td: { template: '{username} 用户的登录时间为： {las_time_format}' }
      },

      'operation': {
        th: { title: '操作', class: 'text-center' },
        td: {
          // 单元格样式居中
          class: 'text-center',

          // 按钮配置
          btnOptions: {

            // 自定义按钮，key需带CoustomBtn（大小写区分）并且为方法，其返回值为一个元素对象或是html
            'statusCustomBtn': function (index, row) {
              var $btnHtml = $('<button type="button" class="btn btn-sm"><span class="glyphicon"></span></button>');
              var $title = '';
              var $msg = '';
              var $url = '/Sugaradmin/User/changeFieldValueByPK';
              var $data = { 'user_id': row['user_id'] };

              // 判断使用状态
              if (row['status'] == 0) {
                $title = '禁用';
                $btnHtml.addClass('btn-warning');
                $btnHtml.find('span').addClass('glyphicon-pause');
                $data.status = 1;
              } else {
                $title = '启用';
                $btnHtml.addClass('btn-success');
                $btnHtml.find('span').addClass('glyphicon-play');
                $data.status = 0;
              }
              $btnHtml.attr('title', $title);

              $msg = '请您确认是否【' + $title + '】ID为【' + row['user_id'] + '】，<br/>用户名为【' + row['username'] + '】的用户？';
              $title = $title + '用户【' + row['username'] + '】';

              // 点击事件
              $btnHtml.click(function (e) {
                // 调用弹出确认窗口
                SugarCommons.makeConfirm({
                  title: $title,
                  msg: $msg,
                  url: $url,
                  data: $data,
                  complete: function (e) {
                    // 完成时刷新列表
                    $('#user-table-form [name="search"]').click();
                  }
                });
              });

              // 返回按钮对象
              return $btnHtml;
            },

            // 编辑按钮
            'edit': {
              title: '编辑',
              btnClass: 'btn-info btn-sm',
              btnIconClass: 'glyphicon-edit',
              // 所需表格字段中的数据信息
              data: ['user_id', 'username'],
              // 点击事件
              btnClick: function (e, optData) {
                var $url = '/Sugaradmin/User/edit';
                var $title = '编辑用户【' + optData.username + '】';
                // 弹出编辑窗口
                SugarCommons.showEditDialogByAjax('#user-edit', $title, $url, optData);
              }
            },

            // 删除按钮
            'delete': {
              title: '删除',
              btnClass: 'btn-danger btn-sm',
              btnIconClass: 'glyphicon-trash',
              // 所需表格字段中的数据信息
              data: ['user_id', 'username'],
              // 点击事件
              btnClick: function (e, optData) {
                var $url = '/Sugaradmin/User/removeByPK';
                var $title = '删除用户确认【' + optData.username + '】';
                var $msg = '请您确认是否删除ID为【' + optData.user_id + '】，<br/>用户名为【' + optData.username + '】的用户？';
                var $data = { 'user_id': optData.user_id };

                // 调用弹出确认窗口
                SugarCommons.makeConfirm({
                  title: $title,
                  msg: $msg,
                  url: $url,
                  data: $data,
                  complete: function (e) {
                    // 完成时刷新列表
                    $('#user-table-form [name="search"]').click();
                  },
                  sureClick: function (e) {
                    // alert('sure is ok');
                  },
                  cancelClick: function (e) {
                    // alert('cancle is ok');
                  }
                });
              }
            },

          }

        }
      },
    };

    // 自定义表格头部工具
    var toolbar = [
      {
        'batchPlay': {
          title: '批量启用',
          btnClass: 'btn-default',
          aria_label: 'Batch Start',
          btnIconClass: 'glyphicon-play',
          btnClick: function (e, formData) {
            var $title = '批量启用';
            var $msg = '请您确认是否批量启用？';
            var $url = '';
            $data.status = 0;

            SugarCommons.makeConfirm({
              title: $title,
              msg: $msg,
              url: $url,
              data: $data,
              complete: function (e) {
                // 完成时刷新列表
                $('#user-table-form [name="search"]').click();
              }
            });
          }
        },

        'batchStop': {
          title: '批量禁用',
          btnIconClass: 'glyphicon-pause',
          btnClick: function (e, formData) {
          }
        },
      }
    ];

    SugarTables.create('#user-table-form', '#user-table', '/Sugaradmin/User/loadAjax', columns, toolbar);

    // 当编辑等模拟框关闭时触发重新刷新列表
    /* $(SugarCommons.edit_dialog_tpl_id).on('hide.bs.modal', function (e) {
      // 判断是否关闭刷新，若不然每次关闭都会刷新
      if(SugarCommons.close_refresh){
        $('#user-table-form [name="search"]').click();
        // 重置关闭刷新标记
        SugarCommons.close_refresh = false;
      }
    }); */
  });
</script>