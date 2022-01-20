<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class ClienteVehiculos extends ANT_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	function view($id)
	{
		$data['title'] = 'Ver Vehiculo';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		//$data['datos_vehiculo'] = $this->get_info_Vehiculos($id);
		$data['tipos_servicios'] = Tipos_Servicios_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['view'] = 'forms/ClienteVehiculos';
		$this->_load_views('ClienteVehiculo/add', $data);
	}
	function get_info_Vehiculos()
	{
		$post = $this->input->post();
		$data = Vehiculos_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$data->head = "<tr>
				<th>Tipo de Servicio</th>
				<th>Fecha</th>
				<th>Descripción</th>
				</tr>";
		$aux = Servicios_Model::get_grid_info_by_vehiculo($post['id']);
		$data->tableData = '';
		if ($aux) {
			foreach ($aux as $a) {
				$data->tableData .= '<tr>
					
						<td>' . $a['tipoServicio'] . '</td>
						<td>' . $a['createdAt'] . '</td>
						<td>' . $a['descripcion'] . '</td>
					</tr>';
			}
		} else {
			$data->tableData = '<tr><td colspan="5">Perdon, no hemos encontrado nada.</td></tr>';
		}
		$data->head2 = "<tr>
				<th>Tipo de Servicio</th>
				<th>Fecha</th>
				<th>Descripción</th>
				<th>Status</th>
				</tr>";
		$aux = Tickets_Model::get_grid_info_by_vehiculo($post['id']);
		$data->tableData2 = '';
		if ($aux) {
			foreach ($aux as $a) {
				$data->tableData2 .= '<tr>
					
						<td>' . $a['tipoServicio'] . '</td>
						<td>' . $a['createdAt'] . '</td>
						<td>' . $a['descripcion'] . '</td>
						<td>' . $a['status'] . '</td>
					</tr>';
			}
		} else {
			$data->tableData2 = '<tr><td colspan="5">Perdon, no hemos encontrado nada.</td></tr>';
		}
		$this->output_json($data);
	}

	function save_info()
	{
		$post = $this->input->post('users');

		if ($post['id'] == 0) {
			$result = Vehiculos_Model::Insert($post);
		} else {

			$result = Vehiculos_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function save_ticket()
	{
		$post = $this->input->post('tickets');

		if ($post['id'] == 0) {
			$result = Tickets_Model::Insert($post);
		} else {

			$result = Tickets_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
}
