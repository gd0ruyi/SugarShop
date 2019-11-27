<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="renderer" content="webkit">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SugarAdmin-登录</title>
  <!-- 注：min.css与css样式不同，这里统一使用css -->
  <link href="/Public/bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet">
  <link href="/Public/bootstrap-3.3.5-dist/css/bootstrap-theme.css" rel="stylesheet">
  <!-- <link href="/Public/bootstrapvalidator-0.4.5/dist/css/bootstrapValidator.min.css" rel="stylesheet"> -->
  <link href="/Public/bootstrapvalidator-0.5.2/dist/css/bootstrapValidator.css" rel="stylesheet">
  <link href="/Public/Sugaradmin/css/login.css" rel="stylesheet">
  <!--[if lt IE 9]>
  <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="page-header"></div>
    </div>

    <div class="row">
      <div class="col-sm-3"><!--左空白--></div>

      <div class="col-sm-5">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="text-center">SugarShop管理登录</h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" id="login-form" method="post" action="<{U url='/Sugaradmin/Login/login'}>">

              <div class="row center-block">
                <div class="form-group col-sm-12 col-xs-12">
                  <label for="usename" class="col-sm-2 control-label hidden-xs">用户名</label>
                  <div class="col-sm-8 col-xs-11 input-group">
                    <span class="input-group-addon">
                      <font class="glyphicon glyphicon-user"></font>
                    </span>
                    <input type="text" class="form-control input-sm" id="username" name="username" placeholder="请输入用户名" />
                  </div>
                </div>
              </div>

              <div class="row center-block">
                <div class="form-group col-sm-12 col-xs-12">
                  <label for="password" class="col-sm-2 control-label hidden-xs">密码</label>
                  <div class="col-sm-8 col-xs-11 input-group">
                    <span class="input-group-addon">
                      <font class="glyphicon glyphicon-lock"></font>
                    </span>
                    <input type="password" class="form-control input-sm" id="password" name="password" placeholder="请输入密码" />
                  </div>
                </div>
              </div>

              <div class="row center-block verify-box">
                <div class="form-group col-sm-12 col-xs-12 row">
                  <label for="verification" class="col-sm-2 control-label hidden-xs">验证码</label>
                  <div class="col-sm-8 col-xs-11 input-group">
                    <div class="row">
                      <div class="col-sm-4 col-xs-4">
                        <input type="text" class="form-control input-sm" id="verification" name="verification" placeholder="请输入验证码" maxlength="4" autocomplete="off" />
                      </div>
                      <div class="col-sm-5 col-sm-offset-0 col-xs-5 col-xs-offset-0">
                        <img id="verify-img" class="img-thumbnail" alt="点击刷新" title="点击刷新" src="<{U url='/Home/Verify/index'}>" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row center-block">
                <div class="form-group col-xs-12">
                  <div class="form-group row text-center">
                    <button type="submit" class="btn btn-lg btn-primary" name="login-button">登 录</button>
                  </div>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>

      <div class="col-sm-3"><!--右空白--></div>
    </div>

    <div class="row">
    </div>
  </div>
  <script src="/Public/jquery/jquery-1.11.3.min.js"></script>
  <script src="/Public/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
  <!-- <script src="/Public/bootstrapvalidator-0.4.5/dist/js/bootstrapValidator.min.js"></script> -->
  <script src="/Public/bootstrapvalidator-0.5.2/dist/js/bootstrapValidator.min.js"></script>
  <script language="javascript" type="text/javascript">
    // 登录验证
    $(document).ready(function () {

      // 验证码处理
      $('#verify-img').click(function () {
        var $vimg_src = $('#verify-img').attr('src');
        $(this).attr('src', $vimg_src + '?' + Math.random());
      });

      // 验证插件配置
      $bvOptions = {
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
          username: {
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
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: '密码不能为空'
              }
            }
          },
          verification: {
            validators: {
              notEmpty: {
                message: '验证码不能为空'
              },
              stringLength: {
                min: 4,
                max: 4,
                message: '验证码长度必须为4位'
              }
            },
          },
        },
        submitButtons: 'button[type="submit"]'
      };

      // 执行验证方法
      $("#login-form").bootstrapValidator($bvOptions).on('success.form.bv', function (e, data) {
        // 登录按钮加入等待图标
        $('button[type="submit"]').append('    <span class="glyphicon glyphicon-refresh refresh-animation"></span>');
      });

    });
  </script>
</body>

</html>