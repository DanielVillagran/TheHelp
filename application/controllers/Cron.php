<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';
require APPPATH . 'libraries/openpay/Openpay.php';

// require_once BASEPATH . '../application/models/common_library.php';

class Cron extends ANT_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    public function citas()
    {
        $options = array(
            'select' => 'departamentos_servicios_citas.*,departamentos_servicios.nombre as servicio,departamentos.nombre as departamento,departamentos_servicios_citas_agenda.fecha as agendado',
            'joinsLeft' => array(
                'departamentos_servicios' => 'departamentos_servicios.id=departamentos_servicios_citas.departamento_servicio_id',
                'departamentos' => 'departamentos_servicios.departamento_id=departamentos.id',
                'departamentos_servicios_citas_agenda' => 'departamentos_servicios_citas_agenda.departamentos_servicios_cita_id=departamentos_servicios_citas.id',
            ),
            'where' => "departamentos_servicios_citas_agenda.fecha='" . date('Y-m-d H:i:00', strtotime('+1 hour')) . "'",
            'result' => 'array',
        );
        $result = Departamentos_Servicios_Citas_Model::Load($options);
        if ($result) {
            $result = $result[0];

            $fecha = gmmktime(0, 0, 0, date('m', strtotime('+1 hour')), date('d', strtotime('+1 hour')), date('Y', strtotime('+1 hour')));
            setlocale(LC_TIME, 'es_MX.UTF-8');
            $this->enviar_notificacion("Tu cita sera en 1 hora.",
                "Tu cita para " . $result['departamento'] . ' - ' . $result['servicio'] . " se ha agendado para el " .
                ucfirst(strftime("%A, %d de %B de %Y", $fecha) . " a las " . date('h:i A', strtotime('+1 hour'))),
                $result['token']);

        }
        $options = array(
            'select' => 'departamentos_servicios_citas.*,departamentos_servicios.nombre as servicio,departamentos.nombre as departamento,departamentos_servicios_citas_agenda.fecha as agendado',
            'joinsLeft' => array(
                'departamentos_servicios' => 'departamentos_servicios.id=departamentos_servicios_citas.departamento_servicio_id',
                'departamentos' => 'departamentos_servicios.departamento_id=departamentos.id',
                'departamentos_servicios_citas_agenda' => 'departamentos_servicios_citas_agenda.departamentos_servicios_cita_id=departamentos_servicios_citas.id',
            ),
            'where' => "departamentos_servicios_citas_agenda.fecha='" . date('Y-m-d H:i:00', strtotime('+15 minutes')) . "'",
            'result' => 'array',
        );
        $result = Departamentos_Servicios_Citas_Model::Load($options);
        if ($result) {
            $result = $result[0];

            $fecha = gmmktime(0, 0, 0, date('m', strtotime('+1 hour')), date('d', strtotime('+1 hour')), date('Y', strtotime('+1 hour')));
            setlocale(LC_TIME, 'es_MX.UTF-8');
            $this->enviar_notificacion("Tu cita sera en 15 min.",
                "Tu cita para " . $result['departamento'] . ' - ' . $result['servicio'] . " se ha agendado para el " .
                ucfirst(strftime("%A, %d de %B de %Y", $fecha) . " a las " . date('h:i A', strtotime('+15 minutes'))),
                $result['token']);

        }

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

}
