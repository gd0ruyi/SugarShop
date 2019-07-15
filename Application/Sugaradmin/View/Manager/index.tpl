<!--导航条-->
<ol class="breadcrumb">
  <li><a href="#">首页</a></li>
  <!-- <li><a href="#" class="active">管理员管理</a></li> -->
  <li class="active">管理员管理</li>
</ol>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">管理员列表</h3>
  </div>
  <div class="panel-body sgtable-toolbar">
    <!-- 工具组 -->
    <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
      <div class="btn-group" role="group" aria-label="Setting groups">
        <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button>
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
    <form class="form-inline" id="manager-form">
      <div class="form-group form-group-sm">
        <label for="exampleInputName2">Name</label>
        <input type="text" class="form-control" id="exampleInputName2" name="name" placeholder="Jane Doe">
      </div>
      <div class="form-group form-group-sm">
        <label for="exampleInputEmail2">Email</label>
        <input type="email" class="form-control input-sm" id="exampleInputEmail2" name="email"
          placeholder="jane.doe@example.com">
      </div>
      <button type="submit" class="btn btn-primary">搜 索</button>
      <div class="table-responsive">
        <table id="manager-table" title="管理员列表-表格内容" class="table table-striped table-bordered table-hover ajax-tables">
        </table>
      </div>
    </form>
  </div>
</div>
<script language="javascript" type="text/javascript">
  // 执行创建表格（用于测试）
  var columns = {
    'manager_id': {
      th: { title: 'ID', sort_icon: true, sort_default: 'asc', class: 'text-center' },
      td: { style: "color:red; text-align:center" }
    },
    'username': {
      th: { title: '账户', sort_icon: true, sort_default: 'desc', class: 'text-center' },
    },
    'add_time': {
      th: { title: '创建时间', class: 'text-center', sort_icon: true },
      td: { template:"{add_time_format}",class: 'text-center' }
    },
    'upd_time': {
      th: { title: '更新时间', class: 'text-center', sort_icon: true },
      td: { template:"{upd_time_format}",class: 'text-center' }
    },
    'las_time': {
      th: { title: '登录时间', class: 'text-center', sort_icon: true },
      td: { template:"{las_time_format}",class: 'text-center' }
    },
    'other': {
      th: { title: '描述说明', title_length: 12 },
      td: { template: '{username} 用户的登录时间为： {las_time_format}' }
    },
    'status': {
      th: { title: '状态', sort_icon: true, class: 'text-center' },
      td: { template:"{status_name}", class: 'text-center' }
    },
    'operation': {
      th: { title: '操作', class: 'text-center' },
      td: { class: 'text-center' }
    },
  };
  SugarTables.create('#manager-form', '#manager-table', '/Sugaradmin/Manager/loadAjax', columns);
</script>