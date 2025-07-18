<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Servicios extends ANT_Controller
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
		$data['title'] = 'Servicios';
		$data['view'] = 'grids/Servicios';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Servicios/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Servicio';
		$data['view'] = 'forms/Servicios';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['vehiculos'] = Vehiculos_Model::get_select();
		$data['tipos_servicios'] = Tipos_Servicios_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Servicios/add', $data);
	}
	function edit($id)
	{
		$data['title'] = 'Editar Servicio';
		$data['view'] = 'forms/Servicios';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['vehiculos'] = Vehiculos_Model::get_select();
		$data['tipos_servicios'] = Tipos_Servicios_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Servicios/add', $data);
	}
	function get_info_Servicios()
	{
		$post = $this->input->post();
		$data = Servicios_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Servicios()
	{
		$aux = Servicios_Model::get_grid_info();
		$data['head'] = "<tr>
		<th>Vehiculo</th>
		<th>Tipo de Servicio</th>
		<th>Fecha</th>
		<th>Descripción</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr>
				<td>' .  $a['marca'] . ' - ' . $a['modelo'] . ' - ' . $a['serie'] . '</td>
				<td>' . $a['tipoServicio'] . '</td>
				<td>' . $a['createdAt'] . '</td>
				<td>' . $a['descripcion'] . '</td>
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
			$result = Servicios_Model::Insert($post);
			$evidencia = $this->is_valid_post_file($_FILES['users'], 'evidencia');
			if ($evidencia['exist'] && !$evidencia['error']) {
				$filename_source = $_FILES['users']['tmp_name']['evidencia'];
				$extension = strtolower(pathinfo($_FILES['users']['name']['evidencia'], PATHINFO_EXTENSION));
				$nombreevidencia = pathinfo($_FILES['users']['name']['evidencia'], PATHINFO_FILENAME);
				$file_path = 'assets/files/fotos/';
				$nombreevidencia = str_replace(array("(", ")", " "), "", $nombreevidencia);
				$filename = 'evidencia_' . sprintf('%010s', $post['id']) . '.' . $nombreevidencia . '.' . strtolower($extension);
				$filename_destiny = $file_path . DIRECTORY_SEPARATOR . $filename;
				if (file_exists($filename_destiny)) {
					@unlink($filename_destiny);
				}
				if (move_uploaded_file($filename_source, $filename_destiny)) {
					$subida = Servicios_Model::Update(array('evidencia' => 'files/fotos/' . $filename), array('id' => $result['insert_id']));
				}
			}
		} else {
			$evidencia = $this->is_valid_post_file($_FILES['users'], 'evidencia');
			if ($evidencia['exist'] && !$evidencia['error']) {
				$filename_source = $_FILES['users']['tmp_name']['evidencia'];
				$extension = strtolower(pathinfo($_FILES['users']['name']['evidencia'], PATHINFO_EXTENSION));
				$nombreevidencia = pathinfo($_FILES['users']['name']['evidencia'], PATHINFO_FILENAME);
				$file_path = 'assets/files/fotos/';
				$nombreevidencia = str_replace(array("(", ")", " "), "", $nombreevidencia);
				$filename = 'evidencia_' . sprintf('%010s', $post['id']) . '.' . $nombreevidencia . '.' . strtolower($extension);
				$filename_destiny = $file_path . DIRECTORY_SEPARATOR . $filename;
				if (file_exists($filename_destiny)) {
					@unlink($filename_destiny);
				}
				if (move_uploaded_file($filename_source, $filename_destiny)) {
					$result = Servicios_Model::Update(array('evidencia' => 'files/fotos/' . $filename), array('id' => $post['id']));
				}
			}
			$result = Servicios_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		Servicios_Model::Delete('id=' . $id);
	}
}
