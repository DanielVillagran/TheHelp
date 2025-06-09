<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Eventos extends ANT_Controller
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
        $data['title'] = 'Eventos';
        $data['view'] = 'grids/Eventos';
        $data['styles'] = 'jquery.shuttle';
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('Eventos/list', $data);
    }
    public function add()
    {
        $data['title'] = 'Agregar departamento';
        $data['view'] = 'forms/Eventos';
        $data['styles'] = 'jquery.shuttle';
        $data['id'] = 0;
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('Eventos/add', $data);
    }
    public function edit($id)
    {
        $data['title'] = 'Editar departamento';
        $data['view'] = 'forms/Eventos';
        $data['styles'] = 'jquery.shuttle';
        $data['id'] = $id;
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('Eventos/add', $data);
    }
    public function get_info_Eventos()
    {
        $post = $this->input->post();
        $data = Eventos_Model::Load(array('select' => "*",
            'where' => 'id=' . $post['id'],
            'result' => '1row'));
        $this->output_json($data);
    }
    public function get_Eventos()
    {
        $aux = Eventos_Model::get_grid_info($this->tank_auth->get_user_type());
        $data['head'] = "<tr><th></th><th>Nombre</th>
		<th>Descripción</th>
		<th class='th-editar'>Editar</th>
		</tr>";
        $data['table'] = '';
        if ($aux) {
            foreach ($aux as $a) {
                $botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
                if ($a['status'] == 1) {
                    $botones .= '<button type="button" class="btn btn-default row-disable" rel="' . $a['id'] . '"><i class="fa fa-ban" aria-hidden="true"></i></button>';
                }
                $data['table'] .= '<tr>
				<td><img style="width:100px;" src="/assets/' . $a['logo'] . '"></td>
				<td><span class="row-edit" rel="' . $a['id'] . '">' . $a['nombre'] . '</span></td>
			<td>' . $a['descripcion'] . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
            }
        } else {
            
        }
        $this->output_json($data);
    }
    public function save_info()
    {
        $post = $this->input->post('users');

        if ($post['id'] == 0) {
            $post['user_type'] = $this->tank_auth->get_user_type();
            $result = Eventos_Model::Insert($post);
            $logo = $this->is_valid_post_file($_FILES['users'], 'logo');
            if ($logo['exist'] && !$logo['error']) {
                $filename_source = $_FILES['users']['tmp_name']['logo'];
                $extension = strtolower(pathinfo($_FILES['users']['name']['logo'], PATHINFO_EXTENSION));
                $nombrelogo = pathinfo($_FILES['users']['name']['logo'], PATHINFO_FILENAME);
                $file_path = 'assets/files/fotos/';
                $nombrelogo = str_replace(array("(", ")", " "), "", $nombrelogo);
                $filename = 'logo_' . sprintf('%010s', $post['id']) . '.' . $nombrelogo . '.' . strtolower($extension);
                $filename_destiny = $file_path . DIRECTORY_SEPARATOR . $filename;
                if (file_exists($filename_destiny)) {
                    @unlink($filename_destiny);
                }
                if (move_uploaded_file($filename_source, $filename_destiny)) {
                    $subida = Eventos_Model::Update(array('logo' => 'files/fotos/' . $filename), array('id' => $result['insert_id']));
                }
            }
        } else {
            $logo = $this->is_valid_post_file($_FILES['users'], 'logo');
            if ($logo['exist'] && !$logo['error']) {
                $filename_source = $_FILES['users']['tmp_name']['logo'];
                $extension = strtolower(pathinfo($_FILES['users']['name']['logo'], PATHINFO_EXTENSION));
                $nombrelogo = pathinfo($_FILES['users']['name']['logo'], PATHINFO_FILENAME);
                $file_path = 'assets/files/fotos/';
                $nombrelogo = str_replace(array("(", ")", " "), "", $nombrelogo);
                $filename = 'logo_' . sprintf('%010s', $post['id']) . '.' . $nombrelogo . '.' . strtolower($extension);
                $filename_destiny = $file_path . DIRECTORY_SEPARATOR . $filename;
                if (file_exists($filename_destiny)) {
                    @unlink($filename_destiny);
                }
                if (move_uploaded_file($filename_source, $filename_destiny)) {
                    $result = Eventos_Model::Update(array('logo' => 'files/fotos/' . $filename), array('id' => $post['id']));
                }
            }
            $result = Eventos_Model::Update($post, 'id=' . $post['id']);
        }
        $this->output_json($result);
    }
    public function save_pregunta()
    {
        $post = $this->input->post();

        $elemento = array('evento_id' => $post['id'],
            'pregunta' => $post['descripcion']);
        if ($post['id_pregunta'] > 0) {
            Eventos_Preguntas_Model::Update($elemento, 'id=' . $post['id_pregunta']);
        } else {
            Eventos_Preguntas_Model::Insert($elemento);
        }
    }
    public function get_preguntas_list()
    {
        $id = $this->input->post("id");
        $aux = Eventos_Preguntas_Model::get_grid_info($id);

        $data['table'] = '';
        if ($aux) {
            foreach ($aux as $a) {

                $botones = '<button type="button" class="btn btn-default row-edit" onclick="editar_pregunta(' . $a['id'] . ')"><i class="fa fa-pencil"></i></button>';
                $data['table'] .= '<tr>
				<td>' . $a['pregunta'] . '</td>
				<td>' . $a['si'] . '</td>
				<td>' . $a['no'] . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
            }
        } else {
            
        }
        $this->output_json($data);
    }
    public function get_preguntas_list_csv($id)
    {
        $aux = Eventos_Preguntas_Model::get_grid_info($id);
        $my_file = 'assets/files/preguntas.csv';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);

        $texto = "Pregunta,Cantidad SI, Cantidad NO\n";
        if ($aux) {
            foreach ($aux as $a) {
                $texto .= $a['pregunta'] . "," . $a['si'] . "," . $a['no'] . "\n";

            }
        }
        fwrite($handle, $texto);
        fclose($handle);
        header("Content-type: text/csv");
        header("Content-disposition: attachment; filename = Reporte de Preguntas.csv");
        readfile($my_file);
    }
    public function delete_Pregunta()
    {
        $id = $this->input->post("id");
        Eventos_Preguntas_Model::Delete('id=' . $id);
    }
    public function get_info_Pregunta()
    {
        $id = $this->input->post("id");
        $data = Eventos_Preguntas_Model::Load(array('select' => "*",
            'where' => 'id=' . $id,
            'result' => '1row'));
        $this->output_json($data);
    }
    public function eliminar()
    {
        $id = $this->input->post("id");
        Eventos_Model::Delete('id=' . $id);
        Eventos_Preguntas_Model::Delete('evento_id=' . $id);
    }
    public function disable()
    {
        $id = $this->input->post("id");
        Eventos_Model::Update(array('status' => 0), 'id=' . $id);
        //Eventos_Preguntas_Model::Update(array('status=0'),'evento_id='.$id);
    }
    public function get_participantes_list()
    {
        $id = $this->input->post("id");
        $aux = Eventos_Participaciones_Model::get_grid_info($id);

        $data['table'] = '';
        if ($aux) {
            foreach ($aux as $a) {

                $botones = '';
                $data['table'] .= '<tr>
				<td>' . $a['number'] . '</td>
				<td>' . $a['created'] . '</td>';
            }
        } else {
            
        }
        $this->output_json($data);
    }
    public function get_part_list_csv($id)
    {
        $my_file = 'assets/files/part.csv';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);

        $texto = "Numero Telefonico,Fecha\n";
        $aux = Eventos_Participaciones_Model::get_grid_info($id);

        if ($aux) {
            foreach ($aux as $a) {
                $texto .= $a['number'] . "," . $a['created'] . "\n";
            }
        } 
        fwrite($handle, $texto);
        fclose($handle);
        header("Content-type: text/csv");
        header("Content-disposition: attachment; filename = Reporte de Participantes.csv");
        readfile($my_file);
    }
    public function get_calificaciones_list()
    {
        $id = $this->input->post("id");
        $aux = Eventos_Calificaciones_Model::get_grid_info($id);

        $data['table'] = '';
        if ($aux) {
            foreach ($aux as $a) {

                $botones = '';
                $data['table'] .= '<tr>
				<td>' . $a['comentario'] . '</td>
				<td>' . $a['calificacion'] . '</td>
				<td>' . $a['created'] . '</td>';
            }
        } else {
            
        }
        $this->output_json($data);
    }
    public function get_calif_list_csv($id)
    {
        $my_file = 'assets/files/calif.csv';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);

        $texto = "Comentario,Calificacion,Fecha\n";
        $aux = Eventos_Calificaciones_Model::get_grid_info($id);
        if ($aux) {
            foreach ($aux as $a) {
                $texto .= $a['comentario'] . "," . $a['calificacion'] . "," . $a['created'] . "\n";
            }
        } 
        fwrite($handle, $texto);
        fclose($handle);
        header("Content-type: text/csv");
        header("Content-disposition: attachment; filename = Reporte de Calificaciones.csv");
        readfile($my_file);
    }
}
