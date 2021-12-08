<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;

require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';

// require_once BASEPATH . '../application/models/common_library.php';

class Citas extends ANT_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        if (!$this->tank_auth->is_logged_in()) {
            redirect('/');
            return;
        }
    }
    public function index()
    {
        $data['title'] = 'Citas';
        $data['view'] = 'grids/Citas';
        $data['styles'] = 'jquery.shuttle';
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('Citas/list', $data);
    }
    public function get_citas_csv()
    {
        $my_file = 'assets/files/citas.csv';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);

        $texto = "Nombre,Teléfono,Email,Servicio,Status\n";
        $aux = Departamentos_Servicios_Citas_Model::get_grid_info($this->tank_auth->get_user_type());

        if ($aux) {
            foreach ($aux as $a) {
                if ($a['agendado'] == "") {

                    $status = 'No agendada';
                } else {
                    $status = $a['agendado'];

                }
                $texto .= $a['nombre'] . ',' . $a['telefono'] . ',' . $a['email'] . "," .
                    $a['departamento'] . ' - ' . $a['servicio'] . "," . $status . "\n";
            }
        }
        fwrite($handle, $texto);
        fclose($handle);
        header("Content-type: text/csv");
        header("Content-disposition: attachment; filename = Reporte de Citas.csv");
        readfile($my_file);
    }

    public function get_Citas()
    {
        $aux = Departamentos_Servicios_Citas_Model::get_grid_info($this->tank_auth->get_user_type());
        $data['head'] = "<tr><th>Nombre</th>
		<th>Teléfono</th>
		<th>Email</th>
		<th>Servicio</th>
		<th>Status</th>
		<th class='th-editar'>Acciones</th>
		</tr>";
        $data['table'] = '';
        if ($aux) {
            foreach ($aux as $a) {
                if ($a['agendado'] == "") {
                    $botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-calendar-check-o"></i></button>';
                    $status = '<span class="badge badge-danger">No agendada</span>';
                } else {
                    $status = '<span class="badge badge-denuncia">' . $a['agendado'] . '</span>';
                    $botones = "";
                }
                $data['table'] .= '<tr>
				<td>' . $a['nombre'] . '</td>
			<td>' . $a['telefono'] . '</td>
			<td>' . $a['email'] . '</td>
			<td>' . $a['departamento'] . ' - ' . $a['servicio'] . '</td>
			<td>' . $status . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
            }
        } else {
            $data['table'] = '<tr><td colspan="5">Perdon, no hemos encontrado nada.</td></tr>';
        }

        $this->output_json($data);
    }
    public function agendar_cita()
    {
        $post = $this->input->post();
        $data = Departamentos_Servicios_Citas_Agenda_Model::Insert(array('departamentos_servicios_cita_id' => $post['id'],
            'fecha' => $post['fecha']));
        $options = array(
            'select' => 'departamentos_servicios_citas.*,departamentos_servicios.nombre as servicio,departamentos.nombre as departamento,departamentos_servicios_citas_agenda.fecha as agendado',
            'joinsLeft' => array(
                'departamentos_servicios' => 'departamentos_servicios.id=departamentos_servicios_citas.departamento_servicio_id',
                'departamentos' => 'departamentos_servicios.departamento_id=departamentos.id',
                'departamentos_servicios_citas_agenda' => 'departamentos_servicios_citas_agenda.departamentos_servicios_cita_id=departamentos_servicios_citas.id',
            ),
            'where' => 'departamentos_servicios_citas.id=' . $post['id'],
            'result' => 'array',
        );
        $result = Departamentos_Servicios_Citas_Model::Load($options);
        if ($result) {
            $result = $result[0];
            $arre = explode("-", $post['dia']);
            $arre2 = explode(":", $post['hora']);
            $tiempo = " am";
            if (intval($arre2[0]) > 12) {
                $tiempo = " pm";
                $arre2[0] = "0" . (intval($arre2[0]) - 12);
            }
            $fecha = gmmktime($arre2[0], $arre2[1], 0, $arre[1], $arre[2], $arre[0]);
            setlocale(LC_TIME, 'es_MX.UTF-8');
            $this->enviar_notificacion("Tu cita ha sido agendada.",
                "Tu cita para " . $result['departamento'] . ' - ' . $result['servicio'] . " se ha agendado para el " .
                ucfirst(strftime("%A, %d de %B de %Y", $fecha) . " a las " . $arre2[0] . ":" . $arre2[1] . $tiempo),
                $result['token']);
            $this->send_email(array('nombre' => $result['nombre'],
                'correo' => $result['email'],
                'servicio' => $result['departamento'] . ' - ' . $result['servicio'],
                'hora' => $arre2[0] . ":" . $arre2[1] . $tiempo,
                'dia' => ucfirst(strftime("%A, %d de %B de %Y", $fecha)),
            ));
        }
        $this->output_json($data);

    }
    public function enviar_notificacion($titulo, $body, $to)
    {
        $apikey = "AIzaSyCT2rnP0u1jDZTqJRbFaW1MuxOKubAl-r4";
        $data = array('title' => $titulo,
            'body' => $body,
            'image' => 'https://zumpango.vmcomp.com.mx/assets/images/logo-zumpango-horizontal-color.png',
        );
        $fields = array('to' => $to, 'notification' => $data);
        $headers = array('Authorization:key=' . $apikey, "Content-Type:application/json");
        $url = 'https://fcm.googleapis.com/fcm/send';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        Notificaciones_Model::Insert(array('titulo' => $titulo, 'descripcion' => $body, 'token' => $to));
    }
    public function send_email($datos)
    {

        date_default_timezone_set("America/Mexico_City");
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
        <head>
        <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <meta content="width=device-width" name="viewport"/>
        <!--[if !mso]><!-->
        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
        <!--<![endif]-->
        <title></title>
        <!--[if !mso]><!-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700&display=swap" rel="stylesheet">
        <!--<![endif]-->
        <style type="text/css">
                body {
                    margin: 0;
                    padding: 0;
                }

                table,
                td,
                tr {
                    vertical-align: top;
                    border-collapse: collapse;
                }

                * {
                    line-height: inherit;
                }

                a[x-apple-data-detectors=true] {
                    color: inherit !important;
                    text-decoration: none !important;
                }
            </style>
        <style id="media-query" type="text/css">
                @media (max-width: 520px) {

                    .block-grid,
                    .col {
                        min-width: 320px !important;
                        max-width: 100% !important;
                        display: block !important;
                    }

                    .block-grid {
                        width: 100% !important;
                    }

                    .col {
                        width: 100% !important;
                    }

                    .col>div {
                        margin: 0 auto;
                    }

                    img.fullwidth,
                    img.fullwidthOnMobile {
                        max-width: 100% !important;
                    }

                    .no-stack .col {
                        min-width: 0 !important;
                        display: table-cell !important;
                    }

                    .no-stack.two-up .col {
                        width: 50% !important;
                    }

                    .no-stack .col.num4 {
                        width: 33% !important;
                    }

                    .no-stack .col.num8 {
                        width: 66% !important;
                    }

                    .no-stack .col.num4 {
                        width: 33% !important;
                    }

                    .no-stack .col.num3 {
                        width: 25% !important;
                    }

                    .no-stack .col.num6 {
                        width: 50% !important;
                    }

                    .no-stack .col.num9 {
                        width: 75% !important;
                    }

                    .video-block {
                        max-width: none !important;
                    }

                    .mobile_hide {
                        min-height: 0px;
                        max-height: 0px;
                        max-width: 0px;
                        display: none;
                        overflow: hidden;
                        font-size: 0px;
                    }

                    .desktop_hide {
                        display: block !important;
                        max-height: none !important;
                    }
                }
            </style>
        </head>
        <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f4f4f4;">
        <!--[if IE]><div class="ie-browser"><![endif]-->
        <table bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f4f4f4; width: 100%;" valign="top" width="100%">
        <tbody>
        <tr style="vertical-align: top;" valign="top">
        <td style="word-break: break-word; vertical-align: top;" valign="top">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f4f4f4"><![endif]-->
        <div style="background-color:transparent;">
        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
        <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:transparent;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
        <div style="width:100% !important;">
        <!--[if (!mso)&(!IE)]><!-->
        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
        <!--<![endif]-->
        <div align="center" class="img-container center fixedwidth" style="padding-right: 00px;padding-left: 00px;">
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 00px;padding-left: 00px;" align="center"><![endif]-->
        <div style="font-size:1px;line-height:30px"> </div><img align="center" alt="Alternate text" border="0" class="center fixedwidth" src="https://zumpango.vmcomp.com.mx/assets/images/logo-zumpango-horizontal-color.png" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 150px; display: block;" title="Alternate text" width="150"/>
        <div style="font-size:1px;line-height:30px"> </div>
        <!--[if mso]></td></tr></table><![endif]-->
        </div>
        <!--[if (!mso)&(!IE)]><!-->
        </div>
        <!--<![endif]-->
        </div>
        </div>
        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
        </div>
        </div>
        </div>
        <div style="background-color:transparent;">
        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #0081ff;">
        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#0081ff;background-image:;background-position:top left;background-repeat:no-repeat">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#0081ff"><![endif]-->
        <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#0081ff;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
        <div style="width:100% !important;">
        <!--[if (!mso)&(!IE)]><!-->
        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
        <!--<![endif]-->
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 20px; padding-bottom: 0px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
        <div style="color:#ffffff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:2;padding-top:20px;padding-right:20px;padding-bottom:0px;padding-left:20px;">
        <div style="line-height: 2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #ffffff; mso-line-height-alt: 24px;">
        <p style="font-size: 28px; line-height: 2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 56px; margin: 0; font-weight: 300;"><span style="font-size: 28px;">Cita confirmada</span></p>
        </div>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
        <!--[if (!mso)&(!IE)]><!-->
        </div>
        <!--<![endif]-->
        </div>
        </div>
        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
        </div>
        </div>
        </div>
        <div style="background-color:transparent;">
        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
        <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#ffffff;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
        <div style="width:100% !important;">
        <!--[if (!mso)&(!IE)]><!-->
        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
        <!--<![endif]-->
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 50px; padding-bottom: 20px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
        <div style="color:#555555;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:50px;padding-right:20px;padding-bottom:20px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #555555; mso-line-height-alt: 14px;">
        <p style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; mso-line-height-alt: 17px; margin: 0;"><span style="font-size: 14px;">Hola <strong>' . $datos['nombre'] . '</strong>, tu cita para el servicio ' . $datos['servicio'] . ' se ha agendado para el dia:</span></p>
        </div>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
        <tbody>
        <tr style="vertical-align: top;" valign="top">
        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 30px; padding-left: 10px;" valign="top">
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
        <tbody>
        <tr style="vertical-align: top;" valign="top">
        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 0px; padding-bottom: 5px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
        <div style="color:#0081ff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:5px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #0081ff; mso-line-height-alt: 14px;">
        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;"><span style="font-size: 18px;">' . $datos['dia'] . '.</span></p>
        </div>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 0px; padding-bottom: 0px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
        <div style="color:#000000;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:0px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #000000; mso-line-height-alt: 14px;">
        <p style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: NaNpx; margin: 0;"><strong><span style="font-size: 34px; font-weight: 600;">' . $datos['hora'] . '</span></strong></p>
        </div>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
        <tbody>
        <tr style="vertical-align: top;" valign="top">
        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 30px; padding-right: 10px; padding-bottom: 50px; padding-left: 10px;" valign="top">
        <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
        <tbody>
        <tr style="vertical-align: top;" valign="top">
        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
        <!--[if (!mso)&(!IE)]><!-->
        </div>
        <!--<![endif]-->
        </div>
        </div>
        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
        </div>
        </div>
        </div>
        <div style="background-color:transparent;">
        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
        <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:transparent;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 30px; padding-left: 30px; padding-top:40px; padding-bottom:40px;"><![endif]-->
        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
        <div style="width:100% !important;">
        <!--[if (!mso)&(!IE)]><!-->
        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:40px; padding-bottom:40px; padding-right: 30px; padding-left: 30px;">
        <!--<![endif]-->
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top: 0px; padding-bottom: 0px; font-family: Arial, sans-serif"><![endif]-->
        <div style="color:#555555;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;">
        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
        <p style="text-align: center; line-height: 1.2; word-break: break-word; mso-line-height-alt: NaNpx; margin: 0;">Administración <strong><a href="http://zumpango.gob.mx/" target="_blank" style="text-decoration: none; color: #555555;">Zumpango</a></strong></p>
        </div>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
        <!--[if (!mso)&(!IE)]><!-->
        </div>
        <!--<![endif]-->
        </div>
        </div>
        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
        </div>
        </div>
        </div>
        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
        </td>
        </tr>
        </tbody>
        </table>
        <!--[if (IE)]></div><![endif]-->
        </body>
        </html>';

        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        //$mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->Username = 'odvillagrana@hotmail.com';
        $mail->Password = 'daniel200796';

        //Recipients
        $mail->setFrom('donotreply@zumpango.com', 'Zumpango');
        $mail->Subject = 'Tu cita ha sido agendada.';
        $mail->isHTML(true);
        $mail->Body = $message;
        $mail->AddAddress($datos['correo']);
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            //unlink("uploads/p" . $name . ".pdf");
            //echo "Message has been sent";
        }

    }
}
