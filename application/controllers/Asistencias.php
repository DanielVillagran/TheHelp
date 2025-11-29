<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Asistencias extends ANT_Controller
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
		$data['title'] = 'Asistencias';
		$data['view'] = 'grids/Asistencias';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Asistencias/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/Asistencias';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$where = "";
		if ($user_role_id > 2) {
			$where = "empresas.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		$data['empresas'] = Empresas_Model::get_select($where);
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['puestos'] = Puestos_Model::get_select();
		$data['departamentos'] = Empresas_Model::get_select();
		$this->_load_views('Asistencias/add', $data);
	}
	function edit($id)
	{
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/Asistencias';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['departamentos'] = Empresas_Model::get_select();
		$this->_load_views('Asistencias/add', $data);
	}
	function view($id)
	{
		$hc = Empresas_Horarios_Cubiertos_Model::Load(array(
			'select' => "empresas_horarios_cubiertos.*, s.nombre as sede, e.nombre as empresa",
			'where' => 'empresas_horarios_cubiertos.id=' . $id,
			'joins' => array(
				'empresas_sedes as s' => 's.id=empresas_horarios_cubiertos.sede_id',
				'empresas as e' => 'e.id=empresas_horarios_cubiertos.empresa_id'
			),
			'result' => '1row'
		));
		$data['title'] = 'Ver asistencias';
		$data['view'] = 'grids/Asistencia';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['hc'] = $hc;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Asistencias/view', $data);
	}
	function get_info_asistencia($id)
	{
		$datos = Empresas_Horarios_Cubiertos_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $id,
			'result' => '1row'
		));
		$aux = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
			'select' => "empresas_horarios_cubiertos_detalle.*,c.*, p.nombre as puesto, concat(at.prefijo,' - ',at.nombre) as asistencia_tipo, concat(eh.nombre, ' - ',eh.horario) as horario",
			'where' => 'empresas_horarios_cubiertos_detalle.horario_cubierto_id=' . $id,
			'joinsLeft' => array(
				'empresas_puestos_horarios as ep' => 'ep.id=empresas_horarios_cubiertos_detalle.puesto_horario_id',
				'empresas_horarios as eh' => 'eh.id=ep.horario_id',
				'puestos as p' => 'p.id=ep.puesto_id',
				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id',
				'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_detalle.colaborador_id'
			),
			'sortBy' => 'c.id',
			'sortDirection' => 'desc',
			'result' => 'array'
		));
		$data['head'] = "<tr>
		<th>Horario</th>
		<th>Código</th>
		<th>Nombre</th>
		<th>Apellido Paterno</th>
		<th>Apellido Materno</th>
		<th>Puesto</th>
		<th>Tipo incidencia</th>
		<th>Horas extras</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$clase = (empty($a['codigo'])) ? 'style="background-color:#f8d7da !important;"' : '';
				$data['table'] .= '<tr >
				<td ' . $clase . '>' . (empty($a['horario']) ? " FALTA" : $a['horario']) . '</td>
				<td ' . $clase . '>' . (empty($a['codigo']) ? " FALTA" : $a['codigo']) . '</td>
				<td ' . $clase . '>' . $a['nombre'] . '</td>
				<td ' . $clase . '>' . $a['apellido_paterno'] . '</td>
				<td ' . $clase . '>' . $a['apellido_materno'] . '</td>
				<td ' . $clase . '>' . $a['puesto'] . '</td>
				<td ' . $clase . '>' . $a['asistencia_tipo'] . '</td>
				<td ' . $clase . '>' . ($a['asistencia_tipo'] == 'HE - Hora Extra' ? $a['horas_extras'] : "") . '</td>
			</tr>';
			}
		}
		if ($datos) {
			$aux = Empresas_Horarios_Cubiertos_Extras_Model::get_grid_info('empresas_horarios_cubiertos_extras.empresa_id=' .  $datos->empresa_id .
				' AND empresas_horarios_cubiertos_extras.sede_id=' . $datos->sede_id .
				' AND empresas_horarios_cubiertos_extras.fecha="' .  $datos->fecha . '"');
			if ($aux) {
				foreach ($aux as $a) {
					$clase = 'style="background-color:#aedcae !important;"';
					$data['table'] .= '<tr>
					<td ' . $clase . '>' . (empty($a['horario']) ? " FALTA" : $a['horario']) . '</td>
					<td ' . $clase . '>' . (empty($a['codigo']) ? " FALTA" : $a['codigo']) . '</td>
					<td ' . $clase . '>' . $a['nombre'] . '</td>
					<td ' . $clase . '>' . $a['apellido_paterno'] . '</td>
					<td ' . $clase . '>' . $a['apellido_materno'] . '</td>
					<td ' . $clase . '>' . $a['puesto'] . ' - EXTRA' .  '</td>
					<td ' . $clase . '>' . '</td>
					<td ' . $clase . '>' . '</td>
					</tr>';
				}
			}
		}
		if ($data['table'] == "") {
		}
		$this->output_json($data);
	}
	function get_info_Asistencias()
	{
		$post = $this->input->post();
		$data = Asistencias_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Asistencias()
	{
		$aux = Asistencias_Model::get_grid_info();
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
			$result = Asistencias_Model::Insert($post);
		} else {

			$result = Asistencias_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		Asistencias_Model::Delete('id=' . $id);
	}
	function get_asistencias_by_empresa()
	{
		$post = $this->input->post();
		$diaSemana = strtolower(date('l', strtotime($post['fecha'])));
		$tipoAsistencias = Asistencias_Tipo_Model::get_select();
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
		<th>Tipo incidencia</th>
		<th>Cantidad HE</th>
		<th></th>
		</tr>";
		$data['table'] = '';
		$aux = Empresas_Puestos_Horarios_Model::Load(array(
			'select' => "empresas_puestos_horarios.*, concat(eh.nombre, ' - ',eh.horario) as horario, p.nombre as puesto",
			'joinsLeft' => array(
				'empresas_horarios eh' => 'eh.id=empresas_puestos_horarios.horario_id',
				'puestos p' => 'p.id=empresas_puestos_horarios.puesto_id',
			),
			'where' => 'empresas_puestos_horarios.empresa_id=' . $post['empresa_id'] .
				' AND empresas_puestos_horarios.sede_id=' . $post['sede_id'] .
				' AND eh.' . $campoDia . "=1 AND empresas_puestos_horarios.status=1"
				. ($post['horario_id'] != 'Seleccionar horario' ? (" AND empresas_puestos_horarios.horario_id=" . $post['horario_id']) : ""),
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
					<td><select class="form-control input-form"  name="cubiertos[' . $a['id'] . '][]" >
					<option hidden>Seleccionar colaborador</option>
					' . $colaboradores . '
					</select>
					</td>
					<td><select class="form-control input-form" style="margin-left:10px;" name="tipos[' . $a['id'] . '][]" >
					<option hidden>Seleccionar tipo asistencia</option>
					' . $tipoAsistencias . '
					</select>
					</td>
					<td><input type="number" class="form-control input-form" style="margin-left:10px;" name="he[' . $a['id'] . '][]" readonly >
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
						<td></td>
						<td></td>
						<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td>
						</tr>';
			}
		}
		if ($data['table'] == "") {
			$data['table'] = '<tr><td colspan="5">No se han encontrado asignaciones para ese horario</td></tr>';
		}
		$this->output_json($data);
	}
	public function save_puestos_cubiertos()
	{
		$post = $this->input->post();
		$fecha = $post['users']['fecha'];
		$empresa_id = $post['users']['empresa'];
		$sede_id = $post['users']['sede'];
		$asignaciones = $post['cubiertos'];
		$tipos = $post['tipos'];
		$he = $post['he'];
		$id = 0;
		$hasValue = Empresas_Horarios_Cubiertos_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			"where" => "empresa_id=" . $empresa_id . " AND fecha='" . $fecha . "' AND sede_id='" . $sede_id . "'"
		));
		if ($hasValue) {
			$id = $hasValue->id;
			Empresas_Horarios_Cubiertos_Detalle_Model::Delete('horario_cubierto_id=' . $id);
		} else {
			$inserted = Empresas_Horarios_Cubiertos_Model::Insert([
				'empresa_id' => $empresa_id,
				'sede_id' => $sede_id,
				'fecha' => $fecha
			]);
			$id = $inserted['insert_id'];
		}
		foreach ($asignaciones as $puesto_horario_id => $colaboradores) {
			foreach ($colaboradores as $index => $colaborador_id) {
				if (!empty($colaborador_id)) {
					$tipo_asistencia = isset($tipos[$puesto_horario_id][$index]) ? $tipos[$puesto_horario_id][$index] : null;
					$horas_extras = isset($he[$puesto_horario_id][$index]) ? $he[$puesto_horario_id][$index] : null;
					Empresas_Horarios_Cubiertos_Detalle_Model::Insert([
						'horario_cubierto_id' => $id,
						'puesto_horario_id' => $puesto_horario_id,
						'colaborador_id' => $colaborador_id,
						'asistencia_tipo_id' => $tipo_asistencia,
						'horas_extras' => $horas_extras
					]);
				}
			}
		}


		$this->output_json(['status' => 'ok']);
	}
	function save_extra()
	{
		$post = $this->input->post('puesto');
		$hasValue = Empresas_Horarios_Cubiertos_Extras_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			'where' =>
			"empresa_id = " . intval($post['empresa_id']) .
				" AND puesto_id = " . intval($post['puesto_id']) .
				" AND sede_id = " . intval($post['sede_id']) .
				" AND horario_id = " . intval($post['horario_id']) .
				" AND colaborador_id = " . intval($post['colaborador_id']) .
				" AND fecha = '" . addslashes($post['fecha']) . "'"
		));
		if ($hasValue) {
			$this->output_json(false);
		} else {
			$result = Empresas_Horarios_Cubiertos_Extras_Model::Insert($post);
			$this->output_json($result);
		}
	}
	function eliminar_extra()
	{
		$id = $this->input->post("id");
		Empresas_Horarios_Cubiertos_Extras_Model::Delete('id=' . $id);
	}
	function get_Asistencias_globales()
	{
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$where = "";
		if ($user_role_id > 2) {
			$where = "empresas.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		$query = array(
			'select' => "empresas_horarios_cubiertos.*, s.nombre as sede, e.nombre as empresa",

			'joins' => array(
				'empresas_sedes as s' => 's.id=empresas_horarios_cubiertos.sede_id',
				'empresas as e' => 'e.id=empresas_horarios_cubiertos.empresa_id'
			),
			'result' => 'array'
		);
		if ($where) {
			$query['where'] = $where;
		}
		$aux = Empresas_Horarios_Cubiertos_Model::Load($query);
		$data['head'] = "<tr>
		<th>Empresa</th>
		<th>Sede</th>
		<th>Fecha</th>
		<th>Asistencias</th>
		<th>Faltas</th>
		<th>Extras</th>
		<th class='th-editar-colonia'>Ver</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$conteo = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
					'select' => "
						SUM(CASE WHEN empresas_horarios_cubiertos_detalle.colaborador_id = 0 THEN 1 ELSE 0 END) AS faltas,
						SUM(CASE WHEN empresas_horarios_cubiertos_detalle.colaborador_id != 0 THEN 1 ELSE 0 END) AS asistencias,
						COUNT(*) AS total
					",
					'where' => 'empresas_horarios_cubiertos_detalle.horario_cubierto_id=' . intval($a['id']),
					'joinsLeft' => array(
						'empresas_puestos_horarios as ep' => 'ep.id=empresas_horarios_cubiertos_detalle.puesto_horario_id',
						'puestos as p' => 'p.id=ep.puesto_id',
						'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_detalle.colaborador_id'
					),
					'result' => '1row'
				));
				$conteoExtras = Empresas_Horarios_Cubiertos_Extras_Model::Load(array(
					'select' => "COUNT(*) as extras",
					"where" => 'empresas_horarios_cubiertos_extras.empresa_id=' . $a['empresa_id'] .
						' AND empresas_horarios_cubiertos_extras.sede_id=' . $a['sede_id'] .
						' AND empresas_horarios_cubiertos_extras.fecha="' .  $a['fecha'] . '"',
					'result' => '1row'
				));
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-eye"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['empresa'] . '</td>
				<td>' . $a['sede'] . '</td>
				<td>' . $a['fecha'] . '</td>
				<td>' . $conteo->asistencias . '</td>
				<td>' . $conteo->faltas . '</td>
				<td>' . $conteoExtras->extras . '</td>
				<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
}
