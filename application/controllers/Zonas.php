<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Zonas extends ANT_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/');
			return;
		}
	}
	function index()
	{
		$data['title'] = 'Zonas';
		$data['view'] = 'grids/Zonas';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Zonas/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/Zonas';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['departamentos'] = Empresas_Model::get_select();
		$this->_load_views('Zonas/add', $data);
	}
	function edit($id)
	{
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/Zonas';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['departamentos'] = Empresas_Model::get_select();
		$this->_load_views('Zonas/add', $data);
	}
	function get_info_Zonas()
	{
		$post = $this->input->post();
		$data = Zonas_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Zonas()
	{
		$aux = Zonas_Model::get_grid_info();
		$data['head'] = "<tr><th>Nombre</th>
		
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['nombre'] . '</td>
				<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
			
		}
		$this->output_json($data);
	}
	function save_info()
	{
		$post = $this->input->post('users');

		if ($post['id'] == 0) {
			$result = Zonas_Model::Insert($post);
		} else {

			$result = Zonas_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		Zonas_Model::Delete('id=' . $id);
	}
}
