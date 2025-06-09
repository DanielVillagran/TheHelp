<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Encuestas extends ANT_Controller
{
	private $opciones = [
		1 => 'Escala (B,R,M,N/A)',
		2 => 'Escala 1 a 5',
		3 => 'Escala (B,R,M,N/A) con comentario',
		4 => 'Escala 1 a 5 con comentario',
		5 => 'Valor numérico',
		6 => 'Comentario'
	];
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
		$data['title'] = 'Encuestas';
		$data['view'] = 'grids/Encuestas';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Encuestas/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/Encuestas';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['departamentos'] = Empresas_Model::get_select();
		$this->_load_views('Encuestas/add', $data);
	}
	function edit($id)
	{
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/Encuestas';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['opciones'] = $this->opciones;
		$data['departamentos'] = Empresas_Model::get_select();
		$this->_load_views('Encuestas/edit', $data);
	}
	function get_info_Encuestas()
	{
		$post = $this->input->post();
		$data = Encuestas_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Encuestas()
	{
		$aux = Encuestas_Model::get_grid_info();
		$data['head'] = "<tr><th>Nombre</th>
		
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
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
			$result = Encuestas_Model::Insert($post);
		} else {

			$result = Encuestas_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		Encuestas_Model::Delete('id=' . $id);
	}
	function save_pregunta()
	{
		$post = $this->input->post('pregunta');
		$hasValue = Encuestas_Preguntas_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			"where" => "encuesta_id=" . $post['encuesta_id'] . " AND pregunta='" . $post['pregunta'] . "'"
		));
		if ($hasValue) {
			$this->output_json(false);
		} else {
			$result = Encuestas_Preguntas_Model::Insert($post);
			$this->output_json($result);
		}
	}
	function get_Encuestas_preguntas()
	{
		$id = $this->input->post('id');
		$aux = Encuestas_Preguntas_Model::get_grid_info("encuesta_id=" . $id);
		$data['head'] = "<tr>
		<th>Pregunta</th>
		<th>Tipo</th>
		<th>Orden</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['pregunta'] . '</td>
				<td>' . $this->opciones[$a['tipo']] . '</td>
				<td>' . $a['orden'] . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
	function respuestasAdd()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/RespuestaEncuesta';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$where = "";
		if ($user_role_id > 2) {
			$where = "empresas.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		$data['empresas'] = Empresas_Model::get_select($where);
		$data['encuestas'] = Encuestas_Model::get_select();
		
		$this->_load_views('RespuestasEncuestas/add', $data);
	}
	function get_respuestas_responder()
	{
		$post = $this->input->post();
		$diaSemana = strtolower(date('l', strtotime($post['fecha'])));
		$mapaDias = [
			'monday' => 'lunes',
			'tuesday' => 'martes',
			'wednesday' => 'miercoles',
			'thursday' => 'jueves',
			'friday' => 'viernes',
			'saturday' => 'sabado',
			'sunday' => 'domingo',
		];
		$campoDia = $mapaDias[$diaSemana];
		$data['select_horarios'] = Empresas_Horarios_Model::get_select("empresa_id=" . $post['empresa_id']);
		$data['head'] = "<tr><th>Horario</th>
		<th>Puesto</th>
		<th>Persona que cubre</th>
		<th></th>
		</tr>";
		$data['table'] = '';
		$aux = Empresas_Puestos_Horarios_Model::Load(array(
			'select' => "empresas_puestos_horarios.*, eh.nombre as horario, p.nombre as puesto",
			'joinsLeft' => array(
				'empresas_horarios eh' => 'eh.id=empresas_puestos_horarios.horario_id',
				'puestos p' => 'p.id=empresas_puestos_horarios.puesto_id',
			),
			'where' => 'empresas_puestos_horarios.empresa_id=' . $post['empresa_id'] .
				' AND empresas_puestos_horarios.sede_id=' . $post['sede_id'] .
				' AND eh.' . $campoDia . "=1",
			'result' => 'array'
		));
		$colaboradores = Colaboradores_Model::get_select("departamento=" . $post['empresa_id']);
		$data['select_colaboradores'] = $colaboradores;
		if ($aux) {
			foreach ($aux as $a) {
				for ($i = 1; $i <= $a['cantidad']; $i++) {
					$data['table'] .= '<tr>
					<td>' . $a['horario'] . '</td>
					<td>' . $a['puesto'] . '</td>
					<td><select class="form-control input-form" name="cubiertos[' . $a['id'] . '][]" >
					<option hidden>Seleccionar colaborador</option>
					' . $colaboradores . '
					</select>
					</td>
					<td></td>
					</tr>';
				}
			}
		}
		$aux = Empresas_Horarios_Cubiertos_Extras_Model::get_grid_info('empresas_horarios_cubiertos_extras.empresa_id=' . $post['empresa_id'] .
			' AND empresas_horarios_cubiertos_extras.sede_id=' . $post['sede_id'] .
			' AND empresas_horarios_cubiertos_extras.fecha="' . $post['fecha'] . '"');
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" onclick="delete_extra(' . $a['id'] . ')"><i class="fa fa-trash"></i></button>';

				$data['table'] .= '<tr>
						<td>' . $a['horario'] . '</td>
						<td>' . $a['puesto'] . '</td>
						<td>' . $a['colaborador'] . '</td>
						<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td>
						</tr>';
			}
		}
		if ($data['table'] == "") {
			$data['table'] = '<tr><td colspan="5">No se han encontrado asignaciones para ese horario</td></tr>';
		}
		$this->output_json($data);
	}
}
