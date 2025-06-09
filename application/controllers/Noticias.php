<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Noticias extends ANT_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/');
			return;
		}
	}
	function index() {
		$data['title'] = 'Noticias';
		$data['view'] = 'grids/Noticias';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Noticias/list', $data);
	}
	function add() {
		$data['title'] = 'Agregar Noticia';
		$data['view'] = 'forms/Noticias';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = 0;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Noticias/add', $data);
	}
	function edit($id) {
		$data['title'] = 'Editar Noticia';
		$data['view'] = 'forms/Noticias';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Noticias/add', $data);
	}
	function get_info_Noticias(){
		$post = $this->input->post();
		$data = Noticias_Model::Load(array('select' => "*",
				'where' => 'id=' . $post['id'],
				'result' => '1row'));
				$this->output_json($data);
	}
	
	
	
	function get_Noticias() {
		$aux = Noticias_Model::get_grid_info($this->tank_auth->get_user_type());
		$data['head'] = "<tr><th class='th-img-noticias'></th><th>Título</th>
		<th class='th-desc-noticias'>Descripción</th>
		<th class='th-url-noticias'>Url</th>
		<th class='th-editar'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr>
				<td class="td-img-noticias"><img style="width:100px;" src="/assets/' . $a['logo'] . '"></td>
				<td>' . $a['titulo'] . '</td>
			<td class="td-desc-noticias">' . $a['descripcion'] . '</td>
			<td class="td-url-noticias one-line"><a target="_blank" title="' . $a['url'] . '" href="' . $a['url'] . '">' . $a['url'] . '</a></td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
			
		}
		$this->output_json($data);
	}
	function save_info() {
		$post = $this->input->post('users');
		
		if ($post['id']==0) {
			$post['user_type']=$this->tank_auth->get_user_type();
			$result = Noticias_Model::Insert($post);
			$logo = $this->is_valid_post_file($_FILES['users'], 'logo');
            if ($logo['exist'] && !$logo['error']) {
                $filename_source = $_FILES['users']['tmp_name']['logo'];
                $extension = strtolower(pathinfo($_FILES['users']['name']['logo'], PATHINFO_EXTENSION));
                $nombrelogo = pathinfo($_FILES['users']['name']['logo'], PATHINFO_FILENAME);
                $file_path = 'assets/files/fotos/';
                $nombrelogo = str_replace(array("(",")"," "), "", $nombrelogo);
                $filename = 'logo_' . sprintf('%010s', $post['id']) . '.' . $nombrelogo . '.' . strtolower($extension);
                $filename_destiny = $file_path . DIRECTORY_SEPARATOR . $filename;
                if (file_exists($filename_destiny)) {
                    @unlink($filename_destiny);
                }
                if (move_uploaded_file($filename_source, $filename_destiny)) {
                    $subida = Noticias_Model::Update(array('logo' => 'files/fotos/' . $filename), array('id' => $result['insert_id']));
                }
            }
		} else {
			$logo = $this->is_valid_post_file($_FILES['users'], 'logo');
            if ($logo['exist'] && !$logo['error']) {
                $filename_source = $_FILES['users']['tmp_name']['logo'];
                $extension = strtolower(pathinfo($_FILES['users']['name']['logo'], PATHINFO_EXTENSION));
                $nombrelogo = pathinfo($_FILES['users']['name']['logo'], PATHINFO_FILENAME);
                $file_path = 'assets/files/fotos/';
                $nombrelogo = str_replace(array("(",")"," "), "", $nombrelogo);
                $filename = 'logo_' . sprintf('%010s', $post['id']) . '.' . $nombrelogo . '.' . strtolower($extension);
                $filename_destiny = $file_path . DIRECTORY_SEPARATOR . $filename;
                if (file_exists($filename_destiny)) {
                    @unlink($filename_destiny);
                }
                if (move_uploaded_file($filename_source, $filename_destiny)) {
                    $result = Noticias_Model::Update(array('logo' => 'files/fotos/' . $filename), array('id' => $post['id']));
                }
            }
			$result = Noticias_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function save_servicio(){
		$post = $this->input->post();
		if($post['pagos']=='true'){
			$post['pagos']=1;
		}else{
			$post['pagos']=0;
		}
		if($post['citas']=='true'){
			$post['citas']=1;
		}else{
			$post['citas']=0;
		}
		$elemento=array('departamento_id'=>$post['id'],
		'nombre'=>$post['nombre'],
		'descripcion'=>$post['descripcion'],
		'pagos'=>$post['pagos'],
		'citas'=>$post['citas']);
		if($post['id_servicio']>0){
			Departamentos_Servicios_Model::Update($elemento,'id='.$post['id_servicio']);
		}else{
		Departamentos_Servicios_Model::Insert($elemento);
		}
	}
	function get_service_list() {
		$id = $this->input->post("id");
		$aux = Departamentos_Servicios_Model::get_grid_info($id);
	
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				if($a['pagos']==1){
					$a['pagos']='<i class="fa fa-check" aria-hidden="true"></i>';
				}else{
					$a['pagos']='<i class="fa fa-times" aria-hidden="true"></i>';
				}
				if($a['citas']==1){
					$a['citas']='<i class="fa fa-check" aria-hidden="true"></i>';
				}else{
					$a['citas']='<i class="fa fa-times" aria-hidden="true"></i>';
				}
				$botones = '<button type="button" class="btn btn-default row-edit" onclick="editar_servicio(' . $a['id'] . ')"><i class="fa fa-pencil"></i></button>';
				$data['table'] .= '<tr><td>' . $a['nombre'] . '</td>
			<td>' . $a['descripcion'] . '</td>
			<td class="td-center">' . $a['citas'] . '</td>
			<td class="td-center">' . $a['pagos'] . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
			
		}
		$this->output_json($data);
	}
	function delete_service(){
		$id = $this->input->post("id");
		Departamentos_Servicios_Model::Delete('id='.$id);
	}
	function get_info_Service(){
		$id = $this->input->post("id");
		$data = Departamentos_Servicios_Model::Load(array('select' => "*",
				'where' => 'id=' . $id,
				'result' => '1row'));
				$this->output_json($data);
	}
	function eliminar(){
		$id = $this->input->post("id");
		Noticias_Model::Delete('id='.$id);
	}
}
