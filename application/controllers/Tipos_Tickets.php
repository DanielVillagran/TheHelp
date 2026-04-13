<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tipos_Tickets extends ANT_Controller
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
		$data['title'] = 'Categorías de Tickets';
		$data['view'] = 'grids/Tipos_Tickets';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Tipos_Tickets/list', $data);
	}

	function add()
	{
		$data['title'] = 'Agregar Categoría de Ticket';
		$data['view'] = 'forms/Tipos_Tickets';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['usuarios'] = Users_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Tipos_Tickets/add', $data);
	}

	function edit($id)
	{
		$data['title'] = 'Editar Categoría de Ticket';
		$data['view'] = 'forms/Tipos_Tickets';
		$data['id'] = $id;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['usuarios'] = Users_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Tipos_Tickets/add', $data);
	}

	function get_info_Tipos_Tickets()
	{
		$post = $this->input->post();
		$data = Tipos_Servicios_Model::Load(array(
			'select' => '*',
			'where' => 'id=' . intval($post['id']),
			'result' => '1row'
		));
		$this->output_json($data);
	}

	function get_Tipos_Tickets()
	{
		$aux = Tipos_Servicios_Model::get_grid_info();
		$data['head'] = "<tr>
		<th>Nombre</th>
		<th>Usuario asignado</th>
		<th>Con copia de correo</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>';
				if (intval($a['id']) !== 2) {
					$botones .= '<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				}

				$usuario_asignado = trim((string)$a['usuario_asignado_nombre']);
				if ($usuario_asignado === '' && !empty($a['usuario_asignado_user_name'])) {
					$usuario_asignado = $a['usuario_asignado_user_name'];
				}
				if ($usuario_asignado === '') {
					$usuario_asignado = 'Sin asignar';
				}

				$con_copia_correo = intval($a['con_copia_correo']) === 1 ? 'Si' : 'No';

				$data['table'] .= '<tr>
				<td>' . htmlspecialchars($a['nombre'], ENT_QUOTES, 'UTF-8') . '</td>
				<td>' . htmlspecialchars($usuario_asignado, ENT_QUOTES, 'UTF-8') . '</td>
				<td>' . htmlspecialchars($con_copia_correo, ENT_QUOTES, 'UTF-8') . '</td>
				<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		}
		$this->output_json($data);
	}

	function save_info()
	{
		$post = $this->input->post('users');
		$payload = array(
			'nombre' => trim((string)($post['nombre'] ?? '')),
			'usuario_asignado' => intval($post['usuario_asignado'] ?? 0),
			'con_copia_correo' => intval($post['con_copia_correo'] ?? 0) === 1 ? 1 : 0
		);

		if (intval($post['id']) === 0) {
			$result = Tipos_Servicios_Model::Insert($payload);
		} else {
			$result = Tipos_Servicios_Model::Update($payload, 'id=' . intval($post['id']));
		}
		$this->output_json($result);
	}

	function eliminar()
	{
		$id = $this->input->post("id");
		if (intval($id) === 2) {
			$this->output_json(array(
				'status' => false,
				'message' => 'La categoría de ticket con id 2 no se puede eliminar porque está asociada a procesos importantes.'
			));
			return;
		}

		Tipos_Servicios_Model::Delete('id=' . intval($id));
		$this->output_json(array('status' => true));
	}
}
