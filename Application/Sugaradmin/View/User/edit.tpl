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
          <input type="text" class="form-control" id="username" name="username" value="<{$user.username}>" placeholder="用户名称，唯一， 任意字符">
        </div>
      </div>

      <div class="form-group">
        <label for="password" class="col-sm-2 control-label">密码：</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="password" name="password" placeholder="密码，6位任意字符" autocomplete="new-password">
        </div>
      </div>

      <div class="form-group">
        <label for="repassword" class="col-sm-2 control-label">重复密码：</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="repassword" name="repassword" placeholder="重复密码，6位任意字符，与密码保持一致">
        </div>
      </div>

      <div class="form-group">
        <label for="truename" class="col-sm-2 control-label">真实姓名：</label>
        <div class="col-sm-6">
          <input type="truename" class="form-control" id="truename" name="truename" placeholder="真实姓名，6位任意字符">
        </div>
      </div>

      <div class="form-group">
        <label for="email" class="col-sm-2 control-label">邮箱：</label>
        <div class="col-sm-6">
          <input type="email" class="form-control" id="email" name="email" placeholder="邮箱格式">
        </div>
      </div>

      <div class="form-group">
        <label for="mobile" class="col-sm-2 control-label">电话：</label>
        <div class="col-sm-6">
          <input type="mobile" class="form-control forceToNumber" id="mobile" name="mobile" placeholder="手机号码格式，11位数字">
        </div>
      </div>

      <div class="form-group">
        <label for="use_type"" class="col-sm-2 control-label">类型：</label>
        <div class="col-sm-6">
          <div class="btn-group">
            <select class="form-control" id="use_type" name="use_type">
              <option value="all" selected="selected">请选择类型</option>
              <option value="0">管理员</option>
              <option value="1">普通用户</option>
            </select>
          </div>
          <!-- <div class="btn-group" sugar-selector="true">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <font>请选择类型</font> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#" value="0">管理员</a></li>
              <li><a href="#" value="1">普通用户</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#" value="all">请选择类型</a></li>
            </ul>
            <input type="hidden" class="form-control" id="use_type" name="use_type" value="">
          </div> -->
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
          <button id="user-edit-submit" name="user-edit-submit" type="submit" class="col-sm-offset-2 col-sm-3 btn btn-primary">保 存</button>
        </div>
      </div>

    </form>
  </div>
</div>
<!-- Bootstrap验证加载 -->
<link href="/Public/bootstrapvalidator-0.4.5/dist/css/bootstrapValidator.min.css" rel="stylesheet">
<script src="/Public/bootstrapvalidator-0.4.5/dist/js/bootstrapValidator.min.js"></script>
<script language="javascript" type="text/javascript">
  // 表单验证
  $(document).ready(function () {
    // 加载下拉
    // SugarCommons.createSelectInput();

    // 自动强制校正数字输入,使用自定义样式进行处理
    SugarCommons.forceToNumber();

    // 清除自动填充
    if ($("#username").val() == "") {
      $("#username").val("");
      $("#password").val("");
    }

    // bootstrap自动验证插件验证处理
    $("#user-edit-form").bootstrapValidator({
      // excluded: [':disabled', ':hidden', ':not(:visible)'],
      excluded: [":disabled"],

      // 图标设置
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },

      // 表单提交按钮设置
      submitButtons: '#user-edit-submit',

      // 字段验证
      fields: {
        // 用户名验证
        username: {
          //有4字符以上才开始进行验证
          threshold: 4,
          //message: '用户名无效',
          validators: {
            notEmpty: {
              message: '用户名不能为空'
            },
            stringLength: {
              min: 4,
              max: 12,
              message: '用户名长度必须为4~12'
            },
            regexp: {
              regexp: /^[a-zA-Z0-9_]+$/,
              message: '用户名只能由字母、数字和下划线组成'
            },
            //ajax验证。server result:{"valid",true or false} 
            remote: {
              url: "/Sugaradmin/User/checkUserUnique",
              message: '用户名已存在,请重新输入',
              //ajax刷新的时间是1秒一次
              delay: 1000,
              type: 'POST',
              //自定义提交数据，默认值提交当前input value
              data: function (validator) {
                return {
                  username: $("input[name=username]").val()
                };
              }
            }
          }
        },

        // 输入密码校验
        password: {
          validators: {
            notEmpty: {
              message: '密码不能为空'
            },
            stringLength: {
              min: 6,
              max: 12,
              message: '密码长度必须为6~12'
            },
            //比较
            different: {
              //需要进行比较的input name值
              field: 'username',
              message: '密码不能与用户名相同'
            },
          }
        },

        // 重复密码校验
        repassword: {
          validators: {
            notEmpty: {
              message: '重复密码不能为空'
            },
            stringLength: {
              min: 6,
              max: 12,
              message: '重复密码长度必须为6~12'
            },
            //比较是否相同
            identical: {
              //需要进行比较的input name值
              field: 'password',
              message: '两次密码不一致'
            },
          },
        },

        // 真实姓名验证
        truename: {
          validators: {
            notEmpty: {
              message: '真实姓名不能为空'
            },
            stringLength: {
              min: 2,
              max: 32,
              message: '用户名长度必须为2~32'
            }
          }
        },

        // 邮箱验证
        email: {
          validators: {
            notEmpty: {
              message: '邮箱地址不能为空'
            },
            emailAddress: {
              message: '邮箱地址不正确'
            }
          }
        },

        // 手机号码验证
        mobile: {
          validators: {
            notEmpty: {
              message: '手机号码不能为空'
            },
            regexp: {
              regexp: /^1\d{10}$/,
              message: '手机号必须为11位数字'
            }
            /* phone: {
              country: 'CN',
              message: '电话号码格式错误'
            } */
          }
        },

        // 用户类型验证
        use_type: {
          // 变更时触发
          // trigger:"click",
          validators: {
            notEmpty: {
              message: '用户类型不能为空'
            },
            callback: {
              message: '请选择用户类型',
              callback: function (value, validator, $field) {
                if (value == 'all') {
                  return false;
                }
                return true;
              }
            }
          }
        }

      },

    });

  });
</script>