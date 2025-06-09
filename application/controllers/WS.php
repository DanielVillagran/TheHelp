<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;

require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';
require APPPATH . 'libraries/openpay/Openpay.php';

// require_once BASEPATH . '../application/models/common_library.php';

class WS extends ANT_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('pdf');
    }
    public function get_departamentos($import = false, $tipo = null)
    {
        $regreso = "";
        $where = "";
        $producto = $this->input->post('product');
        if ($producto != "") {
            $where = " AND nombre like '%" . $producto . "%'";
        }
        $servidor = "https://zumpango.vmcomp.com.mx";
        if ($import) {
            $aux = Departamentos_Model::Load(array(
                'select' => "*",
                'result' => 'array',
                'where' => 'user_type=' . $tipo . ' ' . $where,

            ));
        } else {
            $aux = Departamentos_Model::Load(array(
                'select' => "*",
                'result' => 'array',
                'where' => 'user_type=' . $this->input->post('tipo') . ' ' . $where,
            ));
        }
        $clase = "btn-directorio";
        switch ($this->input->post('tipo')) {
            case 2:
                $clase = "btn-directorio-dif";
                break;
            case 3:
                $clase = "btn-directorio-oda";
                break;
            case 4:
                $clase = "btn-directorio-imc";
                break;
        }
        if ($aux) {
            foreach ($aux as $key) {
                $regreso .= '<div class="d-item-directorio">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-8">
                            <div class="clearfix item-directorio">
                                <img src="' . $servidor . "/assets/" . $key["logo"] . '" alt="">
                                <p class="t1 one-line">' . $key["nombre"] . '</p>
                                <p class="t2 num-directorio" data-toggle="tooltip" data-placement="bottom" title="Copiado"><img src="images/icons/phone.svg" alt=""><span>' . $key["telefono"] . '</span></p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                            <div class="d-btn-directorio">
                                <a class="btn ' . $clase . '" href="tel:' . $key["telefono"] . '" role="button">Llamar</a>
                            </div>
                        </div>

                    </div>
                </div>';
            }
        }
        if ($import) {
            return $regreso;
        } else {
            $this->output_json($regreso);
        }
    }
    public function get_noticias($import = false, $tipo = null)
    {
        $regreso = "";
        $servidor = "https://zumpango.vmcomp.com.mx";
        if ($import) {
            $aux = Noticias_Model::Load(array(
                'select' => "*",
                'result' => 'array',
                'sortBy' => "id",
                'sortDirection' => 'DESC',
                'where' => 'user_type=' . $tipo,
            ));
        } else {
            $aux = Noticias_Model::Load(array(
                'select' => "*",
                'result' => 'array',
                'sortBy' => "id",
                'sortDirection' => 'DESC',
                'where' => 'user_type=' . $this->input->post('tipo')
            ));
        }
        if ($aux) {
            foreach ($aux as $key) {
                $regreso .= '<div class="d-item-noticias" onclick="window.open(\'' . $key["url"] . '\')">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-4 pr-0">
                            <div style="background-image: url(' . $servidor . "/assets/" . $key["logo"] . ');" class="d-img-noticias">
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-8">
                            <div class="d-info-noticias">
                                <p class="t1 one-line">' . $key["titulo"] . '</p>
                                <p class="t2 three-lines">' . $key["descripcion"] . '</p>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }
        if ($import) {
            return $regreso;
        } else {
            $this->output_json($regreso);
        }
    }
    public function get_eventos($import = false, $tipo = null)
    {
        $regreso = "";
        $servidor = "https://zumpango.vmcomp.com.mx";
        if ($import) {
            $aux = Eventos_Model::Load(array(
                'select' => "*",
                'result' => 'array',
                'where' => 'status=1 AND user_type=' . $tipo,
            ));
        } else {
            $aux = Eventos_Model::Load(array(
                'select' => "*",
                'result' => 'array',
                'where' => 'status=1 AND user_type=' . $this->input->post('tipo')
            ));
        }
        if ($aux) {
            foreach ($aux as $key) {
                $regreso .= '<div class="d-item-evento" onclick="window.location.href=\'modo-evento.html?id=' . $key['id'] . '\'">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-4 pr-0">
                            <div style="background-image: url(' . $servidor . "/assets/" . $key["logo"] . ');" class="d-img-eventos">
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-8">
                            <div class="d-info-eventos">
                                <p class="t1 one-line">' . $key["nombre"] . '</p>
                                <p class="t2 three-lines">' . $key["descripcion"] . '</p>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }
        if ($import) {
            return $regreso;
        } else {
            $this->output_json($regreso);
        }
    }
    public function get_intereses()
    {
        $regreso = "";
        $servidor = "https://zumpango.vmcomp.com.mx";
        $aux = Intereses_Model::Load(array(
            'select' => "*",
            'result' => 'array'
        ));
        if ($aux) {
            $data['arreglo'] = $aux;
            foreach ($aux as $key) {
                $regreso .= '<div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="check' . $key['id'] . '" onchange="cambiar_valor(' . $key['id'] . ')">
            <label class="custom-control-label" for="check' . $key['id'] . '">' . $key['nombre'] . '</label>
        </div>';
            }
        }
        $data['regreso'] = $regreso;
        $this->output_json($data);
    }
    public function get_servicios($import = false, $tipo = null)
    {
        $regreso = "";
        $where = "";
        $where2 = "";
        $producto = $this->input->post('product');
        if ($producto != "") {
            $where = " AND nombre like '%" . $producto . "%'";
            $where2 = " WHERE nombre like '%" . $producto . "%'";
        }
        $servidor = "https://zumpango.vmcomp.com.mx";
        $elem = "";
        if ($tipo == null) {
            $tipo = 1;
        }
        if ($this->input->post('tipo') !== null && $this->input->post('tipo') != 1) {
            $tipo = $this->input->post('tipo');
            $elem = "";
        }
        $aux = Departamentos_Model::Query("SELECT * from departamentos where id in (SELECT departamento_id from departamentos_servicios " . $where2 . " group by departamento_id) AND user_type=" . $tipo . ' ');
        if ($aux) {
            foreach ($aux as $key) {
                $regreso .= '<div class="d-item-servicios">
                    <div class="row row-item-servicios" data-toggle="collapse" data-target="#collapse' . $key['id'] . '" aria-expanded="false" aria-controls="collapse' . $key['id'] . '">
                        <div class="col-lg-8 col-md-8 col-8">
                            <div class="clearfix item-servicios">
                                <img src="' . $servidor . "/assets/" . $key["logo"] . '" alt="">
                                <p class="t1 one-line">' . $key['nombre'] . '</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-4 pl-0">
                            <div class="d-btn-servicios">
                                <p class="p-ver-servicios">Servicios<img class="down-icon-servicios ml-2" src="images/icons/icon-down.svg" alt=""></p>
                            </div>
                        </div>

                    </div>
                    <div class="collapse d-content-servicios" id="collapse' . $key['id'] . '">
                                    <div class="card card-body">
                                        <div id="accordion' . $key['id'] . '" class="accordion-servicios">';
                $servicios = Departamentos_Model::Query("SELECT * from departamentos_servicios where departamento_id=" . $key['id'] . ' ' . $where);
                foreach ($servicios as $s) {
                    $botones = "";
                    if ($s['citas'] == 1) {
                        $botones .= '<div class="col-lg-6 col-md-6 col-6">
                            <button class="btn btn-lg-cita" type="button" onclick="location.href=\'' . $elem . 'agendar-cita.html?id=' . $s['id'] . '\'">Agendar cita</button>
                            </div>';
                    }
                    if ($s['pagos'] == 1) {
                        $botones .= '<div class="col-lg-6 col-md-6 col-6">
                            <button class="btn btn-lg-pagar" type="button"  onclick="location.href=\'' . $elem . 'resumen-de-pago.html?id=' . $s['id'] . '\'">Pagar</button>
                        </div>';
                    }
                    $regreso .= '<div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-name-servicios collapsed" data-toggle="collapse" data-target="#collapses' . $s['id'] . '" aria-expanded="true" aria-controls="collapses' . $s['id'] . '">
                                    <img class="icon-info-servicios" src="images/icons/icon-information.svg" alt="">' . $s['nombre'] . '
                                </button>
                            </h5>
                        </div>

                        <div id="collapses' . $s['id'] . '" class="collapse" aria-labelledby="headingOne" data-parent="#accordion' . $key['id'] . '">
                            <div class="card-body">
                                <p class="text-info-servicios">' . $s['descripcion'] . '</p>
                                <div class="d-btns-info-services">
                                    <div class="row">
                                        ' . $botones . '

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                $regreso .= '</div>
                    </div>
                </div>


            </div>';
            }
        }
        if ($import) {
            return $regreso;
        } else {
            $this->output_json($regreso);
        }
    }
    public function get_evento()
    {
        $post = $this->input->post();
        $data = Eventos_Model::Load(array(
            'select' => "*",
            'where' => 'id=' . $post['id'],
            'result' => '1row'
        ));
        if ($data) {
            if ($data->tipo == 1) {
                $data->elemento = $this->get_calificar();
            } else if ($data->tipo == 2) {
                $data->elemento = $this->get_form($data->fgoogle);
            } else if ($data->tipo == 3) {
                $data->elemento = $this->get_participar();
            } else if ($data->tipo == 4) {
                $data->elemento = $this->get_encuesta($data->id);
            }
        }
        $this->output_json($data);
    }
    public function get_service()
    {
        $post = $this->input->post();
        $data = Departamentos_Servicios_Model::Load(array(
            'select' => "*",
            'where' => 'id=' . $post['id'],
            'result' => '1row'
        ));

        $this->output_json($data);
    }
    public function get_encuesta($id)
    {
        $aux = Eventos_Preguntas_Model::Load(array(
            'select' => "*",
            'where' => 'evento_id=' . $id,
            'result' => 'array'
        ));
        $data = "";
        if ($aux) {
            $contador = 1;
            $data .= ' <p class="p-calificar" id="calificado" style="display:none;"><img class="mr-3" src="images/icons/icon-participar.svg" alt="">¡Gracias por tu participación!</p>
            <div id="encuestas">';
            foreach ($aux as $key) {
                $data .= '<div class="row row-si-no-1 mb-5">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="d-pregunta-evento">
                    <p class="p-pregunta-evento">' . $contador . '. ' . $key['pregunta'] . '</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-6">
                <button class="btn btn-lg-si btn-encuesta" id="si_' . $key['id'] . '" onclick="si(' . $key['id'] . ')"><img src="images/icons/icon-check-si-black.svg" alt=""></button>
            </div>
            <div class="col-lg-6 col-md-6 col-6">
                <button class="btn btn-lg-no btn-encuesta" id="no_' . $key['id'] . '" onclick="no(' . $key['id'] . ')"><img src="images/icons/icon-close-black.svg" alt=""></button>
            </div>
        </div>';
                $contador++;
            }
            $data .= '<button type="button" onclick="enviar_encuesta()" class="btn btn-lg-green btn-calificar mt-3">ENVIAR</button></div>';
        }

        return $data;
    }
    public function get_participar()
    {
        $data = '<div class="row row-participar">
        <div class="col-lg-12 col-md-12 col-12">
           <p class="p-participar" style="display:none;" id="participando"><img class="mr-3" src="images/icons/icon-participar.svg" alt="">¡Ya estas participando!</p>
            <button class="btn btn-lg-green btn-participar" id="participar" onclick="participar()">PARTICIPAR</button>
        </div>
    </div>';
        return $data;
    }
    public function get_form($form)
    {
        $data = '<div class="row row-form-google">
        <div class="col-lg-12 col-md-12 col-12">

            <div class="d-form-google">
                <iframe src="' . $form . '" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>

            </div>
        </div>
    </div>';
        return $data;
    }
    public function get_calificar()
    {
        $data = '<div class="row row-form-calificar">
        <div class="col-lg-12 col-md-12 col-12">
            <p class="p-calificar" id="calificado" style="display:none;"><img class="mr-3" src="images/icons/icon-participar.svg" alt="">¡Gracias por tu calificación!</p>
            <div class="d-calificacion">
                <p class="p-calificacion">Califica el evento</p>
                <fieldset class="rating star mt-3">
                    <input type="radio" onclick="calificar(5)" id="field6_star5" name="rating2" class="radio-rate" value="5" /><label class="full" for="field6_star5"></label>
                    <input type="radio" onclick="calificar(4)" id="field6_star4" name="rating2" class="radio-rate" value="4" /><label class="full" for="field6_star4"></label>
                    <input type="radio" onclick="calificar(3)" id="field6_star3" name="rating2" class="radio-rate" value="3" /><label class="full" for="field6_star3"></label>
                    <input type="radio" onclick="calificar(2)" id="field6_star2" name="rating2" class="radio-rate" value="2" /><label class="full" for="field6_star2"></label>
                    <input type="radio" onclick="calificar(1)" id="field6_star1" name="rating2" class="radio-rate" value="1" /><label class="full" for="field6_star1"></label>
                </fieldset>
            </div>


            <div class="d-form-calificacion mt-4" id="div_comentario" style="display:none;">
                <form action="" class="form-calificacion">
                    <div class="form-group">
                        <textarea class="form-control input-form-gray-area pt-2" id="comentario" rows="4" placeholder="¿Que te parecio el evento?"></textarea>
                    </div>
                    <button type="button" class="btn btn-lg-green btn-calificar mt-3" onclick="enviar()">ENVIAR</button>
                </form>
            </div>

        </div>

    </div>';
        return $data;
    }
    public function get_colonias()
    {
        $data['colonias'] = Colonias_Model::get_select();
        $this->output_json($data);
    }
    public function add_cita()
    {
        $post = $this->input->post();
        $data = Departamentos_Servicios_Citas_Model::Insert($post);
        $options = array(
            'select' => 'departamentos_servicios.nombre as servicio,departamentos.nombre as departamento,departamentos.user_type',
            'joinsLeft' => array(
                'departamentos' => 'departamentos_servicios.departamento_id=departamentos.id',

            ),
            'where' => 'departamentos_servicios.id=' . $post['departamento_servicio_id'],
            'result' => 'array',
        );
        $result = Departamentos_Servicios_Model::Load($options);
        if ($result) {
            $result = $result[0];
            $this->send_email_cutas(array(
                'nombre' => $post['nombre'],
                'correo' => $post['email'],
                'servicio' => $result['servicio'],
                'departamento' => $result['departamento'],
                'user_type' => $result['user_type'],
            ));
        }
        $this->output_json($data);
    }
    public function add_participacion()
    {
        $post = $this->input->post();
        $post = $this->input->post();
        $data = Eventos_Participaciones_Model::Insert(array(
            'evento_id' => $post['id'],
            'number' => $post['telefono'],
            'created' => date("Y-m-d H:i:s"),
        ));
        $this->output_json($data);
    }
    public function add_calificacion()
    {
        $post = $this->input->post();
        $data = Eventos_Calificaciones_Model::Insert(array(
            'evento_id' => $post['id'],
            'calificacion' => $post['calif'],
            'comentario' => $post['comentario'],
            'created' => date("Y-m-d H:i:s"),

        ));
        $this->output_json($data);
    }
    public function get_tipos_denuncias()
    {
        $data = "";
        $aux = Tipos_Denuncias_Model::Load(array(
            'select' => "*",
            'result' => 'array'
        ));
        foreach ($aux as $key) {
            $data .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
        }
        $this->output_json($data);
    }
    public function upload_image()
    {
        $new_image_name = urldecode($_FILES["file"]["name"]) . uniqid() . ".jpg";
        move_uploaded_file($_FILES["file"]["tmp_name"], "assets/files/denuncias/" . $new_image_name);
        $this->output_json($new_image_name);
    }
    public function add_denuncia()
    {
        $post = $this->input->post();
        $data = Denuncias_Model::Insert($post);
        $this->output_json($data);
    }
    public function send_encuesta()
    {
        $post = $this->input->post();
        foreach ($post['respuestas'] as $key) {
            $data = Eventos_Preguntas_Respuestas_Model::Insert(array(
                'evento_pregunta_id' => $key['id'],
                'respuesta' => $key['respuesta'],
                'created' => date("Y-m-d H:i:s"),

            ));
        }

        $this->output_json($data);
    }
    public function enviar_notificacion()
    {

        $post = $this->input->post();
        $fields = array(
            'to' => 'ExponentPushToken[YefkosJwCnAf7ltWl6MRZH]',
            'body' => "Pruebas",
            'sound' => 'default',
            'title' => "Prueba de titulo",
            'channelId' => 'default',
        );
        //ExponentPushToken[w3-h1KDZJwKTbq2r3ctK_B]
        //ExponentPushToken[YefkosJwCnAf7ltWl6MRZH]
        $headers = array(
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://exp.host/--/api/v2/push/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        //Notificaciones_Model::Insert(array('titulo' => $titulo, 'descripcion' => $body));
        //$this->output_json($result);

        //header("Location: notificaciones.php");
    }
    public function proccess_card_pay()
    {
        Openpay::setProductionMode(false);
        $identificador = "m70gtw7bctazhllgfezs";
        $openpay = Openpay::getInstance(
            $identificador,
            'sk_9dd56acc5cf842fb9ee13c4c173537b3'
        );
        $customer = array(
            'name' => $_POST['nombre_real'],
            'phone_number' => "",
            'email' => $_POST['email']
        );
        $chargeData = array(
            'method' => 'card',
            'source_id' => $_POST["token_id"],
            'amount' => (float) $_POST["amount"],
            'description' => $_POST["description"],
            'device_session_id' => $_POST["deviceIdHiddenFieldName"],
            'customer' => $customer,
        );
        $charge = false;
        $charge = $openpay->charges->create($chargeData);
        if ($charge) {
            Departamentos_Servicios_Pagos_Model::Insert(array(
                'nombre' => $_POST['nombre_real'],
                'email' => $_POST['email'],
                'token' => $_POST['token'],
                'status' => '1',
                'departamento_servicio_id' => $_POST['servicio']
            ));
            $options = array(
                'select' => 'departamentos_servicios.nombre as servicio,departamentos.nombre as departamento,departamentos.user_type,departamentos_servicios_pagos.token',
                'joinsLeft' => array(
                    'departamentos' => 'departamentos_servicios.departamento_id=departamentos.id',
                    'departamentos_servicios_pagos' => 'departamentos_servicios_pagos.departamento_servicio_id=departamentos_servicios.id',

                ),
                'where' => 'departamentos_servicios.id=' . $_POST['servicio'],
                'result' => 'array',
            );
            $result = Departamentos_Servicios_Model::Load($options);
            if ($result) {
                $result = $result[0];
                $this->enviar_notificacion_pago(
                    "Tu pago ha sido acreditado.",
                    "Tu pago para " . $result['departamento'] . ' - ' . $result['servicio'] . " ha sido acreditado con exito. ",
                    $result['token']
                );
                $this->send_email(array(
                    'nombre' => $_POST['nombre_real'],
                    'correo' => $_POST['email'],
                    'servicio' => $result['servicio'],
                    'departamento' => $result['departamento'],
                    'precio' => number_format($_POST["amount"], 2, '.', ','),
                ));
                $this->send_email_pago(array(
                    'nombre' => $_POST['nombre_real'],
                    'correo' => $_POST['email'],
                    'servicio' => $result['servicio'],
                    'departamento' => $result['departamento'],
                    'precio' => number_format($_POST["amount"], 2, '.', ','),
                    'user_type' => $result['user_type'],
                ));
            }
        }
        $this->output_json($charge);
    }
    public function enviar_notificacion_pago($titulo, $body, $to)
    {
        $apikey = "AIzaSyCT2rnP0u1jDZTqJRbFaW1MuxOKubAl-r4";
        $data = array(
            'title' => $titulo,
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
        <p style="font-size: 28px; line-height: 2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 56px; margin: 0; font-weight: 300;"><span style="font-size: 28px;">Pago acreditado</span></p>
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
        <p style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; mso-line-height-alt: 17px; margin: 0;"><span style="font-size: 14px;">Hola <strong>' . $datos['nombre'] . '</strong>, tu pago de servicio se ha acreditado correctamente.</span></p>
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
        <div style="color:#0081ff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #0081ff; mso-line-height-alt: 14px;">
        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;"><span style="font-size: 14px;"><span style="color: #555555;1">' . $datos['departamento'] . ' > </span>' . $datos['servicio'] . '</span></p>
        </div>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 0px; padding-bottom: 0px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
        <div style="color:#000000;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:0px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #000000; mso-line-height-alt: 14px;">
        <p style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: NaNpx; margin: 0;"><strong><span style="font-size: 30px; font-weight: 500;">$' . $datos['precio'] . ' MXN</span></strong></p>
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
        $mail->Subject = 'Tu pago ha sido acreditado.';
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
    public function send_email_pago($datos)
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
        <p style="font-size: 28px; line-height: 2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 56px; margin: 0; font-weight: 300;"><span style="font-size: 28px;">Pago acreditado</span></p>
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
        <p style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; mso-line-height-alt: 17px; margin: 0;"><span style="font-size: 14px;">El usuario <strong>' . $datos['nombre'] . '</strong>, ha pagado el siguiente servicio.</span></p>
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
        <div style="color:#0081ff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #0081ff; mso-line-height-alt: 14px;">
        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;"><span style="font-size: 14px;"><span style="color: #555555;1">' . $datos['departamento'] . ' > </span>' . $datos['servicio'] . '</span></p>
        </div>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 0px; padding-bottom: 0px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
        <div style="color:#000000;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:0px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #000000; mso-line-height-alt: 14px;">
        <p style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: NaNpx; margin: 0;"><strong><span style="font-size: 30px; font-weight: 500;">$' . $datos['precio'] . ' MXN</span></strong></p>
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
        $mail->Subject = 'Pago recibido.';
        $mail->isHTML(true);
        $mail->Body = $message;
        $aux = Users_Model::Query("SELECT * from users_user where user_type=" . $datos['user_type']);
        if ($aux) {
            foreach ($aux as $key) {
                $this->send_firebase_notification($key['token'], 'Pago recibido.');
                $mail->AddAddress($key['user_name']);
            }
        }
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            //unlink("uploads/p" . $name . ".pdf");
            //echo "Message has been sent";
        }
    }
    public function send_email_cutas($datos)
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
        <p style="font-size: 28px; line-height: 2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 56px; margin: 0; font-weight: 300;"><span style="font-size: 28px;">Cita Solicitada</span></p>
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
        <p style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; mso-line-height-alt: 17px; margin: 0;"><span style="font-size: 14px;">El usuario <strong>' . $datos['nombre'] . '</strong>, ha solicitado una cita para el siguiente servicio.</span></p>
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
        <div style="color:#0081ff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #0081ff; mso-line-height-alt: 14px;">
        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;"><span style="font-size: 14px;"><span style="color: #555555;1">' . $datos['departamento'] . ' > </span>' . $datos['servicio'] . '</span></p>
        </div>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 0px; padding-bottom: 0px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
        <div style="color:#000000;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:0px;padding-left:20px;">
        <div style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #000000; mso-line-height-alt: 14px;">
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
        $mail->Subject = 'Cita Solicitada.';
        $mail->isHTML(true);
        $mail->Body = $message;
        $aux = Users_Model::Query("SELECT * from users_user where user_type=" . $datos['user_type']);
        if ($aux) {
            foreach ($aux as $key) {
                $this->send_firebase_notification($key['token'], 'Cita Solicitada.');
                $mail->AddAddress($key['user_name']);
            }
        }
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            //unlink("uploads/p" . $name . ".pdf");
            //echo "Message has been sent";
        }
    }
    public function proccess_oxxo_pay()
    {
        Openpay::setProductionMode(false);
        $tipo = "store";
        if ($_POST['type'] == 1) {
            $tipo = "store";
            $server = "https://sandbox-dashboard.openpay.mx/paynet-pdf/";
        } else {
            $tipo = "bank_account";
            $server = "https://sandbox-dashboard.openpay.mx/spei-pdf/";
        }
        $identificador = "m70gtw7bctazhllgfezs";
        $openpay = Openpay::getInstance(
            $identificador,
            'sk_9dd56acc5cf842fb9ee13c4c173537b3'
        );

        $customer = array(
            'name' => $_POST['nombre'],
            'phone_number' => "",
            'email' => $_POST['email']
        );
        $chargeData = array(
            'method' => $tipo,
            'amount' => floatval($_POST['amount']),
            'description' => $_POST["description"],
            "customer" => $customer
        );
        $charge = $openpay->charges->create($chargeData);
        $referencia = "";
        if ($_POST['type'] == 1) {
            $data['url_recibo'] = $server . "/" . $identificador . "/" . $charge->payment_method->reference;
            $referencia = $charge->payment_method->reference;
        } else {
            $data['url_recibo'] = $server . "/" . $identificador . "/" . $charge->id;
            $referencia = $charge->id;
        }

        $nombre = $_POST['nombre'];
        $correo = $_POST['email'];
        date_default_timezone_set("America/Mexico_City");
        $message = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <center>
    <img style="width: 40%;" src="https://i.imgur.com/iBCLjxm.png">
    <div style="margin-top: 25px;">
        <p style="font-size: 40px;">Hola, ' . $_POST['nombre'] . '</p>
        <p style="font-size: 26px; color: rgba(0,0,0,.5);" margin-top: -18px;> Anexamos tu comprobante de referencia con fecha ' . date("d-m-Y h:i a") . '.</p>
        <p style="font-size: 14px; color: rgba(0,0,0,.5); margin-top: 20px;">Gracias por usar la aplicación de Zumpango.</p>
        </div>
    </center>
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
        $mail->Subject = 'Recibo de Compra.';
        $mail->isHTML(true);

        $mail->AddAttachment($data['url_recibo']);
        $mail->Body = $message;
        $mail->AddAddress($correo);
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        Departamentos_Servicios_Pagos_Model::Insert(array(
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'token' => $_POST['token'],
            'departamento_servicio_id' => $_POST['servicio']
        ));
        $this->output_json($data);
    }
    public function get_colonia()
    {
        $data = 0;
        $aux = Colonias_Model::Load(array(
            'select' => "*",
            'result' => 'array',
            'where' => "id=" . $this->input->post('colonia')
        ));
        if ($aux) {
            $data = $aux[0];
        }
        $this->output_json($data);
    }
    public function get_notis($import = false, $informacion = null)
    {
        $data = 0;
        if ($import) {
            $colonia = $informacion['colonia'];
            $token = $informacion['token'];
        } else {
            $colonia = $this->input->post('colonia');
            $token = $this->input->post('token');
        }
        $where = "";
        if ($colonia != 0) {
            $where = " AND ((colonia=0 AND token is null) OR colonia=" . $colonia . " OR token='" . $token . "')";
        } else {
            $where = " AND ((colonia=0 AND token is null) OR token='" . $token . "')";
        }
        $aux = Notificaciones_Model::Load(array(
            'select' => "*",
            'result' => 'array',
            'where' => "created>='" . date('Y-m-d H:i:s', strtotime('-1 days')) . "' " . $where
        ));
        if ($aux) {
            $data = sizeof($aux);
        }
        if ($import) {
            return $data;
        } else {
            $this->output_json($data);
        }
    }
    public function get_noti_list($import = false, $informacion = null)
    {
        $data = "";
        $colonia = 0;
        $token = "";
        if ($import) {
            $colonia = $informacion['colonia'];
            $token = $informacion['token'];
        } else {
            $colonia = $this->input->post('colonia');
            $token = $this->input->post('token');
        }
        $where = "";
        if ($colonia != 0) {
            $where = " AND ((colonia=0 AND notificaciones.token is null) OR colonia=" . $colonia . " OR notificaciones.token='" . $token . "')";
        } else {
            $where = " AND ((colonia=0 AND notificaciones.token is null) OR notificaciones.token='" . $token . "')";
        }
        $aux = Notificaciones_Model::Load(array(
            'select' => "notificaciones.*, readed_notifications.id as has",
            'joinsLeft' => array('readed_notifications' => "readed_notifications.token='$token' AND readed_notifications.notificacion_id=notificaciones.id"),
            'result' => 'array',

            'sortBy' => 'notificaciones.id',
            'sortDirection' => 'desc',
            'where' => "created>='" . date('Y-m-d H:i:s', strtotime('-1 days')) . "' " . $where
        ));
        if ($aux) {
            foreach ($aux as $key) {
                $imagen = "images/noticias/5.jpg";
                $funcion = "";
                $numero = "inactive";
                if ($key['imagen'] != "") {
                    $imagen = 'https://zumpango.vmcomp.com.mx/' . $key['imagen'];
                }
                if ($key['has'] == null) {
                    $numero = "active";
                    $funcion = 'onclick="marcar_leida(' . $key['id'] . ')"';
                }
                if ($key['url'] != "") {
                    if ($key['has'] == null) {
                        $funcion = 'onclick=" marcar_leida(' . $key['id'] . '); window.open(\'' . $key['url'] . '\');"';
                    } else {
                        $funcion = 'onclick="window.open(\'' . $key['url'] . '\')"';
                    }
                }

                //$funcion = 'onclick="marcar_leida('+$key['id']+')';
                $data .= '<div class="d-item-noti ' . $numero . '" ' . $funcion . ' >
            <p class="t1">' . $key['titulo'] . '</p>
            <span class="more">
            ' . $key['descripcion'] . '
            </span>
        </div>';
            }
        }
        if ($import) {
            return $data;
        } else {
            $this->output_json($data);
        }
    }
    public function send_firebase_notification($to, $texto)
    {
        $apikey = "AIzaSyCT2rnP0u1jDZTqJRbFaW1MuxOKubAl-r4";
        $data = array(
            'title' => 'Zumpango Web App',
            'body' => $texto,
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
    }
    public function get_all_logos()
    {
        $data['l6'] = $this->tank_auth->get_logos(6);
        $data['l7'] = $this->tank_auth->get_logos(7);
        $data['l8'] = $this->tank_auth->get_logos(8);
        $data['l9'] = $this->tank_auth->get_logos(9);
        $data['l10'] = $this->tank_auth->get_logos(10);
        $data['l11'] = $this->tank_auth->get_logos(11);
        $data['l12'] = $this->tank_auth->get_logos(12);

        //$data['servicios'] = $this->get_all_service_data();
        $this->output_json($data);
    }
    public function update_catalogos()
    {
        $post = $this->input->post();
        $data['deps_1'] = $this->get_departamentos(true, 1);
        $data['noti'] = $this->get_notis(true, array('colonia' => $post['colonia'], 'token' => $post['token']));
        $data['noti_list'] = $this->get_noti_list(true, array('colonia' => $post['colonia'], 'token' => $post['token']));
        $data['noticias_1'] = $this->get_noticias(true, 1);
        $data['eventos_1'] = $this->get_eventos(true, 1);
        $data['servicios_1'] = $this->get_servicios(true, 1);
        $data['deps_2'] = $this->get_departamentos(true, 2);
        $data['noticias_2'] = $this->get_noticias(true, 2);
        $data['eventos_2'] = $this->get_eventos(true, 2);
        $data['servicios_2'] = $this->get_servicios(true, 2);
        $data['deps_3'] = $this->get_departamentos(true, 3);
        $data['noticias_3'] = $this->get_noticias(true, 3);
        $data['eventos_3'] = $this->get_eventos(true, 3);
        $data['servicios_3'] = $this->get_servicios(true, 4);
        $data['deps_4'] = $this->get_departamentos(true, 4);
        $data['noticias_4'] = $this->get_noticias(true, 4);
        $data['eventos_4'] = $this->get_eventos(true, 4);
        $data['servicios_4'] = $this->get_servicios(true, 4);
        //$data['servicios'] = $this->get_all_service_data();
        $this->output_json($data);
    }
    public function leer_notificacion()
    {
        $notificacion = $this->input->post('id');
        $token = $this->input->post('token');
        $result = Readed_Notifications_Model::Insert(array('token' => $token, 'notificacion_id' => $notificacion));
        $this->output_json($result);
    }
    public function informacion()
    {
        $archivo = fopen('assets/credenciales.csv', "r");
        $linea = 0;
        $contador = 0;

        while (($datos = fgetcsv($archivo, ",")) == true) {
            $datos = array_map("utf8_encode", $datos);
            if ($linea > 1 && $datos[0] != "") {
                $clave = $datos[0];
                $nombre = $datos[1];
                $puesto = $datos[2];
                $area = $datos[3];
                $fabricante = $datos[4];
                $cert = $datos[5];
                $inicio = $datos[6];
                $fin = $datos[7];
                $aux = Users_Model::Query("SELECT * FROM CasasCertificadoras where Nombre='" . $fabricante . "'");
                if ($aux) {
                    $fabricanteId = $aux[0]['Id'];
                } else {
                    $aux = Users_Model::Query("INSERT INTO CasasCertificadoras
                    (Nombre)
                    VALUES
                    ('" . $fabricante . "')");
                    $aux = Users_Model::Query("SELECT * FROM CasasCertificadoras where Nombre='" . $fabricante . "'");
                    $fabricanteId = $aux[0]['Id'];
                }
                $aux = Users_Model::Query("SELECT * FROM AreasCertificacion where Nombre='" . $area . "'");
                if ($aux) {
                    $areaId = $aux[0]['Id'];
                } else {
                    $aux = Users_Model::Query("INSERT INTO AreasCertificacion
                    (Nombre)
                    VALUES
                    ('" . $area . "')");
                    $aux = Users_Model::Query("SELECT * FROM AreasCertificacion where Nombre='" . $area . "'");
                    $areaId = $aux[0]['Id'];
                }
                $encontreDocumento = "";
                $nombreArchivo = $datos[9];
                if (file_exists("assets/Credenciales/" . $nombreArchivo . ".pdf")) {
                    $encontreDocumento = "assets/Credenciales/" . $nombreArchivo . ".pdf";
                } else {
                    if (file_exists("assets/Credenciales/" . $nombreArchivo . ".png")) {
                        $encontreDocumento = "assets/Credenciales/" . $nombreArchivo . ".png";
                    } else {
                        if (file_exists("assets/Credenciales/" . $fabricante . "/" . $nombreArchivo . ".pdf")) {
                            $encontreDocumento = "assets/Credenciales/" . $fabricante . "/" . $nombreArchivo . ".pdf";
                        } else {
                            if (file_exists("assets/Credenciales/" . $fabricante . "/" . $nombreArchivo . ".png")) {
                                $encontreDocumento = "assets/Credenciales/" . $fabricante . "/" . $nombreArchivo . ".png";
                            } else {
                            }
                        }
                    }
                }
                if ($encontreDocumento == "" && $nombreArchivo != "" && $nombreArchivo != "No aplica") {
                    var_dump($nombreArchivo);
                    $contador++;
                }
                //var_dump($encontreDocumento);
                $archivoEnviar = "";
                $aux = Users_Model::Query("SELECT id
                FROM [ODS_Catalogs].[dbo].[MGA_PlazasMH]
                where empleado=" . $clave . " and '" . $nombre . "' like paterno+' '+materno+' '+nombre");
                if ($aux) {
                    $clave = $aux[0]['id'];
                    if ($inicio) {

                        $inicio = explode("/", $inicio)[2] . "-" . explode("/", $inicio)[1] . "-" . explode("/", $inicio)[0] . "T00:00:00.000Z";
                    }
                    if ($fin) {

                        $fin = explode("/", $fin)[2] . "-" . explode("/", $fin)[1] . "-" . explode("/", $fin)[0] . "T00:00:00.000Z";
                    }
                    $obj = array(
                        'Id' => 0,
                        'Nombre' => $cert,
                        'AreaCertificacionId' => $areaId,
                        'FechaInicio' => $inicio,
                        'FechaVencimiento' => $fin,
                        'CasaCertificadoraId' => $fabricanteId,
                        'UserId' => $clave
                    );
                    if ($encontreDocumento !== "") {
                        $filenames = array($encontreDocumento);
                        //$obj['file'] =  new CURLFile($encontreDocumento);
                    }
                    $url = "http://localhost:17391/api/Certifications";
                    $files = array();
                    foreach ($filenames as $f) {
                        $files[$f] = file_get_contents($f);
                    }

                    $curl = curl_init();

                    $url_data = http_build_query($obj);

                    $boundary = uniqid();
                    $delimiter = '-------------' . $boundary;

                    $post_data = $this->build_data_files($boundary, $obj, $files);


                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => $post_data,
                        CURLOPT_HTTPHEADER => array(
                            //"Authorization: Bearer $TOKEN",
                            "Content-Type: multipart/form-data; boundary=" . $delimiter,
                            "Content-Length: " . strlen($post_data)

                        ),


                    ));


                    //
                    $response = curl_exec($curl);
                    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);
                    var_dump($response);
                    var_dump($status);
                } else {
                    var_dump($linea);
                }
            }
            $linea++;
        }
    }
    public function get_data_from_puesto()
    {
        $archivo = fopen('assets/puestos.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";

        while (($datos = fgetcsv($archivo, ",")) == true) {
            $datos = array_map("utf8_encode", $datos);
            if ($linea > 1 && $datos[0] != "") {
                $puesto = $datos[0];
                $jefe = $datos[1];

                $aux = Users_Model::Query("SELECT id FROM [ODS_Catalogs].[dbo].[MGA_PlazasMH] WHERE 
                paterno+' '+materno+' '+nombre = '" . $jefe . "'
                AND id != 0");
                if ($aux) {
                    $jefe_id = $aux[0]['id'];
                    $aux = Users_Model::Query("SELECT cve_puesto FROM [ODS_Catalogs].[dbo].[MGA_PlazasMH] WHERE 
                   descripcion_puesto = '" . $puesto . "'
                    AND id != 0");
                    //var_dump($jefe_id);
                    if ($aux) {
                        $puesto_id = $aux[0]['cve_puesto'];
                        //var_dump($puesto_id);
                        $query .= "INSERT INTO [dbo].[UsuariosAutorizadores]
                        ([ColaboradorID]
                        ,[CvePuesto])
                  VALUES
                        (" . $jefe_id . "
                        ," . $puesto_id . ");";
                    }
                }
            } else {
                var_dump($linea);
            }

            $linea++;
        }
        var_dump($query);
    }
    public function update_matriz()
    {
        $archivo = fopen('assets/matriz.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";

        while (($datos = fgetcsv($archivo, ",")) == true) {
            $datos = array_map("utf8_encode", $datos);
            if ($linea > 1 && $datos[1] != "" && $datos[0] != "") {
                $colaborador = $datos[0];
                $jefe = $datos[1];

                $aux = Users_Model::Query("SELECT c.* FROM [ODS_Catalogs].[dbo].[MGA_PlazasMH] as m
                INNER JOIN GestionDesempenoUnificada.dbo.Colaboradores as 
                c on c.Id_MGA_PlazasMH=m.id
                INNER JOIN GestionDesempenoUnificada.dbo.ColaboradorPeriodo as CP
                ON CP.ColaboradorId=C.ID  and CP.PeriodoId=76
                WHERE  m.plaza=" . $colaborador . " AND m.id != 0 and inMatriz=0;");
                if ($aux) {
                    $colaboradordata = $aux[0];

                    $contador++;
                    $query .= "UPDATE ColaboradorPeriodo set inMatriz=1 WHERE ColaboradorID=" .
                        $colaboradordata['ID'] . " AND PeriodoId=76;";
                    $query .= "UPDATE ColaboradorPeriodo set inMatriz=1 WHERE ColaboradorID=" .
                        $colaboradordata['ID'] . " AND PeriodoId=78;";
                }
            } else {
                var_dump($linea);
            }

            $linea++;
        }
        var_dump($contador);
        var_dump($query);
    }
    public function find_by_file()
    {
        $archivo = fopen('assets/reading.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";

        while (($datos = fgetcsv($archivo, ",")) == true) {
            $datos = array_map("utf8_encode", $datos);
            if ($linea >= 1 && $datos[1] != "" && $datos[0] != "") {
                $colaborador = $datos[0];
                $jefe = $datos[1];

                $aux = Users_Model::Query("SELECT m.Id FROM [ODS_Catalogs].[dbo].[MGA_PlazasMH] as m
                WHERE  m.empleado=" . $colaborador . " AND m.id != 0");
                if ($aux) {
                    $colaboradordata = $aux[0];



                    $query .= "INSERT INTO [dbo].[itgov_ApplicationsUserConfigurations]
                    ([UserId]
                    ,[RoleId]
                    ,[ApplicationId]
                    
                    ,[Online])
              VALUES
                    (" . $colaboradordata['Id'] . "
                    ,21
                    ,22
                    ,0); ";
                }
            } else {
                var_dump($linea);
            }

            $linea++;
        }
        var_dump($query);
    }
    public function construir()
    {
        $archivo = fopen('assets/Construir.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";

        while (($datos = fgetcsv($archivo, ",")) == true) {
            $datos = array_map("utf8_encode", $datos);
            if ($linea > 1 && $datos[1] != "" && $datos[0] != "") {

                $query .= "UPDATE [GestionDesempenoUnificada].[dbo].[ColaboradorPeriodo]
                set NivelCompetencia=" . $datos[1] . "
                where ColaboradorID=" . $datos[0] . "
                ; ";
            } else {
                var_dump($linea);
            }

            $linea++;
        }
        var_dump($query);
    }
    public function updateMetas()
    {
        $archivo = fopen('assets/metas.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";

        while (($datos = fgetcsv($archivo, ",")) == true) {
            $datos = array_map("utf8_encode", $datos);
            if ($linea >= 1) {
                $aux = Users_Model::Query("
                SELECT
                o.Id
                      
                      ,[Meta]
                  FROM [GestionDesempenoUnificada].[dbo].[Objetivo] as o
                  LEFT JOIN [GestionDesempenoUnificada].[dbo].Colaboradores as c on c.Id=o.ColaboradorId
                  LEFT JOIN [GestionDesempenoUnificada].[dbo].Colaboradores as C2 on c2.cve_puesto=o.CvePuesto
                  WHERE (c.Puesto='" . $datos[0] . "' OR c2.Puesto='" . $datos[0] . "') AND o.[Nombre]='" . $datos[1] . "' AND META = 0");
                if ($aux) {
                    foreach ($aux as $key) {
                        $query .= "UPDATE [GestionDesempenoUnificada].[dbo].[Objetivo]
                    set Meta=" . $datos[2] . "
                    where Id=" . $key['Id'] . "
                    ; ";
                    }
                } else {
                    var_dump("No encontre");
                }
            } else {
                var_dump($linea);
            }

            $linea++;
        }
        var_dump($query);
    }

    public function find_by_file_cambios()
    {
        $archivo = fopen('assets/matriz2.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";

        while (($datos = fgetcsv($archivo, ",")) == true) {
            $datos = array_map("utf8_encode", $datos);
            if ($linea >= 1) {


                $aux = Users_Model::Query("SELECT TOP (1000) C.[ID]
                ,[IdCorporativo]
                ,[Username]
                ,[CompaniaBI]
                ,[Dominio]
                ,[IsAdmin]
                ,[Nombre]
                ,[Area]
                ,[estatus]
                ,[Id_MGA_PlazasMH]
                ,[IsObjetivoTemprano]
                ,[Id_Autorizador]
                ,[Nombre_Autorizador]
                ,[Sistema]
                ,[cve_puesto]
                ,[Puesto]
                ,CP.inMatriz
            FROM [GestionDesempenoUnificada].[dbo].[Colaboradores] as C
            INNER JOIN [GestionDesempenoUnificada].[dbo].ColaboradorPeriodo as CP on CP.ColaboradorID=C.ID and CP.PeriodoID=76
            WHERE Id_MGA_PlazasMH=" . $datos[0]);
                if ($aux) {
                    $aux = $aux[0];
                    if ($aux['inMatriz'] == 0) {
                        var_dump($datos[0] . " Esta pero sin matriz");
                    } else {
                    }
                } else {
                    var_dump($datos[0] . " Lit, no esta");
                }
            } else {
                var_dump($linea);
            }

            $linea++;
        }
        var_dump($query);
    }





    function build_data_files($boundary, $fields, $files)
    {
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------' . $boundary;

        foreach ($fields as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
                . $content . $eol;
        }


        foreach ($files as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
                //. 'Content-Type: image/png'.$eol
                . 'Content-Transfer-Encoding: binary' . $eol;

            $data .= $eol;
            $data .= $content . $eol;
        }
        $data .= "--" . $delimiter . "--" . $eol;


        return $data;
    }
    public function find_by_file_permisos()
    {
        $archivo = fopen('assets/permisos.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";

        while (($datos = fgetcsv($archivo, ",")) == true) {
            $colaborador = $datos[0];
            $aux = Users_Model::Query("SELECT  Id
            FROM itgov_ApplicationsUserConfigurations
            WHERE ApplicationId=5 AND UserId=" . intval($colaborador));
            if ($aux) {
            } else {
                $query .= "INSERT INTO [itgov_ApplicationsUserConfigurations]
                    ([UserId]
                    ,[RoleId]
                    ,[ApplicationId]
                    
                    ,[Online])
              VALUES
                    (" . intval($colaborador) . "
                    ,16
                    ,5
                    ,0); ";
            }


            $linea++;
        }
        var_dump($query);
    }
    public function insertMatrizComplementaria()
    {
        $archivo = fopen('assets/matrizC6.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";
        $periodoId = 84;
        $periodoEvaluacion = 127;

        while (($datos = fgetcsv($archivo, ",")) == true) {
            if ($linea > 0 && $datos[0] != "" && $datos[3] != "") {

                $evaluado = Users_Model::Query("SELECT  c.Id, CP.NivelCompetencia
            FROM Colaboradores as c
            INNER JOIN ColaboradorPeriodo as CP on CP.ColaboradorID=c.Id and
            CP.periodoId=" . $periodoId . "
            WHERE estatus='Activos' AND Id_MGA_PlazasMH=" . $datos[0]);
                if ($evaluado) {
                    $tipoEvaluacion = 1;
                    switch ($datos[5]) {
                        case 'CLIENTE':
                            $tipoEvaluacion = 3;
                            break;
                        case 'EQUIPO':
                            $tipoEvaluacion = 2;
                            break;
                        case 'PAR':
                            $tipoEvaluacion = 1;
                            break;
                    }
                    $evaluador = Users_Model::Query("SELECT  Id
                    FROM Colaboradores
                    WHERE estatus='Activos' AND Id_MGA_PlazasMH=" . $datos[3]);
                    $contador++;
                    if ($evaluador) {
                        $query .= "INSERT INTO [dbo].[MatrizEvaluacionesComplementarias]
                        ([EvaluadorId]
                        ,[EvaluadoId]
                        ,[TipoEvaluacionId]
                        ,[EstatusId]
                        ,[NivelCompetencia]
                        ,[PeriodoId]
                        )
                  VALUES
                        (" . $evaluador[0]['Id'] . "
                        ," . $evaluado[0]['Id'] . "
                        ," . $tipoEvaluacion . "
                        ,1
                        ," . $evaluado[0]['NivelCompetencia'] . "
                        ," . $periodoEvaluacion . ");";
                    }
                } else {
                    /*  $query .= "INSERT INTO [itgov_ApplicationsUserConfigurations]
                    ([UserId]
                    ,[RoleId]
                    ,[ApplicationId]
                    
                    ,[Online])
              VALUES
                    (" . intval($colaborador) . "
                    ,16
                    ,5
                    ,0); ";
                    */
                }
            }
            $linea++;
        }
        var_dump($contador);
        var_dump($query);
    }
    public function updateMatriz()
    {
        $archivo = fopen('assets/upMatriz3.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";
        $periodoId = 84;
        $periodoEvaluacion = 127;

        while (($datos = fgetcsv($archivo, ",")) == true) {
            if ($linea > 0 && $datos[0] != "" && $datos[2] != "") {

                $evaluado = Users_Model::Query("SELECT  CP.Id
            FROM Colaboradores as c
            INNER JOIN ColaboradorPeriodo as CP on CP.ColaboradorID=c.Id and
            CP.periodoId=" . $periodoId . "
            WHERE estatus='Activos' AND Id_MGA_PlazasMH=" . $datos[0]);
                if ($evaluado) {

                    $evaluador = Users_Model::Query("SELECT  Id
                    FROM Colaboradores
                    WHERE estatus='Activos' AND Id_MGA_PlazasMH=" . $datos[2]);
                    $contador++;
                    if ($evaluador) {
                        $query .= "UPDATE ColaboradorPeriodo
                       SET EvaluadorId=" . $evaluador[0]['Id'] . "
                        WHERE Id=" . $evaluado[0]['Id'] . "
                        ; ";
                    }
                } else {
                    /*  $query .= "INSERT INTO [itgov_ApplicationsUserConfigurations]
                    ([UserId]
                    ,[RoleId]
                    ,[ApplicationId]
                    
                    ,[Online])
              VALUES
                    (" . intval($colaborador) . "
                    ,16
                    ,5
                    ,0); ";
                    */
                }
            }
            $linea++;
        }
        var_dump($contador);
        var_dump($query);
    }
    public function listaAArreglo()
    {
        $archivo = fopen('assets/QuitarEvaluacion.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";
        $periodoId = 84;
        $periodoEvaluacion = 127;

        while (($datos = fgetcsv($archivo, ",")) == true) {

            $query .=  $datos[0] . ",";
            $linea++;
        }
        var_dump($contador);
        var_dump($query);
    }
    public function eliminarMatriz()
    {
        $archivo = fopen('assets/QuitarEvaluacion.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";
        $periodoId = 84;
        $periodoEvaluacion = 127;

        while (($datos = fgetcsv($archivo, ",")) == true) {

            if ($datos[3]) {
                $query .= "UPDATE MatrizEvaluacionesComplementarias SET NivelCompetencia=" . $datos[3] . " WHERE EvaluadoId=" . intval($datos[0]) . " AND PeriodoId=127
             ;";
                $contador++;
            }
            $linea++;
        }
        var_dump($contador);
        var_dump($query);
    }

    public function cambiosCuadrantes()
    {
        $archivo = fopen('assets/cambiosCuadrantes.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";
        $periodoId = 84;
        $periodoEvaluacion = 127;

        while (($datos = fgetcsv($archivo, ",")) == true) {

            if ($datos[0] && $linea>0) {


                $query .= " EXEC spr_InsertCompetenciasFaltantes 134,".$datos[0].",".$datos[2].";
            ";
                $contador++;
            }
            $linea++;
        }
        var_dump($contador);
        var_dump($query);
    }

    public function quitaNoaplica()
    {
        $archivo = fopen('assets/PuestosNoAplica.csv', "r");
        $linea = 0;
        $contador = 0;
        $query = "";
        $periodoId = 84;
        $periodoEvaluacion = 127;

        while (($datos = fgetcsv($archivo, ",")) == true) {

            if ($datos[0]) {
                $evaluador = Users_Model::Query("SELECT

                DISTINCT CP.Id
            
            FROM [dbo].[ColaboradorPeriodo] CP
            LEFT JOIN [dbo].[Colaboradores] AS C ON C.ID = CP.ColaboradorID
            LEFT JOIN [ODS_Catalogs].[dbo].[MGA_PlazasMH] AS AC ON C.Id_MGA_PlazasMH = AC.id AND AC.estatus='Activos'
            
            WHERE CP.PeriodoID = 84  and C.estatus='Activos' 
            --and BOSS.estatus='Activos'
            AND C.IsObjetivoTemprano = 0 
            AND AC.descripcion_puesto='" . $datos[0] . "'");
                if ($evaluador) {
                    foreach ($evaluador as $key) {
                        $query .= $key['Id'] . ",";
                        $contador++;
                    }
                }
                //var_dump($evaluador);

            }
            $linea++;
        }
        var_dump($contador);
        var_dump($query);
    }

    public function completraArchivo()
    {
        $documento = Users_Model::Query("SELECT *,
        CONVERT (varchar(10), FECHADOC, 103) as FDOC,
        CONVERT (varchar(10), FECHACON, 103) as FCON
        FROM POLHO1A
        WHERE DOCUMENTO LIKE '90060078'
        ");
        if ($documento) {
            $doc = $documento[0];
            $pdf = new Pdf();
            $this->contract_atributes($pdf);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            $pdf->AddPage("L", "Letter");
            $pdf->Cell(186, 6, "", 0, 1);
            $w = array(20, 20, 20, 20, 20, 20);
            $pdf->SetFont("Arial", "", 8);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetFillColor(224, 235, 255);
            $pdf->Ln();
            $pdf->Cell(120, 6, "C1. doc.  : RV ( Traspaso de facturas ) Documento normal", "LRT", 0, "L", 1);

            $pdf->Ln();
            $pdf->Cell($w[0], 6, "No. doc", "L");
            $pdf->Cell($w[1], 6, $doc['DOCUMENTO'], 0);
            $pdf->Cell($w[2], 6, "Sociedad", 0, 0, 'L');
            $pdf->Cell($w[3], 6, $doc["SOC"], 0, 0, 'L');
            $pdf->Cell($w[4], 6, "Ejercicio", 0, 0, 'L');
            $pdf->Cell($w[5], 6, $doc["AÑO"], 'R', 0, 'L');
            $pdf->Ln();
            $pdf->Cell($w[0], 6, "Fe. docum.", "L");
            $pdf->Cell($w[1], 6, str_replace("/", ".", $doc['FDOC']), 0);
            $pdf->Cell($w[2], 6, "Fe. contab.", 0, 0, 'L');
            $pdf->Cell($w[3], 6, str_replace("/", ".", $doc['FCON']), 0, 0, 'L');
            $pdf->Cell($w[4], 6, "PERIODO", 0, 0, 'L');
            $pdf->Cell($w[5], 6, $doc["PERIODO"], 'R', 0, 'L');
            $pdf->Ln();
            $pdf->Cell($w[0], 6, "Referen.", "L");
            $pdf->Cell(100, 6, $doc['REFERENCIA'], 'R');
            $pdf->Ln();
            $pdf->Cell($w[0], 6, "Modeda doc.", "LB");
            $pdf->Cell($w[1], 6, "MXN", 'B');
            $pdf->Cell($w[2], 6, "Anulado por.", 'B', 0, 'L');
            $pdf->Cell(60, 6, $doc['ANULA'], 'BR', 0, 'L');
            $pdf->Ln();
            $pdf->Ln();
            $fill = false;
            $w2 = array(8, 5, 13, 35, 13, 20, 20, 20, 14, 20, 13);
            $facturas = Users_Model::Query("SELECT DOCUMENTO
            ,AÑO
            ,POSICION
            ,CUENTA
            ,NOMBRE=(SELECT TXT50 FROM SAPHO1A..SKAT WHERE SPRAS='E' AND KTOPL='0010' AND SAKNR=CONCAT('0000',PDH.CUENTA))
            ,CARABO
            ,TEXTO
            ,CANTIDAD
            ,CLAVE
            ,MONEDA
            ,IMPORTEMONEDA
            ,IMPORTEMN
            FROM POLDETHO1A PDH
            WHERE DOCUMENTO='" . $doc['DOCUMENTO'] . "'
            ORDER BY POSICION
            ");
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetFillColor(224, 235, 255);
            $pdf->Cell($w2[0], 6, "POS", 1, 0, "R", $fill);
            $pdf->Cell($w2[1], 6, "CT",  1, 0, "R", $fill);
            $pdf->Cell($w2[2], 6, "Cuenta", 1, 0, "L", $fill);
            $pdf->Cell($w2[3], 6,  "Texto breve cuenta", 1, 0, "L", $fill);
            $pdf->Cell($w2[4], 6, "II", 1);
            $pdf->Cell($w2[5], 6, "Texto", 1, 0, "L", $fill);
            $pdf->Cell($w2[6], 6, "D", 1, 0, "R", $fill);
            $pdf->Cell($w2[7], 6, "H", 1, 0, "R", $fill);

            $pdf->Cell($w2[8], 6, "Ce. coste", 1, 0, "L", $fill);
            $pdf->Cell($w2[9], 6, "Elem. PEP", 1, 0, "L", $fill);
            $pdf->Cell($w2[10], 6,  "Base Ret.", 1, 0, "L", $fill);

            $pdf->Ln();
            $cargos = 0;
            $abonos = 0;
            if ($facturas) {
                foreach ($facturas as $factura) {
                    if ($factura['CARABO'] === "S") {
                        $cargos += $factura['IMPORTEMN'];
                    } else {
                        $abonos += $factura['IMPORTEMN'];
                    }
                    $pdf->Cell($w2[0], 6, $factura['POSICION'], "LR", 0, "R", $fill);
                    $pdf->Cell($w2[1], 6, $factura['CANTIDAD'],  "LR", 0, "R", $fill);
                    $pdf->Cell($w2[2], 6, $factura['CUENTA'], "LR", 0, "L", $fill);
                    $pdf->Cell($w2[3], 6, substr($factura['NOMBRE'], 0, 20), "LR", 0, "L", $fill);
                    $pdf->Cell($w2[4], 6, "", "LR", 0, "L", $fill);
                    $pdf->Cell($w2[5], 6, $factura['TEXTO'], "LR", 0, "R", $fill);
                    $pdf->Cell($w2[6], 6, $factura['CARABO'] === "S" ? "" . number_format($factura['IMPORTEMN'], 2) : "", "LR", 0, "R", $fill);
                    $pdf->Cell($w2[7], 6, $factura['CARABO'] === "H" ? "" . number_format($factura['IMPORTEMN'], 2) : "", "LR", 0, "R", $fill);


                    $pdf->Cell($w2[8], 6, "", "LR", 0, "R", $fill);
                    $pdf->Cell($w2[9], 6, "", "LR", 0, "R", $fill);
                    $pdf->Cell($w2[10], 6, "", "LR", 0, "R", $fill);
                    $pdf->Ln();
                    $fill = !$fill;
                }
            }
            $pdf->Cell($w2[0], 6, "**", 1, 0, "R", $fill);
            $pdf->Cell($w2[1], 6, "",  1, 0, "R", $fill);
            $pdf->Cell($w2[2], 6, "", 1, 0, "L", $fill);
            $pdf->Cell($w2[3], 6, "", 1, 0, "L", $fill);
            $pdf->Cell($w2[4], 6, "", 1, 0, "L", $fill);
            $pdf->Cell($w2[5], 6, "", 1, 0, "R", $fill);
            $pdf->Cell($w2[6], 6,  "" . number_format(0, 2), 1, 0, "R", $fill);
            $pdf->Cell($w2[7], 6, "" . number_format(0, 2), 1, 0, "R", $fill);
            $pdf->Cell($w2[8], 6, "", 1, 0, "R", $fill);
            $pdf->Cell($w2[9], 6, "", 1, 0, "R", $fill);
            $pdf->Cell($w2[10], 6, "", 1, 0, "R", $fill);
            $pdf->Ln();
            $pdf->Cell(array_sum($w2), 0, '', 'T');
            $pdf->SetTitle('Polizas');
            $pdf->Output("Poliza.pdf", "D");
        }
    }
}
