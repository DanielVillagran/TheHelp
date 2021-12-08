<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Dexfit</title>
    <link type="text/css" rel="stylesheet" href="/assets/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/material-design-iconic-font.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/animate.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/layout.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/components.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/widgets.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/plugins.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/pages.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/bootstrap-extend.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/common.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body class="login-page">
<!--Page Container Start Here-->
<section class="login-container">
<div class="container">
<div class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4">
<div class="login-form-container">
    <form id="recover-form" method="post" data-parsley-validate="" class="j-forms" >

        <div class="login-form-header">
            <div class="logo">
                <img src="/assets/images/esprezza.png" alt="logo" width="80%"/>
            </div>
        </div>
        <div class="login-form-content">
            <!-- start login -->
            <div class="unit">
                <div class="input login-input">
                    <label class="icon-left" for="login">
                        <i class="zmdi zmdi-account"></i>
                    </label>
                    <input class="form-control login-frm-input"  type="text" id="login" name="login" placeholder="Email" required>
                </div>
            </div>
            <!-- end login -->
            <!-- start response from server -->
            <div class="response"></div>
            <!-- end response from server -->



        </div>
        <div class="login-form-footer">
            <button type="submit" id="recoverbtn" class="btn-block btn btn-primary">Recuperar</button>
            <button type="button" id="cancelbtn" class="btn-block btn btn-danger">Cancelar</button>
        </div>
    </form>
</div>
</div>
</div>
<!--Footer Start Here -->
<footer class="login-page-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4">
                <div class="footer-content">
                    <span class="footer-meta"><a href="http://www.ant.com.mx">ANT.com.mx</a></span>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--Footer End Here -->
</section>
<!--Page Container End Here-->
<script src="/assets/js/lib/jquery.js"></script>
<script src="/assets/js/lib/jquery-migrate.js"></script>
<script src="/assets/js/lib/bootstrap.js"></script>
<script src="/assets/js/lib/jRespond.js"></script>
<script src="/assets/js/lib/hammerjs.js"></script>
<script src="/assets/js/lib/jquery.hammer.js"></script>
<script src="/assets/js/lib/smoothscroll.js"></script>
<script src="/assets/js/lib/smart-resize.js"></script>

<script src="/assets/js/lib/jquery.validate.js"></script>
<script src="/assets/js/lib/jquery.form.js"></script>
<script src="/assets/js/lib/j-forms.js"></script>
<script src="/assets/js/lib/login-validation.js"></script>
<script src="/assets/js/login.js"></script>
<script src="/assets/js/jquery.parsley/dist/parsley.min.js"></script>
<script src="/assets/js/lib/sweetalert.js"></script>
<script type="text/javascript">
	$("#cancelbtn").click(function (e) {
	        window.location.href = "/";
	});
</script>
</body>
</html>