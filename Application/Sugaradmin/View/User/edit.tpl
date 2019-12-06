<!--导航条-->
<ol class="breadcrumb">
  <li><a href="#">首页</a></li>
  <li><a href="#">用户管理</a></li>
  <li class="active">创建用户</li>
</ol>

<div class="panel panel-primary">
  <div class="panel-body">
    <!-- 表单 -->
    <form class="form-horizontal" id="user-edit-form" action="/Sugaradmin/User/save">
      <!-- 隐藏域 -->
      <div class="form-group hidden">
        <label for="user_id" class="col-sm-2 control-label">用户ID：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="user_id" name="user_id" value="" placeholder="用户ID，唯一，不可更改" readonly="readonly">
        </div>
      </div>

      <div class="form-group">
        <label for="username" class="col-sm-2 control-label">用户名称：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="username" name="username" value="" placeholder="用户名称，唯一，任意字符" disabled="disabled">
        </div>
      </div>

      <div class="form-group">
        <label for="password" class="col-sm-2 control-label">密码：</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="password" name="password" placeholder="密码，6位任意字符" autocomplete="new-password" disabled="disabled">
        </div>
      </div>

      <div class="form-group">
        <label for="repassword" class="col-sm-2 control-label">重复密码：</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="repassword" name="repassword" placeholder="重复密码，6位任意字符，与密码保持一致" disabled="disabled">
        </div>
      </div>

      <div class="form-group">
        <label for="truename" class="col-sm-2 control-label">真实姓名：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="truename" name="truename" placeholder="真实姓名，6位任意字符">
        </div>
      </div>

      <div class="form-group">
        <label for="email" class="col-sm-2 control-label">邮箱：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="email" name="email" placeholder="邮箱格式">
        </div>
      </div>

      <div class="form-group">
        <label for="mobile" class="col-sm-2 control-label">电话：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control forceToNumber" id="mobile" name="mobile" placeholder="手机号码格式，11位数字">
        </div>
      </div>

      <!-- <div class="form-group">
        <label for="use_type"" class="col-sm-2 control-label">类型：</label>
        <div class="col-sm-6">
          <div class="btn-group">
            <select class="form-control" id="use_type" name="use_type">
              <option value="all" selected="selected">请选择类型</option>
              <option value="0">管理员</option>
              <option value="1">普通用户</option>
            </select>
          </div>
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
            <input type="hidden" class="form-control" id="use_type" name="use_type" value="">
          </div> 
        </div>
      </div>-->

      <div class="form-group ">
        <label for="status" class="col-sm-2 control-label">使用状态：</label>
        <div class="col-sm-6 radio">
          <label>
            <input name="status" type="radio" value="0"> 启用
          </label>
          <label>
            <input name="status" type="radio" value="1"> 禁用
          </label>
        </div>
      </div>

      <div class="form-group ">
        <div class="col-sm-offset-4 col-sm-6">
          <button id="user-edit-submit" name="user-edit-submit" type="submit" class="col-sm-offset-2 col-sm-3 btn btn-primary">保 存</button>
          <button type="reset" class="col-sm-offset-2 col-sm-3 btn btn-default" data-toggle="modal">重 置</button>
        </div>
      </div>

    </form>
  </div>
</div>
<script language="javascript" type="text/javascript">
  $(document).ready(function () {
    // 表单ID
    var editFormTarget = '#user-edit-form';

    // 表单的数据加载
    var editData = '<{$user}>';
    // 数据赋值表单
    editData = JSON.parse(editData);
    SugarCommons.setFormInputValue(editFormTarget, editData);

    // 加载下拉
    // SugarCommons.createSelectInput();

    // 创建数字输入框，自动强制校正数字输入,使用自定义样式进行处理
    SugarCommons.createInputForceToNumber();

    // 初始化重置按钮设置
    SugarCommons.createResetFormButton(editFormTarget, function () {
      // 自动初始化
      userAutoInit(editFormTarget);
      SugarCommons.setFormInputValue(editFormTarget, editData);
    });

    // 保存时初始化表单
    function userEditInit(editFormTarget) {
      // 用户ID只读
      $(editFormTarget).find('[name="user_id"]').parent().parent().removeClass('hidden');
      $(editFormTarget).find('[name="user_id"]').attr('readonly', 'readonly');
      // 用户名称不可修改
      $(editFormTarget).find('[name="username"]').attr('disabled', 'disabled');

      // 密码和重复密码不可修改
      $(editFormTarget).find('[name="password"]').attr('disabled', 'disabled');
      $(editFormTarget).find('[name="repassword"]').attr('disabled', 'disabled');

      // 触发密码修改
      var $placeholder = $(editFormTarget).find('[name="password"]').attr('placeholder');
      $placeholder = $placeholder + '，密码单击可修改或不修改';
      $(editFormTarget).find('[name="password"]').attr('placeholder', $placeholder);
      var edit_password = true;
      $(editFormTarget).find('[name="password"]').parent().unbind('dblclick');
      $(editFormTarget).find('[name="password"]').parent().dblclick(function (e) {
        if (edit_password) {
          $(editFormTarget).find('[name="password"]').removeAttr('disabled');
          $(editFormTarget).find('[name="repassword"]').removeAttr('disabled');
        } else {
          $(editFormTarget).find('[name="password"]').attr('disabled', 'disabled');
          $(editFormTarget).find('[name="repassword"]').attr('disabled', 'disabled');
        }
        edit_password = edit_password ? false : true;
      });
    }

    // 新增处理，表单初始化设置
    function userNewInit(editFormTarget) {
      // 清除自动填充
      if ($("#username").val() == "") {
        $("#username").val("");
        $("#password").val("");
      }

      // 表单验证重置
      $(editFormTarget).data('bootstrapValidator').resetForm();
      // 重置表单内容
      $(editFormTarget)[0].reset();

      // $(editFormTarget).find('[name="user_id"]').parent().parent().addClass('hidden');
      $(editFormTarget).find('[name="user_id"]').removeAttr('disabled', 'disabled');
      $(editFormTarget).find('[name="username"]').removeAttr('disabled', 'disabled');
      $(editFormTarget).find('[name="username"]').focus();
      $(editFormTarget).find('[name="password"]').removeAttr('disabled', 'disabled');
      $(editFormTarget).find('[name="repassword"]').removeAttr('disabled', 'disabled');
      $(editFormTarget).find('[name="status"]').removeAttr('checked');
      $(editFormTarget).find('[name="status"]:first').click();
      // $(editFormTarget).find('[name="username"]').removeAttr('disabled');
    }

    // 自动初始化
    function userAutoInit(editFormTarget) {
      if (editData != 'null' && editData != null) {
        userEditInit(editFormTarget);
      } else {
        userNewInit(editFormTarget);
      }
    }

    // 自动验证参数配置
    var $bvOptions = {
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
              delay: 3000,
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
        /* use_type: {
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
        } */

      },

    };


    // bootstrap自动验证插件验证处理
    $(editFormTarget).bootstrapValidator($bvOptions).on('success.form.bv', function (e) {

      // 调取通用的bootstrapValidator表单验证提交方式
      SugarCommons.bvAjaxSubmit(e,
        // 保存成功时调用刷新
        function (e) {
          // 查看e是否传递到方法内
          // console.log(e.target);

          // 执行初始化
          userAutoInit(editFormTarget);

          // 点击触发刷新
          $('#user-table-form [name="search"]').click();
          // 重置关闭刷新标记
          // SugarCommons.close_refresh = false;
        },

        // 保存失败后调用
        function (e) {
          // 示例，暂时无用
        })
    });

    // 执行初始化
    userAutoInit(editFormTarget);

  });
</script>