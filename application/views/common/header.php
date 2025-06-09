<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>The Help | Panel de Administración</title>
    <link rel="apple-touch-icon" sizes="57x57" href="../../../assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../../../assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../../assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../../assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../../../assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../../../assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../../../assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../../assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../../../assets/images/favicon/apple-icon-60x60.pngassets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../../assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../../assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../../assets/images/favicon/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../../../assets/images/favicon/apple-icon-60x60.pngassets/images/favicon/ms-icon-144x144.png">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome-4.7.0/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/material-design-iconic-font.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/emojionearea.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/magnific-popup.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/layout.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/widgets.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/pages.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-extend.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/common.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/best.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/admin-form.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/theme.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/admin-form-2.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/dataTables.responsive.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/wizard.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-timepicker-addon.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-select.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mtr-datepicker.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mtr-datepicker.default-theme.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mtr-datepicker.clutterboard-theme.min.css">


    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/images/apple-touch-icon_dexfit.png">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon_dexfit.png" type="image/x-icon">

    <link href="https://davidstutz.de/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" rel="stylesheet" />
    <!-- Style Autoloader -->
    <?php
$styles = isset($styles) ? $styles : false;
if (is_string($styles)):
    echo "\t" . '<link type="text/css" rel="stylesheet" href="' . base_url() . 'assets/css/' . $styles . '.css?p=' . uniqid() . '" />';
elseif (is_array($styles)):
    foreach ($styles as $css):
        echo "\n\t" . '<link type="text/css" rel="stylesheet" href="' . base_url() . 'assets/css/' . $css . '.css?p=' . uniqid() . '" />';
    endforeach;
endif;
?>

    <!-- Custom -->
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.js"></script>
    <script type="text/javascript">
        var grupo = '<?php echo $this->tank_auth->get_user_role(); ?>';
        var pagina_previa = '<?php echo (isset($account_id) && $account_id > 0) ? '/account/edit/' . $account_id : '/'; ?>';
        var logged_id = "<?php echo $this->tank_auth->get_user_id(); ?>";
        //var notifications = <?php echo (isset($notifications) ? count($notifications) : 0); ?>;

    </script>
</head>

<body class="leftbar-view">
    <!--Topbar Start Here-->
    <header class="topbar clearfix">
        <!--Top Search Bar Start Here-->
        <div class="top-search-bar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="search-input-addon">
                            <span class="addon-icon"><i class="zmdi zmdi-search"></i></span>
                            <input type="text" class="form-control top-search-input" placeholder="Search">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Top Search Bar End Here-->
        <!--Topbar Left Branding With Logo Start-->
        <?php
$url = "/assets/files/fotos/" . $this->tank_auth->get_logos(2);
$clase = "";
switch ($this->tank_auth->get_user_type()) {
    case 2:
        $url = "/assets/files/fotos/" . $this->tank_auth->get_logos(3);
        $clase = "dif";
        break;
    case 3:
        $url = "/assets/files/fotos/" . $this->tank_auth->get_logos(4);
        $clase = "oda";
        break;
    case 4:
        $url = "/assets/files/fotos/" . $this->tank_auth->get_logos(5);
        $clase = "imc";
        break;

}
?>
        <div class="topbar-left pull-left <?php echo $clase; ?>">
            <div class="clearfix">
                <ul class="left-branding pull-left clickablemenu ttmenu dark-style menu-color-gradient">
                    <li><span class="left-toggle-switch"><i class="zmdi zmdi-menu"></i></span></li>
                    <li>
                        <div class="logo-header">
                           <a href="/home/"><img src="<?php echo $url; ?>" alt=""></a>
                        </div>
                    </li>
                </ul>
                <!--Mobile Search and Rightbar Toggle-->
                <ul class="branding-right pull-right">
                    <li><a href="#" class="btn-mobile-search btn-top-search"><i class="zmdi zmdi-search"></i></a></li>
                    <li><a href="#" class="btn-mobile-bar"><i class="zmdi zmdi-menu"></i></a></li>
                </ul>
            </div>
        </div>
        <!--Topbar Left Branding With Logo End-->


        <div class="d-top-name-sec">
            <p class="p-name-sec">Administración <b>The Help App</b></p>
        </div>

        <!--Topbar Right Start-->
        <div style="display: none;" class="topbar-right pull-right">
            <div class="clearfix">
                <!--Mobile View Leftbar Toggle-->
                <ul class="left-bar-switch pull-left">
                    <li><span class="left-toggle-switch"><i class="zmdi zmdi-menu"></i></span></li>
                </ul>
                <ul class="pull-right top-right-icons">
                    <li><a href="#" class="btn-top-search"><i class="zmdi zmdi-search"></i></a></li>
                    <li class="dropdown notifications-dropdown">
                        <a href="javascript:;" class="btn-notification dropdown-toggle" data-toggle="dropdown">
                            <!-- <?php if (count($notifications) > 0): ?>
                    <span class="noty-bubble"><?=count($notifications)?></span>
                    <?php endif;?> -->
                            <i class="zmdi zmdi-globe"></i></a>
                        <div class="dropdown-menu notifications-tabs" style="width: 330px;">
                            <div>
                                <ul class="nav material-tabs nav-tabs" role="tablist">
                                    <li class="active"><a href="#notifications" aria-controls="notifications" role="tab" data-toggle="tab" style="box-shadow: inset 0 -2px 0 #786718;color: #786718;">Notificaciones</a></li>
                                </ul>
                                <div class="tab-content">
                                    <h4>
                                        <div class="row">
                                            <div class="col-md-8" style="text-align: left;margin-left: -5px;">
                                                <input type="checkbox" id="sel_all_checks">&nbsp;&nbsp;<label for="sel_all_checks">Seleccionar todas</label>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="javascript:;" class="btn btn-danger btn-sm row-status" id="del_all_checks" style="margin-top:-5px;">Eliminar</a>
                                            </div>
                                        </div>
                                    </h4>
                                    <div role="tabpanel" class="tab-pane active" id="notifications">
                                        <div class="notification-wrap">
                                            <?php if (count($notifications) > 0): ?>
                                            <ul>
                                                <form id="form_noti" method="post" action="/notification/deactive">
                                                    <?php foreach ($notifications as $e): ?>
                                                    <li class="notification-message" id="noti_email_<?=$e['id']?>">
                                                        <div class="row" style="border-bottom: 1px solid #f1f1f1;padding: 5px">
                                                            <div class="col-sm-1">
                                                                <input type="checkbox" class="noti_check" name="noti_check[]" value="<?=$e['id']?>" style="vertical-align: middle;margin-left:5px;">
                                                            </div>

                                                            <?php
$modal = 0;
if ($e['type'] == 108 || $e['type'] == 109) {
    $modal = 1;
}
?>

                                                            <div class="col-sm-10">
                                                                <a onclick="check_noti(<?=$e['id'] . "," . $e['erp_ticket_id'] . "," . $modal . ",'" . $e['url'] . "'"?>);" style="text-decoration: none;vertical-align: middle;"><span class="notification-message" style="font-size: 12px;">
                                                                        <?php echo $e['title'] . ' - ' . $e['message']; ?>
                                                                        <!--<span class="notification-time clearfix">Ir al Ticket</span>-->
                                                                    </span></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php endforeach;?>
                                                </form>
                                            </ul>
                                            <?php else: ?>
                                            <ul>
                                                <li><a href="#" class="clearfix"><span class="notification-message">Sin notificaciones. <span class="notification-time clearfix"></span></span></a>
                                                </li>
                                            </ul>
                                            <?php endif;?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>
        </div>
        <!--Topbar Right End-->
    </header>
    <!--Topbar End Here-->
