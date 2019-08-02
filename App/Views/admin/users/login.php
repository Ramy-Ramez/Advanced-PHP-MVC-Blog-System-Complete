<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo assets('admin/bootstrap/css/bootstrap.min.css'); ?>"><!-- assets() function is in helpers.php -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo assets('admin/dist/css/AdminLTE.min.css'); ?>"><!-- assets() function is in helpers.php -->
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo assets('admin/plugins/iCheck/square/blue.css'); ?>"><!-- assets() function is in helpers.php -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="<?php echo url('/admin/login/submit'); ?>" method="post" id="login-form"><!-- url() function is in helpers.php -->
            <div id="login-results" style="font-weight: bold"></div>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="Email" required="required">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password" required="required">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember"> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <!-- /.social-auth-links -->
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="<?php echo assets('admin/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script><!-- assets() function is in helpers.php -->
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo assets('admin/bootstrap/js/bootstrap.min.js'); ?>"></script><!-- assets() function is in helpers.php -->
<!-- iCheck -->
<script src="<?php echo assets('admin/plugins/iCheck/icheck.min.js'); ?>"></script><!-- assets() function is in helpers.php -->
<script>
    $(function () {
        var flag = false;//We use this flag to prevent submitting a request while there is another running...
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        loginResults = $('#login-results');

        $('#login-form').on('submit' , function (e) {
            e.preventDefault();//To prevent submitting the form from Reloading the page

            if (flag === true) {//return false means to stop the operation (stop being able to click submit) if there is already a running request
                return false;
            }

            form = $(this);

            requestUrl = form.attr('action');

            requestMethod = form.attr('method');

            requestData = form.serialize();//serialize() the data that will be sent to server
            //console.log(requestData);
            $.ajax({
                url: requestUrl,
                type: requestMethod,
                data: requestData,//Data sent from the form to server
                dataType: 'json', //Data returned from server
                beforeSend: function () {
                    flag = true;//We use this flag to prevent submitting a request while there is another running...
                    $('button').attr('disabled' , true);//To prevent user from being able to click the button while there is a running request
                    loginResults.removeClass().addClass('alert alert-info').html('Logging...');
                },
                success: function (results) {
                    //Here we have $json['success'], $json['redirect'], $json['errors'] from the LoginController.php
                    if (results.errors) {//Refer to $json['errors'] in LoginController.php in submit() function
                        /*errorsLength = results.errors.length;//How many errors in the array (Ex: 3 or 4,...)
                        console.log(results.errors);
                        console.log(errorsLength);
                        loginResults.removeClass().addClass('alert alert-danger').html('');
                        for (var i = 0; i <errorsLength; i++) {//How Many Errors in the Array
                            loginResults.append('<li>' + results.errors[i] + '</li>');
                                //console.log(results.errors[i]);
                        }*/
                        //console.log(results.errors);
                        //console.log(results.errors.length);
                        loginResults.removeClass().addClass('alert alert-danger').html(results.errors);
                        $('button').removeAttr('disabled');//To make the button clickable again if there is no a running request
                        flag = false;//We use this flag to prevent submitting a request while there is another running...
                    } else if (results.success) {//Refer to $json['success'] in LoginController.php in submit() function
                        loginResults.removeClass().addClass('alert alert-success').html(results.success);

                        setTimeout(function () {
                            if (results.redirect) {//Refer to $json['redirect'] in LoginController.php in submit() function
                                window.location.href = results.redirect;//Redirection
                            }
                        }, 2000);
                    }
                }
            });

        });

    });
</script>
</body>
</html>