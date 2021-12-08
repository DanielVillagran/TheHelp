<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Notificaciones extends ANT_Controller
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
        $data['title'] = 'Notificaciones';
        $data['view'] = 'forms/push';
        $data['styles'] = 'jquery.shuttle';
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $data['colonias'] = Colonias_Model::get_select();
        $data['intereses'] = Intereses_Model::get_select();
        $this->_load_views('notificaciones/add', $data);
    }
    public function enviar_notificacion()
    {

        $post = $this->input->post();
        $imagen = "";
        $url = "";
        define('API_ACCESS_KEY', 'AIzaSyCT2rnP0u1jDZTqJRbFaW1MuxOKubAl-r4');
        $logo = $this->is_valid_post_file($_FILES['users'], 'logo');
        $filename_destiny = "";
        if ($logo['exist'] && !$logo['error']) {
            $filename_source = $_FILES['users']['tmp_name']['logo'];
            $extension = strtolower(pathinfo($_FILES['users']['name']['logo'], PATHINFO_EXTENSION));
            $nombrelogo = pathinfo($_FILES['users']['name']['logo'], PATHINFO_FILENAME);
            $file_path = 'assets/files/notificaciones/';
            $nombrelogo = str_replace(array("(", ")", " "), "", $nombrelogo);
            $filename = 'notis_' . sprintf('%010s', $post['id']) . '.' . $nombrelogo . '.' . strtolower($extension);
            $filename_destiny = $file_path . DIRECTORY_SEPARATOR . $filename;
            $imagen = $filename_destiny;
            if (file_exists($filename_destiny)) {
                @unlink($filename_destiny);
            }
            if (move_uploaded_file($filename_source, $filename_destiny)) {

            }
        }
        $topic = "ventas";
        $colonia = 0;
        if (isset($post['colonias'])) {
            $topic = $post['colonias'];
            $colonia = $post['colonias'];
        }
        if (isset($post['intereses'])) {
            $topic = $post['intereses'];
        }

        $titulo = $post['titulo'];
        $body = $post['mensaje'];
        $msg = array
            (
            'body' => $body,
            'title' => $titulo,
            'vibrate' => 1,
            'click_action' => "FCM_PLUGIN_ACTIVITY",
            'sound' => 1,
            'image' => 'https://zumpango.vmcomp.com.mx/' . $filename_destiny,
        );
        $data = null;
        if (isset($post['url'])) {
            $data['url'] = $post['url'];
            $url = $post['url'];
        }
        $fields = array
            (
            'to' => '/topics/' . $topic,
            'data' => $data,
            'notification' => $msg,
        );

        $headers = array
            (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        Notificaciones_Model::Insert(array('titulo' => $titulo, 'descripcion' => $body, 'imagen' => $imagen, 'url' => $url, 'colonia' => $colonia));
        //$this->output_json($result);

//header("Location: notificaciones.php");
    }
    public function history()
    {
        $data['title'] = 'Notificaciones';
        $data['view'] = 'grids/Notificaciones';
        $data['styles'] = 'jquery.shuttle';
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('notificaciones/list', $data);
    }
    public function get_Notificaciones()
    {
        $aux = Notificaciones_Model::get_grid_info();
        $data['head'] = "<tr><th>Titulo</th>
		<th>Descripción</th>
		<th>Fecha de Envio</th>
		</tr>";
        $data['table'] = '';
        if ($aux) {
            foreach ($aux as $a) {

                $data['table'] .= '<tr>
				<td class="my-footable-toggle">' . $a['titulo'] . '</td>
			<td>' . $a['descripcion'] . '</td>
			<td>' . $a['created'] . '</td>
		    </tr>';
            }
        } else {
            $data['table'] = '<tr><td colspan="5">Perdon, no hemos encontrado nada.</td></tr>';
        }
        $this->output_json($data);
    }
}
