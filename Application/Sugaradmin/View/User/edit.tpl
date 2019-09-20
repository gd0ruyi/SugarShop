<!--导航条-->
<ol class="breadcrumb">
  <li><a href="#">首页</a></li>
  <li><a href="#">用户管理</a></li>
  <li class="active">创建用户</li>
</ol>

<div class="panel panel-primary">
  <div class="panel-body">
    <!-- 表单 -->
    <form class="form-horizontal" id="user-edit-form">

      <div class="form-group">
        <label for="username" class="col-sm-2 control-label">用户名称：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="username" name="username" placeholder="用户名称，唯一， 任意字符">
        </div>
      </div>

      <div class="form-group">
        <label for="password" class="col-sm-2 control-label">密码：</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="password" placeholder="密码，6位任意字符">
        </div>
      </div>

      <div class="form-group">
        <label for="repassword" class="col-sm-2 control-label">重复密码：</label>
        <div class="col-sm-6">
          <input type="repassword" class="form-control" id="repassword" placeholder="重复密码，6位任意字符，与密码保持一致">
        </div>
      </div>

      <div class="form-group">
        <label for="truename" class="col-sm-2 control-label">真实姓名：</label>
        <div class="col-sm-6">
          <input type="truename" class="form-control" id="truename" placeholder="真实姓名，6位任意字符">
        </div>
      </div>

      <div class="form-group">
        <label for="email" class="col-sm-2 control-label">邮箱：</label>
        <div class="col-sm-6">
          <input type="email" class="form-control" id="email" placeholder="邮箱格式">
        </div>
      </div>

      <div class="form-group">
        <label for="mobile" class="col-sm-2 control-label">电话：</label>
        <div class="col-sm-6">
          <input type="mobile" class="form-control" id="mobile" placeholder="手机号码格式，11位数字">
        </div>
      </div>

      <div class="form-group">
        <label for="status" class="col-sm-2 control-label">类型：</label>
        <div class="col-sm-6">
          <div class="btn-group" sugar-selector="true">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <font>请选择类型</font> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#" value="0">管理员</a></li>
              <li><a href="#" value="1">普通用户</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#" value="all">请选择类型</a></li>
            </ul>
            <input type="hidden" name="user_type" value="">
          </div>
        </div>
      </div>

      <div class="form-group ">
        <label for="status" class="col-sm-2 control-label">状态：</label>
        <div class="col-sm-6 radio">
          <label>
            <input name="status" type="radio" checked> 启用
          </label>
          <label>
            <input name="status" type="radio"> 禁用
          </label>
        </div>
      </div>

      <div class="form-group ">
        <div class="col-sm-offset-4 col-sm-6">
          <button type="submit" class="col-sm-offset-2 col-sm-3 btn btn-primary">保 存</button>
        </div>
      </div>

    </form>
  </div>
</div>
<script language="javascript" type="text/javascript">
  // 加载下拉
  SugarCommons.createSelectInput();
</script>