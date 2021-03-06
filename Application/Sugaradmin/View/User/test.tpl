<!DOCTYPE html>
<html>

<head>
    <title>Using Ajax to submit data</title>

    <link href="/Public/bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet">
    <link href="/Public/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="/Public/bootstrapvalidator-0.5.2/dist/css/bootstrapValidator.min.css" rel="stylesheet">
    <link href="/Public/Sugaradmin/css/style.css" rel="stylesheet">

    <script src="/Public/jquery/jquery-1.11.3.min.js"></script>
    <script src="/Public/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <!-- Bootstrap验证加载 -->
    <script src="/Public/bootstrapvalidator-0.5.2/dist/js/bootstrapValidator.min.js"></script>
    <script language="javascript" type="text/javascript" src="/Public/Sugaradmin/js/common.js"></script>
    <script language="javascript" type="text/javascript" src="/Public/Sugaradmin/js/timekeeper.js"></script>
    <script src="/Public/Sugaradmin/js/tabs.js" language="javascript"></script>
    <script language="javascript" type="text/javascript" src="/Public/Sugaradmin/js/tables.js"></script>


    <!-- 分割线 -->


    <!-- <link rel="stylesheet" href="/Public/bootstrapvalidator-0.5.2/vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="/Public/bootstrapvalidator-0.5.2/dist/css/bootstrapValidator.css" />
    <link href="/Public/Sugaradmin/css/style.css" rel="stylesheet"> -->

    <!-- <script src="/Public/jquery/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/Public/bootstrapvalidator-0.5.2/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/bootstrapvalidator-0.5.2/dist/js/bootstrapValidator.js"></script> -->
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="page-header">
                    <h2>Using Ajax to submit data</h2>
                </div>

                <form id="defaultForm" method="post" class="form-horizontal" action="">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Username</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="username" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Email address</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="email" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Password</label>
                        <div class="col-lg-5">
                            <input type="password" class="form-control" name="password" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                            <button type="submit" class="btn btn-primary">Sign up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#defaultForm')
                .bootstrapValidator({
                    message: 'This value is not valid',
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        username: {
                            message: 'The username is not valid',
                            validators: {
                                notEmpty: {
                                    message: 'The username is required and can\'t be empty'
                                },
                                stringLength: {
                                    min: 6,
                                    max: 30,
                                    message: 'The username must be more than 6 and less than 30 characters long'
                                },
                                /*remote: {
                                    url: 'remote.php',
                                    message: 'The username is not available'
                                },*/
                                regexp: {
                                    regexp: /^[a-zA-Z0-9_\.]+$/,
                                    message: 'The username can only consist of alphabetical, number, dot and underscore'
                                }
                            }
                        },
                        email: {
                            validators: {
                                notEmpty: {
                                    message: 'The email address is required and can\'t be empty'
                                },
                                emailAddress: {
                                    message: 'The input is not a valid email address'
                                }
                            }
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required and can\'t be empty'
                                }
                            }
                        }
                    }
                })
                .on('success.form.bv', function (e) {
                    // Prevent form submission
                    e.preventDefault();
                    alert("OK");

                    // Get the form instance
                    var $form = $(e.target);

                    // Get the BootstrapValidator instance
                    var bv = $form.data('bootstrapValidator');
                    return false;

                    // Use Ajax to submit form data
                    // $.post($form.attr('action'), $form.serialize(), function (result) {
                    //     console.log(result);
                    // }, 'json');
                });
        });
    </script>
</body>

</html>