<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo isset($title) ? $title : 'Registro de asistencia QR'; ?></title>
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
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome-4.7.0/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/material-design-iconic-font.css">
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
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/images/apple-touch-icon_dexfit.png">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon_dexfit.png" type="image/x-icon">
    <link href="https://davidstutz.de/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.js"></script>
    <script type="text/javascript">
        var grupo = '';
        var pagina_previa = '/';
        var logged_id = "";
    </script>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(217, 65, 29, 0.14), transparent 32%),
                linear-gradient(180deg, #f5f7fb 0%, #e8edf5 100%);
            color: #102a43;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .qr-public-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .qr-public-card {
            width: 100%;
            max-width: 560px;
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(16, 42, 67, 0.14);
            overflow: hidden;
            border: 1px solid rgba(16, 42, 67, 0.08);
        }

        .qr-public-header {
            padding: 18px 20px 14px;
            background: linear-gradient(135deg, #b33014 0%, #d9411d 58%, #ef6b43 100%);
            color: #fff;
        }

        .qr-public-brand {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .qr-public-brand i {
            font-size: 14px;
        }

        .qr-public-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            line-height: 1.15;
        }

        .qr-public-header p {
            margin: 0;
            opacity: 0.92;
            font-size: 15px;
        }

        .qr-public-body {
            padding: 20px;
        }

        .qr-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .qr-meta-item {
            padding: 12px 14px;
            border: 1px solid #d9e2ec;
            border-radius: 16px;
            background: #f8fafc;
        }

        .qr-meta-item span {
            display: block;
            font-size: 12px;
            color: #486581;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .qr-meta-item strong {
            display: block;
            font-size: 16px;
            color: #102a43;
            line-height: 1.25;
        }

        .qr-status-box {
            margin-bottom: 16px;
            padding: 14px 16px;
            border-radius: 16px;
            background: linear-gradient(180deg, #f8fafc 0%, #edf3f8 100%);
            border: 1px solid #cfd8e3;
        }

        .qr-status-box h2 {
            margin: 0;
            font-size: 16px;
        }

        .qr-status-box p {
            margin: 0;
            font-size: 14px;
            color: #334e68;
        }

        .qr-reader-shell {
            display: none;
            border: 1px solid #d9e2ec;
            border-radius: 18px;
            padding: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        #qr-reader {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .qr-reader-title {
            margin: 0 0 10px;
            font-size: 16px;
            text-align: center;
        }

        .qr-reader-status {
            margin: 0 0 12px;
            text-align: center;
            color: #486581;
            font-size: 14px;
        }

        .qr-badge-flotante {
            position: fixed;
            right: 12px;
            left: 12px;
            bottom: 12px;
            z-index: 1050;
            display: none;
            min-width: auto;
            max-width: none;
            padding: 12px 14px;
            border-radius: 14px;
            background: linear-gradient(135deg, #b33014, #d9411d);
            color: #fff;
            box-shadow: 0 14px 32px rgba(217, 65, 29, 0.28);
        }

        .qr-badge-flotante.error {
            background: linear-gradient(135deg, #b91c1c, #ef4444);
            box-shadow: 0 14px 32px rgba(185, 28, 28, 0.28);
        }

        .qr-badge-flotante strong,
        .qr-badge-flotante span {
            display: block;
        }

        .qr-closing {
            display: none;
            margin-top: 16px;
            padding: 14px;
            border-radius: 16px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            text-align: center;
        }

        @media (max-width: 640px) {
            .qr-public-shell {
                padding: 10px;
                align-items: flex-start;
            }

            .qr-public-card {
                max-width: none;
                border-radius: 18px;
            }

            .qr-public-header,
            .qr-public-body {
                padding: 16px;
            }

            .qr-meta {
                grid-template-columns: 1fr;
                gap: 10px;
                margin-bottom: 14px;
            }

            .qr-public-header h1 {
                font-size: 20px;
            }

            .qr-public-brand {
                font-size: 11px;
                padding: 7px 10px;
            }

            .qr-status-box h2 {
                display: none;
            }

            .qr-reader-title {
                display: none;
            }

            #qr-reader {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="qr-public-shell">
        <div class="qr-public-card">
            <div class="qr-public-header">
                <div class="qr-public-brand">
                    <i class="zmdi zmdi-pin-drop"></i>
                    <span>The Help</span>
                </div>
                <h1>Registro de asistencia QR</h1>
            </div>

            <div class="qr-public-body">
                <input type="hidden" id="empresa_id" value="<?php echo intval($sede->empresa_id); ?>">
                <input type="hidden" id="sede_id" value="<?php echo intval($sede->id); ?>">

                <div class="qr-meta">
                    <div class="qr-meta-item">
                        <span>Empresa</span>
                        <strong><?php echo htmlspecialchars($sede->empresa, ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>
                    <div class="qr-meta-item">
                        <span>Sede</span>
                        <strong><?php echo htmlspecialchars($sede->nombre, ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>
                </div>

                <div class="qr-status-box">
                    <p id="qr_public_status_text">Solicitando ubicación para validar que estás dentro del rango de la sede.</p>
                </div>

                <div id="qr_reader_shell" class="qr-reader-shell">
                    <h3 class="qr-reader-title">Escanea el QR del colaborador</h3>
                    <p id="qr_scanner_status" class="qr-reader-status">Inicializando lector...</p>
                    <div id="qr-reader"></div>
                </div>

                <div id="qr_closing_box" class="qr-closing">
                    Registro completado. Esta pantalla intentará cerrarse automáticamente.
                </div>
            </div>
        </div>
    </div>

    <div id="qr_colaborador_badge" class="qr-badge-flotante">
        <strong id="qr_colaborador_badge_nombre"></strong>
        <span id="qr_colaborador_badge_codigo"></span>
        <span id="qr_colaborador_badge_id"></span>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/qrcode.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery-migrate.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jRespond.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/nav.accordion.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/hover.intent.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/hammerjs.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.hammer.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.fitvids.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/scrollup.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/smoothscroll.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.slimscroll.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/velocity.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/smart-resize.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/timepicker.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery-ui-timepicker-addon.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/icheck.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.switch.button.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/sparkline/jquery.sparkline.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/easypie/jquery.easypiechart.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/excanvas.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/jquery.flot.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/curvedLines.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/jquery.flot.time.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/jquery.flot.stack.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/jquery.flot.axislabels.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/jquery.flot.resize.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/jquery.flot.tooltip.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/jquery.flot.spline.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/chart/flot/jquery.flot.pie.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/dataTables.responsive.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/dataTables.tableTools.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.2.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/bootbox.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/sweetalert.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.maskedinput.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.validate.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.form.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/j-forms.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.loadmask.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/theme-switcher.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.parsley/dist/parsley.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/apps.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/footable.all.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/tableExport.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.base64.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/sprintf.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jspdf.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/base64.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/jquery.webcam.min.js?j=<?php echo uniqid(); ?>"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/main.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/photobooth_min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/sha.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lib/emojionearea.min.js"></script>
    <script src="https://davidstutz.de/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.0/html2canvas.min.js" integrity="sha512-UcDEnmFoMh0dYHu0wGsf5SKB7z7i5j3GuXHCnb3i4s44hfctoLihr896bxM0zL7jGkcHQXXrJsFIL62ehtd6yQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/autoload/public/QrAccesoSede.js?p=<?php echo uniqid(); ?>"></script>
</body>

</html>
