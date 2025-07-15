<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Colaboradores extends ANT_Controller
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
		$data['title'] = 'Colaboradores';
		$data['view'] = 'grids/Colaboradores';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Colaboradores/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/Colaboradores';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['departamentos'] = Empresas_Model::get_select();
		$data['horarios'] = Horarios_Model::get_select();
		$this->_load_views('Colaboradores/add', $data);
	}
	function edit($id)
	{
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/Colaboradores';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['departamentos'] = Empresas_Model::get_select();
		$data['horarios'] = Horarios_Model::get_select();
		$this->_load_views('Colaboradores/add', $data);
	}
	function get_info_Colaboradores()
	{
		$post = $this->input->post();
		$data = Colaboradores_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Colaboradores()
	{
		$aux = Colaboradores_Model::get_grid_info();
		$data['head'] = "<tr><th>Código</th>
		<th>Nombre</th>
		<th>Apellido Paterno</th>
		<th>Apellido Materno</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr><td>' . $a['codigo'] . '</td>
				<td>' . $a['nombre'] . '</td>
				<td>' . $a['apellido_paterno'] . '</td>
				<td>' . $a['apellido_materno'] . '</td>
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
			$result = Colaboradores_Model::Insert($post);
		} else {

			$result = Colaboradores_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		$result = Colaboradores_Model::Update(['estatus' => 0], 'id=' . $id);
		$this->output_json($result);
	}
	function carga_masiva()
	{
		$json = file_get_contents("php://input");
		$data = json_decode($json, true);

		if (!isset($data['usuarios'])) {
			show_error("Datos inválidos", 400);
		}
		foreach ($data['usuarios'] as $row) {
			$existUser = Colaboradores_Model::Load(array(
				'select' => "*",
				'where' => "codigo='" . $row['codigo'] . "'",
				'result' => '1row'
			));

			$empresa = Empresas_Model::Load(array(
				'select' => "*",
				'where' => "nombre='" . $row['departamento'] . "'",
				'result' => '1row'
			));
			$horario_id = null;
			if ($empresa) {
				$horario = Empresas_Horarios_Model::Load(array(
					'select' => "*",
					'where' => "nombre='" . $row['horario_id'] . "' AND empresa_id=" . $empresa->id,
					'result' => '1row'
				));
				$horario_id = $horario ? $horario->id : null;
			}

			$usuario = [
				'codigo' => $row['codigo'] ?? '',
				'apellido_paterno' => $row['apellido_paterno'] ?? '',
				'apellido_materno' => $row['apellido_materno'] ?? '',
				'nombre' => $row['nombre'] ?? '',
				'tipo_periodo' => $row['tipo_periodo'] ?? '',
				'departamento' => $empresa ? $empresa->id : null,
				'horario_id' => $horario_id,
				'nomina_nomipaq' => $row['nomina_nomipaq'] ?? '',
				'estatus' => $row['estatus'] == 'Activo' ? 1 : 0
			];
			if (!$existUser) {
				$result = Colaboradores_Model::Insert($usuario);
			} else {
				$result = Colaboradores_Model::Update($usuario, 'id=' . $existUser->id);
			}
		}

		$this->output_json(["status" => "ok"]);
	}
}
