<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="google-signin-client_id" content="628669127662-dk2i40g2csb1djghalak3o16034u3tpf.apps.googleusercontent.com">
    <link rel="apple-touch-icon" sizes="57x57" href="assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <meta name="msapplication-TileImage" content="assets/images/favicon/ms-icon-144x144.png">
    <title>Zumpango</title>
    <link type="text/css" rel="stylesheet" href="assets/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="assets/css/animate.css">
    <link type="text/css" rel="stylesheet" href="assets/css/material-design-iconic-font.css">
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="assets/css/components.css">
    <link type="text/css" rel="stylesheet" href="assets/css/layout.css">
    <link type="text/css" rel="stylesheet" href="assets/css/components.css">
    <link type="text/css" rel="stylesheet" href="assets/css/widgets.css">
    <link type="text/css" rel="stylesheet" href="assets/css/plugins.css">
    <link type="text/css" rel="stylesheet" href="assets/css/pages.css">
    <link type="text/css" rel="stylesheet" href="assets/css/pages.css">
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap-extend.css">
    <link type="text/css" rel="stylesheet" href="assets/css/common.css">
    <link type="text/css" rel="stylesheet" href="assets/css/responsive.css">
    <link type="text/css" rel="stylesheet" href="assets/css/best.css">

    <link type="text/css" rel="stylesheet" href="assets/css/admin-form.css">
    <script src="assets/js/lib/jquery.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="assets/js/jquery.parsley/dist/parsley.min.js"></script>
    <script type="text/javascript">
        var logout_facebook = "<?php echo $logout; ?>";
        var logout_google = "<?php echo $logout; ?>";

    </script>

    <style>
        

    </style>

</head>

<body style="background-color: #F4F4F4;" class="login-page">
    <!--Page Container Start Here-->
    <section class="login-container">
        <div class="container">
            <div class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4">
                <div class=" login-form-container">

                    <form id="demo-form" method="post" data-parsley-validate="" class="j-forms">

                        <div class="login-form-header">
                            <div class="logo">
                            <img src="/assets/files/fotos/<?php echo $this->tank_auth->get_logos(1);?> " alt="logo" width="50%" >
                            </div>
                        </div>

                        <div style="margin-top: 30px;" class="admin-form login-form-content">

                            <!-- start login -->
                            <div class="unit">
                                <div class="input login-input">
                                    <label class="icon-left" for="login">
                                        <i class="zmdi zmdi-account"></i>
                                    </label>
                                    <input class="form-control login-frm-input" type="text" id="login" name="login" placeholder="Correo o nombre de usuario" data-parsley-trigger="change" required="" />
                                </div>
                            </div>
                            <!-- end login -->

                            <!-- start password -->
                            <div class="unit">
                                <div class="input login-input">
                                    <label class="icon-left" for="password">
                                        <i class="zmdi zmdi-key"></i>
                                    </label>
                                    <input class="form-control login-frm-input" type="password" id="password" name="password" placeholder="Contraseña" required="" />
                                    <span class="hint" style="padding-top:10px;">
                                        <a href="user/forgot" class="link">¿Olvidaste la contrase&ntilde;a?</a>
                                    </span>
                                </div>
                            </div>
                            <!-- end password -->
                            <!-- start response from server -->
                            <div class="response" id="serverresponse" style="color:red;">&nbsp;</div>
                            <!-- end response from server -->

                        </div>
                        <div class="login-form-footer">
                            <button type="button" id="loginbtn" class="btn btn-entrar">Iniciar sesión</button>
                        </div>

                    </form>


                </div>
            </div>
        </div>
        </div>


        </div>
        <!--Footer Start Here -->
        <footer class="login-page-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4">
                        <div class="footer-content">

                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--Footer End Here -->
    </section>
    <!--Page Container End Here-->
    <script src="assets/js/lib/jquery.js"></script>
    <script src="assets/js/lib/jquery-migrate.js"></script>
    <script src="assets/js/lib/bootstrap.js"></script>
    <script src="assets/js/lib/jRespond.js"></script>
    <script src="assets/js/lib/hammerjs.js"></script>
    <script src="assets/js/lib/jquery.hammer.js"></script>
    <script src="assets/js/lib/smoothscroll.js"></script>
    <script src="assets/js/lib/smart-resize.js"></script>
    <script src="assets/js/lib/jquery.form.js"></script>
    <script src="assets/js/lib/j-forms.js"></script>
    <script src="assets/js/lib/sha.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-messaging.js"></script>
    <script src="assets/js/login.js?j=<?php echo uniqid(); ?>"></script>
    <script src="assets/js/jquery.parsley/dist/parsley.min.js"></script>
    <script src="assets/js/lib/sweetalert.js"></script>
    
    <script type="text/javascript">
        $("#facebookbtn").click(function(e) {
            window.location.href = "/home";
        });
        $("#googlebtn").click(function(e) {
            window.location.href = "/home";
        });

    </script>

</body>

</html>
