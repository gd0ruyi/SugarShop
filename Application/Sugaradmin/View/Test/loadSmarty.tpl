<!--导航条-->
<ol class="breadcrumb">
  <li><a href="#">首页</a></li>
  <li><a href="#">示例</a></li>
  <li class="active">加载smarty模版示例</li>
</ol>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">示例列表</h3>
  </div>
  <div class="panel-body">
    <form class="form-inline" id="admin-form">
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
        <!-- 此处为通过html模版实现列表加载的示例  -->
        <table id="" class="table table-striped table-bordered table-hover ajax-tables">
          <thead>
            <tr>
              <th>ID</th>
              <th>名称</th>
              <th>中文名称</th>
              <th>状态</th>
              <th>状态名称</th>
            </tr>
          </thead>
          <tbody>
            <{foreach from=$data item=list}>
              <tr>
                <td>
                  <{$list.test_id}>
                </td>
                <td>
                  <{$list.name}>
                </td>
                <td>
                  <{$list.cname}>
                </td>
                <td>
                  <{$list.status}>
                </td>
                <td>
                  <{$list.status_name}>
                </td>
              </tr>
              <{/foreach}> </tbody> </table> </table> </div> </form> </div> </div>