<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Horarios extends ANT_Controller
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
		$data['title'] = 'Horarios';
		$data['view'] = 'grids/Horarios';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Horarios/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/Horarios';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['departamentos'] = Empresas_Model::get_select();
		$this->_load_views('Horarios/add', $data);
	}
	function edit($id)
	{
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/Horarios';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['departamentos'] = Empresas_Model::get_select();
		$this->_load_views('Horarios/add', $data);
	}
	function get_info_Horarios()
	{
		$post = $this->input->post();
		$data = Horarios_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Horarios()
	{
		$aux = Horarios_Model::get_grid_info();
		$data['head'] = "<tr><th>Nombre</th>
		<th>Lunes</th>
		<th>Martes</th>
		<th>Miercoles</th>
		<th>Jueves</th>
		<th>Viernes</th>
		<th>Sabado</th>
		<th>Domingo</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['nombre'] . '</td>
				<td>' . ($a['lunes'] ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-times danger"></i>') . '</td>
				<td>' . ($a['martes'] ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-times danger"></i>') . '</td>
				<td>' . ($a['miercoles'] ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-times danger"></i>') . '</td>
				<td>' . ($a['jueves'] ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-times danger"></i>') . '</td>
				<td>' . ($a['viernes'] ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-times danger"></i>') . '</td>
				<td>' . ($a['sabado'] ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-times danger"></i>') . '</td>
				<td>' . ($a['domingo'] ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-times danger"></i>') . '</td>
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
			$result = Horarios_Model::Insert($post);
		} else {

			$result = Horarios_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		Horarios_Model::Delete('id=' . $id);
	}
}
