<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';

// require_once BASEPATH . '../application/models/common_library.php';

class Logos extends ANT_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

    }
    public function index()
    {
        $data['title'] = 'Logos';
        $data['view'] = 'forms/Logos';
        $data['styles'] = 'jquery.shuttle';
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('Logos/add', $data);
    }
    public function file_upload()
    {
        $post = $this->input->post();
        $file_path = 'assets/files/fotos/';
        $result = array("message" => "Error en operacion, favor de intentar nuevamente", "result" => false);
        $data = array();
        if (!empty($_FILES)) {
            $filename_source = $_FILES['document']['tmp_name'];
            $extension = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
            $nombreDocument = pathinfo($_FILES['document']['name'], PATHINFO_FILENAME);
            $filename = 'document_' . sprintf('%010s', uniqid()) . '.' . $nombreDocument . '.' . strtolower($extension);
            $filename_destiny = $file_path . DIRECTORY_SEPARATOR . $filename;
            if (file_exists($filename_destiny)) {
                @unlink($filename_destiny);
            }
            if (move_uploaded_file($filename_source, $filename_destiny)) {
                $data['imagen'] = $filename;

                $data['type'] = $post['id'];
            }
        }
        $aux = Logos_Model::Load(array('select' => "*",
            'where' => 'type=' . $post['id'],
            'result' => '1row'));
        if ($aux) {
            $result_document = Logos_Model::Update($data, 'id=' . $aux->id);
        } else {
            $result_document = Logos_Model::Insert($data);
        }
        if ($result_document) {
            $result = array("message" => "Operacion realizada con exito", "result" => true);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    
}
