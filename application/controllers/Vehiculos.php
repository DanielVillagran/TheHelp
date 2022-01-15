<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Vehiculos extends ANT_Controller {
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
		$data['title'] = 'Vehiculos';
		$data['view'] = 'grids/Vehiculos';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Vehiculos/list', $data);
	}
	function add() {
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/Vehiculos';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['clientes'] = Clientes_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Vehiculos/add', $data);
	}
	function edit($id) {
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/Vehiculos';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['clientes'] = Clientes_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Vehiculos/add', $data);
	}
	function get_info_Vehiculos(){
		$post = $this->input->post();
		$data = Vehiculos_Model::Load(array('select' => "*",
				'where' => 'id=' . $post['id'],
				'result' => '1row'));
				$this->output_json($data);
	}
	function get_Vehiculos() {
		$aux = Vehiculos_Model::get_grid_info();
		$data['head'] = "<tr><th>Marca</th>
		<th>Modelo</th>
		<th>Serie</th>
		
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr><td>' . $a['marca'] . '</td>
				<td>' . $a['modelo'] . '</td>
				<td>' . $a['serie'] . '</td>
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
			$result = Vehiculos_Model::Insert($post);
		} else {
		
			$result = Vehiculos_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar(){
		$id = $this->input->post("id");
		Vehiculos_Model::Delete('id='.$id);
	}
}
