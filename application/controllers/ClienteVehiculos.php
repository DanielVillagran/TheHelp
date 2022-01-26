<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;

require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';
// require_once BASEPATH . '../application/models/common_library.php';

class ClienteVehiculos extends ANT_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	function view($id)
	{
		$data['title'] = 'Ver Vehiculo';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		//$data['datos_vehiculo'] = $this->get_info_Vehiculos($id);
		$data['tipos_servicios'] = Tipos_Servicios_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['view'] = 'forms/ClienteVehiculos';
		$this->_load_views('ClienteVehiculo/add', $data);
	}
	function get_info_Vehiculos()
	{
		$post = $this->input->post();
		$data = Vehiculos_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$data->head = "<tr>
				<th>Tipo de Servicio</th>
				<th>Fecha</th>
				<th>Descripción</th>
				</tr>";
		$aux = Servicios_Model::get_grid_info_by_vehiculo($post['id']);
		$data->tableData = '';
		if ($aux) {
			foreach ($aux as $a) {
				$data->tableData .= '<tr>
					
						<td>' . $a['tipoServicio'] . '</td>
						<td>' . $a['createdAt'] . '</td>
						<td>' . $a['descripcion'] . '</td>
					</tr>';
			}
		} else {
			$data->tableData = '<tr><td colspan="5">Perdon, no hemos encontrado nada.</td></tr>';
		}
		$data->head2 = "<tr>
				<th>Tipo de Servicio</th>
				<th>Fecha</th>
				<th>Descripción</th>
				<th>Status</th>
				</tr>";
		$aux = Tickets_Model::get_grid_info_by_vehiculo($post['id']);
		$data->tableData2 = '';
		if ($aux) {
			foreach ($aux as $a) {
				$data->tableData2 .= '<tr>
					
						<td>' . $a['tipoServicio'] . '</td>
						<td>' . $a['createdAt'] . '</td>
						<td>' . $a['descripcion'] . '</td>
						<td>' . $a['status'] . '</td>
					</tr>';
			}
		} else {
			$data->tableData2 = '<tr><td colspan="5">Perdon, no hemos encontrado nada.</td></tr>';
		}
		$this->output_json($data);
	}

	function save_info()
	{
		$post = $this->input->post('users');

		if ($post['id'] == 0) {
			$result = Vehiculos_Model::Insert($post);
		} else {

			$result = Vehiculos_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function save_ticket()
	{
		$post = $this->input->post('tickets');

		if ($post['id'] == 0) {
			$result = Tickets_Model::Insert($post);
			$data = Vehiculos_Model::Load(array(
				'select' => "*",
				'where' => 'id=' . $post['vehiculoId'],
				'result' => '1row'
			));
			$datos = array('vehiculo' => $data->marca . ' - ' . $data->modelo . ' - ' . $data->serie, 'motivo' => $post['descripcion'], 'correo' => 'odvillagrana@gmail.com');
			$this->send_email($datos);
		} else {

			$result = Tickets_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
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
        <div style="font-size:1px;line-height:30px"> </div><img align="center" alt="Alternate text" border="0" class="center fixedwidth" src="https://crm.lumamaquinaria.com.mx/assets/files/fotos/LogoLM.png" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 150px; display: block;" title="Alternate text" width="150"/>
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
        <p style="font-size: 28px; line-height: 2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 56px; margin: 0; font-weight: 300;"><span style="font-size: 28px;">Ticket de servicio</span></p>
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
        <p style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; mso-line-height-alt: 17px; margin: 0;"><span style="font-size: 14px;">Hola <strong>Luma Maquinaria</strong>, hemos recibido un nuevo ticket de servicio: </span></p>
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
        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;">Vehiculo: <span style="font-size: 18px;">' . $datos['vehiculo'] . '.</span></p>
        </div>
        </div>
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 0px; padding-bottom: 5px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
        <div style="color:#0081ff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:5px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #0081ff; mso-line-height-alt: 14px;">
        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;"><span style="font-size: 18px;">' . $datos['motivo'] . '.</span></p>
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
		$mail->Username = 'lumamaquinaria@hotmail.com';
		$mail->Password = 'Lu2022ma';

		//Recipients
		$mail->setFrom('donotreply@lumamaquinaria.com.mx', 'Luma Maquinaria');
		$mail->Subject = 'Nuevo Ticket de Servicio. Vehiculo:' . $datos['vehiculo'];
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
