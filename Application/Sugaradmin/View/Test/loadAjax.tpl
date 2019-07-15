<!--导航条-->
<ol class="breadcrumb">
  <li><a href="#">首页</a></li>
  <li><a href="#">示例</a></li>
  <li class="active">加载Ajax动态表格示例</li>
</ol>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">加载Ajax动态表格示例</h3>
  </div>
  <div class="panel-body">
    <form class="form-inline" id="loadAjax-form">
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
        <!-- 加载ajax的表格 -->
        <table id="loadAjax-table" title="加载Ajax动态表格示例-表格内容"
          class="table table-striped table-bordered table-hover ajax-tables">
        </table>
      </div>
    </form>
  </div>
</div>
<script language="javascript" type="text/javascript">
  // 执行创建表格（用于测试）
  var columns = {
    'test_id': {
      th: { title: 'ID', title_length: 12, sort_icon: true, sort_default: 'desc', class: 'text-center' },
      td: { style: "color:red; text-align:center" }
    },
    'name': {
      th: { title: '名称', title_length: 12, sort_icon: true, sort_default: 'asc', class: 'text-center' },
      td: { template: "第{name}条 内容：{cname}", /*content_length : 12*/ }
    },
    'cname': {
      th: { title: '中文名称', title_length: 12, sort_icon: false, class: 'text-center' },
      td: {}
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
  // 联合排序
  // SugarTables.sort_multiply = true;
  SugarTables.create('#loadAjax-form', '#loadAjax-table', '/Sugaradmin/Test/loadAjaxJeson', columns);
</script>