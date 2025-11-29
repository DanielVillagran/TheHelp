<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class PreAltas extends ANT_Controller
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
		$data['title'] = 'PreAltas';
		$data['view'] = 'grids/PreAltas';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('PreAltas/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/PreAltas';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();

		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['clientes'] = Empresas_Model::get_select();
		$data['horarios'] = Horarios_Model::get_select();
		$data['estados_mexico'] = $this->get_estados();

		$this->_load_views('PreAltas/add', $data);
	}

	function edit($id)
	{
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/PreAltas';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['estados_mexico'] = $this->get_estados();
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['clientes'] = Empresas_Model::get_select();

		$this->_load_views('PreAltas/add', $data);
	}
	
	function save_info()
	{
		$post = $this->input->post('users');

		if ($post['id'] == 0) {
			$post['status'] = 1;
			$result = Colaboradores_Model::Insert($post);
			Colaboradores_Movimientos_Model::Insert(['status' => 1, 'colaborador_id' => $result['insert_id']]);
		} else {

			$result = Colaboradores_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
}
