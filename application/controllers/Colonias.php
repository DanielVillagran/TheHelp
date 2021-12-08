<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Colonias extends ANT_Controller {
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
		$data['title'] = 'Colonias';
		$data['view'] = 'grids/colonias';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('colonias/list', $data);
	}
	function add() {
		$data['title'] = 'Agregar Colonia';
		$data['view'] = 'forms/colonias';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('colonias/add', $data);
	}
	function edit($id) {
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/colonias';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('colonias/add', $data);
	}
	function get_info_Colonias(){
		$post = $this->input->post();
		$data = Colonias_Model::Load(array('select' => "*",
				'where' => 'id=' . $post['id'],
				'result' => '1row'));
				$this->output_json($data);
	}
	function get_Colonias() {
		$aux = Colonias_Model::get_grid_info();
		$data['head'] = "<tr><th>Nombre</th>
		
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr><td>' . $a['nombre'] . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
			$data['table'] = '<tr><td colspan="5">Perdon, no hemos encontrado nada.</td></tr>';
		}
		$this->output_json($data);
	}
	function save_info() {
		$post = $this->input->post('users');
		
		if ($post['id']==0) {
			$result = Colonias_Model::Insert($post);
		} else {
		
			$result = Colonias_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar(){
		$id = $this->input->post("id");
		Colonias_Model::Delete('id='.$id);
	}
}
