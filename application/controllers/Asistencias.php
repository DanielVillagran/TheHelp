<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Asistencias extends ANT_Controller
{
	private $horas_minimas_relectura_qr = 2;

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
	function registro_asistencia()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/AsistenciasQR';
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
		$this->_load_views('Asistencias/qr', $data);
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
				$prefijo_asistencia = trim(explode('-', (string)$a['asistencia_tipo'])[0]);
				$clase = '';
				if ($prefijo_asistencia === 'F') {
					$clase = 'style="background-color:#f4b183 !important;"';
				} elseif ($prefijo_asistencia === 'DL' && intval($a['confirmar_dl']) !== 1) {
					$clase = 'style="background-color:#fff3cd !important;"';
				} elseif (empty($a['codigo'])) {
					$clase = 'style="background-color:#f8d7da !important;"';
				}
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
	function decode_qr_colaborador()
	{
		date_default_timezone_set("America/Mexico_City");
		$fecha_actual = date('Y-m-d');
		$token = trim((string)$this->input->post('token'));
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');
		$sede_id = $this->input->post('sede_id');
		if ($token === '') {
			$this->output_json([
				'status' => false,
				'mensaje' => 'No se recibio el token del QR.'
			]);
			return;
		}

		$secret = getenv('QR_TOKEN') ?: '';
		if ($secret === '') {
			$this->output_json([
				'status' => false,
				'mensaje' => 'No se encontro el secreto QR_TOKEN.'
			]);
			return;
		}
		$this->load->library('qr_tokenizer');
		$colaborador_id = $this->qr_tokenizer->decode($token, $secret);
		if (!$colaborador_id || !ctype_digit((string)$colaborador_id)) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'El QR no es valido.'
			]);
			return;
		}

		$colaborador = Colaboradores_Model::Load([
			'select' => 'id, codigo, nombre, apellido_paterno, apellido_materno, cliente, sede, puesto, horario_id',
			'where' => 'id=' . intval($colaborador_id),
			'result' => '1row'
		]);

		if (!$colaborador) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'No se encontro el colaborador.'
			]);
			return;
		} else {
			$validacion_relectura = $this->validar_relectura_qr($colaborador->id, $fecha_actual);
			if (!$validacion_relectura['status']) {
				$this->output_json([
					'status' => false,
					'mensaje' => $validacion_relectura['mensaje']
				]);
				return;
			}

			$insert_result = Asistencias_Validas_Model::Insert([
				'colaborador_id' => $colaborador->id,
				'user_id' => $this->tank_auth->get_user_id(),
				'lat' => $lat,
				'lng' => $lng,
				'sede_id' => $sede_id,
				'fecha' => $fecha_actual
			]);

			$detalle_resultado = $this->registrar_detalle_qr_asistencia(
				$colaborador,
				$fecha_actual,
				intval($sede_id),
				!empty($validacion_relectura['doble_registro'])
			);
			if (!$detalle_resultado['status']) {
				if (!empty($insert_result['insert_id'])) {
					Asistencias_Validas_Model::Delete('id=' . intval($insert_result['insert_id']));
				}
				$this->output_json([
					'status' => false,
					'mensaje' => $detalle_resultado['mensaje']
				]);
				return;
			}
		}

		$this->output_json([
			'status' => true,
			'mensaje' => 'QR valido.',
			'colaborador_id' => intval($colaborador->id),
			'codigo' => $colaborador->codigo,
			'nombre' => trim($colaborador->nombre . ' ' . $colaborador->apellido_paterno . ' ' . $colaborador->apellido_materno)
		]);
	}

	private function registrar_detalle_qr_asistencia($colaborador, $fecha_actual, $sede_id, $doble_registro = false)
	{
		$empresa_id = intval($colaborador->cliente);
		$puesto_id = intval($colaborador->puesto);
		$horario_id = intval($colaborador->horario_id);
		$sede_asociada = intval($colaborador->sede);
		$sede_id = intval($sede_id);

		if ($empresa_id <= 0 || $puesto_id <= 0 || $horario_id <= 0) {
			return [
				'status' => false,
				'mensaje' => 'El colaborador no tiene empresa, puesto u horario configurado.'
			];
		}

		if ($sede_asociada > 0 && $sede_asociada !== $sede_id) {
			return [
				'status' => false,
				'mensaje' => 'El colaborador no pertenece a la sede del registro.'
			];
		}

		$puesto_horario = Empresas_Puestos_Horarios_Model::Load([
			'select' => 'id',
			'where' => 'empresa_id=' . $empresa_id .
				' AND sede_id=' . $sede_id .
				' AND puesto_id=' . $puesto_id .
				' AND horario_id=' . $horario_id .
				' AND status=1',
			'result' => '1row'
		]);
		if (!$puesto_horario) {
			return [
				'status' => false,
				'mensaje' => 'No existe una configuracion activa de puesto y horario para este colaborador en la sede.'
			];
		}

		$horario_cubierto = Empresas_Horarios_Cubiertos_Model::Load([
			'select' => 'id, finalizado',
			'where' => 'empresa_id=' . $empresa_id . ' AND sede_id=' . $sede_id . ' AND fecha="' . $fecha_actual . '"',
			'result' => '1row'
		]);
		if (!$horario_cubierto) {
			$insertado = Empresas_Horarios_Cubiertos_Model::Insert([
				'empresa_id' => $empresa_id,
				'sede_id' => $sede_id,
				'fecha' => $fecha_actual
			]);
			$horario_cubierto_id = isset($insertado['insert_id']) ? intval($insertado['insert_id']) : 0;
		} else {
			$horario_cubierto_id = intval($horario_cubierto->id);
			if (intval($horario_cubierto->finalizado) === 1) {
				return [
					'status' => false,
					'mensaje' => 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.'
				];
			}
		}

		if ($horario_cubierto_id <= 0) {
			return [
				'status' => false,
				'mensaje' => 'No fue posible crear el registro principal de asistencias.'
			];
		}

		$prefijo_tipo = 'A';
		$tipo_asistencia = Asistencias_Tipo_Model::Load([
			'select' => 'id',
			'where' => "prefijo='" . $prefijo_tipo . "'",
			'result' => '1row'
		]);
		if (!$tipo_asistencia) {
			return [
				'status' => false,
				'mensaje' => 'No existe el tipo de asistencia con prefijo ' . $prefijo_tipo . '.'
			];
		}

		$detalle_existente = Empresas_Horarios_Cubiertos_Detalle_Model::Load([
			'select' => 'empresas_horarios_cubiertos_detalle.id, at.prefijo as asistencia_prefijo',
			'where' => 'horario_cubierto_id=' . $horario_cubierto_id . ' AND colaborador_id=' . intval($colaborador->id),
			'joinsLeft' => [
				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id'
			],
			'result' => '1row',
			'sortBy' => 'id',
			'sortDirection' => 'asc'
		]);

		if ($doble_registro) {
			if ($detalle_existente) {
				if ($detalle_existente->asistencia_prefijo === 'F') {
					Empresas_Horarios_Cubiertos_Detalle_Model::Update([
						'puesto_horario_id' => intval($puesto_horario->id),
						'asistencia_tipo_id' => intval($tipo_asistencia->id),
						'horas_extras' => null,
						'confirmar_dl' => 0
					], 'id=' . intval($detalle_existente->id));
				}
			} else {
				Empresas_Horarios_Cubiertos_Detalle_Model::Insert([
					'horario_cubierto_id' => $horario_cubierto_id,
					'puesto_horario_id' => intval($puesto_horario->id),
					'colaborador_id' => intval($colaborador->id),
					'asistencia_tipo_id' => intval($tipo_asistencia->id),
					'horas_extras' => null,
					'confirmar_dl' => 0
				]);
			}

			return $this->registrar_extra_qr_asistencia($colaborador, $fecha_actual, $sede_id, $empresa_id);
		} elseif ($detalle_existente && $detalle_existente->asistencia_prefijo === 'F') {
			Empresas_Horarios_Cubiertos_Detalle_Model::Update([
				'puesto_horario_id' => intval($puesto_horario->id),
				'asistencia_tipo_id' => intval($tipo_asistencia->id),
				'horas_extras' => null,
				'confirmar_dl' => 0
			], 'id=' . intval($detalle_existente->id));
		} elseif (!$detalle_existente) {
			Empresas_Horarios_Cubiertos_Detalle_Model::Insert([
				'horario_cubierto_id' => $horario_cubierto_id,
				'puesto_horario_id' => intval($puesto_horario->id),
				'colaborador_id' => intval($colaborador->id),
				'asistencia_tipo_id' => intval($tipo_asistencia->id),
				'horas_extras' => null,
				'confirmar_dl' => 0
			]);
		}

		return [
			'status' => true
		];
	}

	private function registrar_extra_qr_asistencia($colaborador, $fecha_actual, $sede_id, $empresa_id)
	{
		$extra = [
			'empresa_id' => intval($empresa_id),
			'puesto_id' => intval($colaborador->puesto),
			'sede_id' => intval($sede_id),
			'horario_id' => intval($colaborador->horario_id),
			'colaborador_id' => intval($colaborador->id),
			'fecha' => $fecha_actual,
			'confirmar_dl' => 0
		];

		$hasValue = Empresas_Horarios_Cubiertos_Extras_Model::Load([
			'select' => 'id',
			'result' => '1row',
			'where' =>
			'empresa_id = ' . $extra['empresa_id'] .
				' AND puesto_id = ' . $extra['puesto_id'] .
				' AND sede_id = ' . $extra['sede_id'] .
				' AND horario_id = ' . $extra['horario_id'] .
				' AND colaborador_id = ' . $extra['colaborador_id'] .
				" AND fecha = '" . addslashes($extra['fecha']) . "'"
		]);

		if ($hasValue) {
			return [
				'status' => true
			];
		}

		$result = Empresas_Horarios_Cubiertos_Extras_Model::Insert($extra);
		if (empty($result['result'])) {
			return [
				'status' => false,
				'mensaje' => 'No fue posible crear el registro extra de asistencia.'
			];
		}

		return [
			'status' => true
		];
	}

	private function validar_relectura_qr($colaborador_id, $fecha_actual)
	{
		$ultima_asistencia = Asistencias_Validas_Model::Load([
			'select' => 'id, created_at',
			'where' => 'colaborador_id=' . intval($colaborador_id) . ' AND fecha="' . $fecha_actual . '"',
			'result' => '1row',
			'sortBy' => 'created_at',
			'sortDirection' => 'desc'
		]);

		if (!$ultima_asistencia) {
			return [
				'status' => true,
				'doble_registro' => false
			];
		}

		if (empty($ultima_asistencia->created_at)) {
			return [
				'status' => false,
				'mensaje' => 'Ya existe un registro para este colaborador en la fecha ' . $fecha_actual . '.'
			];
		}

		$fecha_ultimo_registro = new DateTime($ultima_asistencia->created_at);
		$fecha_actual_qr = new DateTime();
		$segundos_transcurridos = $fecha_actual_qr->getTimestamp() - $fecha_ultimo_registro->getTimestamp();
		$segundos_minimos = $this->horas_minimas_relectura_qr * 3600;

		if ($segundos_transcurridos < $segundos_minimos) {
			$segundos_restantes = $segundos_minimos - $segundos_transcurridos;
			$horas_restantes = floor($segundos_restantes / 3600);
			$minutos_restantes = ceil(($segundos_restantes % 3600) / 60);
			$tiempo_restante = [];
			if ($horas_restantes > 0) {
				$tiempo_restante[] = $horas_restantes . ' hora' . ($horas_restantes === 1 ? '' : 's');
			}
			if ($minutos_restantes > 0) {
				$tiempo_restante[] = $minutos_restantes . ' minuto' . ($minutos_restantes === 1 ? '' : 's');
			}

			return [
				'status' => false,
				'mensaje' => 'Ya existe un registro reciente para este colaborador. Deben pasar al menos ' . $this->horas_minimas_relectura_qr . ' horas entre lecturas. Tiempo restante: ' . implode(' ', $tiempo_restante) . '.'
			];
		}

		return [
			'status' => true,
			'doble_registro' => true
		];
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		Asistencias_Model::Delete('id=' . $id);
	}
	function confirmar_dl()
	{
		$id = intval($this->input->post('id'));
		$confirmado = intval($this->input->post('confirmado')) === 1 ? 1 : 0;
		if ($id <= 0) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'Debes enviar un detalle valido.'
			]);
			return;
		}

		$detalle = Empresas_Horarios_Cubiertos_Detalle_Model::Load([
			'select' => 'empresas_horarios_cubiertos_detalle.id, at.prefijo as asistencia_prefijo, ehc.finalizado',
			'where' => 'empresas_horarios_cubiertos_detalle.id=' . $id,
			'joinsLeft' => [
				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id',
				'empresas_horarios_cubiertos as ehc' => 'ehc.id=empresas_horarios_cubiertos_detalle.horario_cubierto_id'
			],
			'result' => '1row'
		]);

		if (!$detalle || $detalle->asistencia_prefijo !== 'DL') {
			$this->output_json([
				'status' => false,
				'mensaje' => 'Solo se puede confirmar un detalle DL.'
			]);
			return;
		}
		if (intval($detalle->finalizado) === 1) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.'
			]);
			return;
		}

		$result = Empresas_Horarios_Cubiertos_Detalle_Model::Update([
			'confirmar_dl' => $confirmado
		], 'id=' . $id);

		$this->output_json([
			'status' => (bool)$result
		]);
	}
	function get_asistencias_by_empresa()
	{
		$post = $this->input->post();
		$diaSemana = strtolower(date('l', strtotime($post['fecha'])));
		$tipoAsistencias = Asistencias_Tipo_Model::get_select();
		$tipoFalta = Asistencias_Tipo_Model::Load([
			'select' => 'id',
			'where' => "prefijo='F'",
			'result' => '1row'
		]);
		$tipoFaltaId = $tipoFalta ? intval($tipoFalta->id) : 0;
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
		$horario_base = $this->asegurar_horario_cubierto_con_faltas($post['empresa_id'], $post['sede_id'], $post['fecha'], $campoDia);
		if (!$horario_base['status']) {
			$this->output_json($horario_base);
			return;
		}
		$data['select_horarios'] = Empresas_Horarios_Model::get_select("empresa_id=" . $post['empresa_id']);
		$data['head'] = "<tr><th>Horario</th>
		<th>Puesto</th>
		<th>Persona que cubre</th>
		<th>Tipo incidencia</th>
		<th>Cantidad HE</th>
		<th>Confirmar DL</th>
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
		$horario_cubierto = Empresas_Horarios_Cubiertos_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			'where' => "empresa_id=" . intval($post['empresa_id']) . " AND fecha='" . $post['fecha'] . "' AND sede_id='" . intval($post['sede_id']) . "'"
		));
		$detalles_guardados = [];
		$data['finalizado'] = ($horario_cubierto && intval($horario_cubierto->finalizado) === 1) ? 1 : 0;
		if ($horario_cubierto) {
			
			$detalles = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
				'select' => '*',
				'result' => 'array',
				'where' => 'horario_cubierto_id=' . intval($horario_cubierto->id),
				'sortBy' => 'id',
				'sortDirection' => 'asc'
			));
			if ($detalles) {
				foreach ($detalles as $detalle) {
					$puesto_horario_id = intval($detalle['puesto_horario_id']);
					if (!isset($detalles_guardados[$puesto_horario_id])) {
						$detalles_guardados[$puesto_horario_id] = [];
					}
					$detalles_guardados[$puesto_horario_id][] = $detalle;
				}
			}
		}
		$colaboradores = Colaboradores_Model::get_select("cliente=" . $post['empresa_id']);
		$colaboradores_asistencias = Colaboradores_Model::get_asistencias_reales("sede =" . $post['sede_id'] . " AND fecha='" . $post['fecha'] . "'");
		$data['select_colaboradores'] = $colaboradores;
		if ($aux) {
			foreach ($aux as $a) {
				for ($i = 1; $i <= $a['cantidad']; $i++) {
					$detalle_guardado = null;
					$tipo_asistencia_id = null;
					$colaborador_id = null;
					$horas_extras = null;
					$usa_select_asistencia = false;
					if (!empty($detalles_guardados[intval($a['id'])])) {
						$detalle_guardado = array_shift($detalles_guardados[intval($a['id'])]);
					}
					if ($detalle_guardado) {
						$tipo_asistencia_id = isset($detalle_guardado['asistencia_tipo_id']) ? intval($detalle_guardado['asistencia_tipo_id']) : null;
						$colaborador_id = isset($detalle_guardado['colaborador_id']) ? intval($detalle_guardado['colaborador_id']) : null;
						$horas_extras = isset($detalle_guardado['horas_extras']) ? $detalle_guardado['horas_extras'] : null;
						$usa_select_asistencia = in_array((string)$tipo_asistencia_id, ['1', '7', '9'], true);
					}

					$colaboradores_options = $this->set_selected_option($colaboradores, $usa_select_asistencia ? null : $colaborador_id);
					$colaboradores_asistencias_options = $this->set_selected_option($colaboradores_asistencias, $usa_select_asistencia ? $colaborador_id : null);
					$tipos_options = $this->set_selected_option($tipoAsistencias, $tipo_asistencia_id);
					$horas_extras_value = ($horas_extras !== null && $horas_extras !== '') ? $horas_extras : '';
					$readonly_he = in_array((string)$tipo_asistencia_id, ['1', '7'], true) ? '' : 'readonly';
					$confirmar_dl = isset($detalle_guardado['confirmar_dl']) ? intval($detalle_guardado['confirmar_dl']) : 0;
					$es_dl_pendiente = ((string)$tipo_asistencia_id === '9' && $confirmar_dl !== 1);
					$es_falta = ($tipoFaltaId > 0 && intval($tipo_asistencia_id) === $tipoFaltaId);
					$cell_style = '';
					if ($es_dl_pendiente) {
						$cell_style = ' style="background-color:#fff3cd !important;"';
					} elseif ($es_falta) {
						$cell_style = ' style="background-color:#f4b183 !important;"';
					}
					$confirmar_dl_html = '';
					if ((string)$tipo_asistencia_id === '9') {
						$confirmar_dl_html = '<label style="margin:0; display:flex; align-items:center; justify-content:center; gap:6px;">
							<input type="checkbox" class="confirmar-dl-toggle" ' . ($confirmar_dl === 1 ? 'checked' : '') . '>
							<span>Confirmar DL</span>
						</label>
						<input type="hidden" class="confirmar-dl-value" name="confirmar_dl[' . $a['id'] . '][]" value="' . $confirmar_dl . '">';
					} else {
						$confirmar_dl_html = '<input type="hidden" class="confirmar-dl-value" name="confirmar_dl[' . $a['id'] . '][]" value="0">';
					}
					$data['table'] .= '<tr>
					<td' . $cell_style . '>' . $a['horario'] . '</td>
					<td' . $cell_style . '>' . $a['puesto'] . '</td>
					<td' . $cell_style . '>
					<div id="colaborador_todos_' . $a['id'] . '_' . $i . '" class="colaborador-select-wrapper"' . ($usa_select_asistencia ? ' style="display:none;"' : '') . '>
					<select class="form-control input-form colaborador-select colaborador-select-todos" name="cubiertos[' . $a['id'] . '][]">
					<option hidden>Seleccionar colaborador</option>
					' . $colaboradores_options . '
					</select>
					</div>
					<div id="colaborador_asistente_' . $a['id'] . '_' . $i . '" class="colaborador-select-wrapper"' . ($usa_select_asistencia ? '' : ' style="display:none;"') . '>
					<select class="form-control input-form colaborador-select colaborador-select-asistencia" name="cubiertos[' . $a['id'] . '][]"' . ($usa_select_asistencia ? '' : ' disabled') . '>
					<option hidden>Seleccionar colaborador</option>
					' . $colaboradores_asistencias_options . '
					</select>
					</div>
					</td>
					<td' . $cell_style . '><select class="form-control input-form" style="margin-left:10px;" name="tipos[' . $a['id'] . '][]" >
					<option hidden>Seleccionar tipo asistencia</option>
					' . $tipos_options . '
					</select>
					</td>
					<td' . $cell_style . '><input type="number" class="form-control input-form" style="margin-left:10px;" name="he[' . $a['id'] . '][]" value="' . $horas_extras_value . '" ' . $readonly_he . ' >
					</td>
					<td' . $cell_style . '>' . $confirmar_dl_html . '</td>
					<td' . $cell_style . '></td>
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
					$horarios_extra_options = $this->set_selected_option($data['select_horarios'], $a['horario_id']);
					$confirmar_extra_dl = isset($a['confirmar_dl']) ? intval($a['confirmar_dl']) : 0;
					$extra_cell_style = $confirmar_extra_dl === 1 ? '' : ' style="background-color:#fff3cd !important;"';
					$confirmar_extra_dl_html = '<label style="margin:0; display:flex; align-items:center; justify-content:center; gap:6px;">
						<input type="checkbox" class="confirmar-extra-dl-toggle" data-id="' . $a['id'] . '" ' . ($confirmar_extra_dl === 1 ? 'checked' : '') . '>
						<span>Confirmar DL</span>
					</label>';

					$data['table'] .= '<tr>
							<td' . $extra_cell_style . '><select class="form-control input-form extra-horario-select" data-id="' . $a['id'] . '">
								' . $horarios_extra_options . '
							</select></td>
							<td' . $extra_cell_style . '>' . $a['puesto'] . '</td>
							<td' . $extra_cell_style . '>' . $a['colaborador'] . '</td>
							<td' . $extra_cell_style . '>Extra</td>
							<td' . $extra_cell_style . '></td>
							<td' . $extra_cell_style . '>' . $confirmar_extra_dl_html . '</td>
							<td' . $extra_cell_style . ' class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td>
							</tr>';
				}
			}
		if ($data['table'] == "") {
			$data['table'] = '<tr><td colspan="7">No se han encontrado asignaciones para ese horario</td></tr>';
		}
		$this->output_json($data);
	}

	private function asegurar_horario_cubierto_con_faltas($empresa_id, $sede_id, $fecha, $campoDia)
	{
		$empresa_id = intval($empresa_id);
		$sede_id = intval($sede_id);
		$fecha = addslashes($fecha);

		if ($empresa_id <= 0 || $sede_id <= 0 || empty($fecha) || empty($campoDia)) {
			return [
				'status' => false,
				'mensaje' => 'Debes enviar empresa, sede y fecha validas.'
			];
		}

		$tipo_falta = Asistencias_Tipo_Model::Load([
			'select' => 'id',
			'where' => "prefijo='F'",
			'result' => '1row'
		]);
		if (!$tipo_falta) {
			return [
				'status' => false,
				'mensaje' => 'No existe el tipo de asistencia con prefijo F.'
			];
		}
		$tipo_falta_id = intval($tipo_falta->id);

		$puestos_horarios = Empresas_Puestos_Horarios_Model::Load([
			'select' => 'empresas_puestos_horarios.id, empresas_puestos_horarios.cantidad, empresas_puestos_horarios.puesto_id, empresas_puestos_horarios.horario_id',
			'joinsLeft' => [
				'empresas_horarios eh' => 'eh.id=empresas_puestos_horarios.horario_id'
			],
			'where' => 'empresas_puestos_horarios.empresa_id=' . $empresa_id .
				' AND empresas_puestos_horarios.sede_id=' . $sede_id .
				' AND eh.' . $campoDia . '=1 AND empresas_puestos_horarios.status=1',
			'result' => 'array',
			'sortBy' => 'empresas_puestos_horarios.id',
			'sortDirection' => 'asc'
		]);

		if (!$puestos_horarios) {
			return [
				'status' => true,
				'horario_cubierto_id' => 0
			];
		}

		$horario_cubierto = Empresas_Horarios_Cubiertos_Model::Load([
			'select' => 'id, finalizado',
			'where' => 'empresa_id=' . $empresa_id . ' AND sede_id=' . $sede_id . ' AND fecha="' . $fecha . '"',
			'result' => '1row'
		]);

		if (!$horario_cubierto) {
			$insertado = Empresas_Horarios_Cubiertos_Model::Insert([
				'empresa_id' => $empresa_id,
				'sede_id' => $sede_id,
				'fecha' => $fecha,
				'finalizado' => 0
			]);
			$horario_cubierto_id = isset($insertado['insert_id']) ? intval($insertado['insert_id']) : 0;
			$finalizado = 0;
		} else {
			$horario_cubierto_id = intval($horario_cubierto->id);
			$finalizado = intval($horario_cubierto->finalizado);
		}

		if ($horario_cubierto_id <= 0) {
			return [
				'status' => false,
				'mensaje' => 'No fue posible crear el registro principal de asistencias.'
			];
		}

		if ($finalizado === 1) {
			return [
				'status' => true,
				'horario_cubierto_id' => $horario_cubierto_id
			];
		}

		$detalles = Empresas_Horarios_Cubiertos_Detalle_Model::Load([
			'select' => 'id, puesto_horario_id, colaborador_id',
			'where' => 'horario_cubierto_id=' . $horario_cubierto_id,
			'result' => 'array'
		]);
		$conteo_por_puesto_horario = [];
		$colaboradores_usados = [];
		if ($detalles) {
			foreach ($detalles as $detalle) {
				$puesto_horario_id = intval($detalle['puesto_horario_id']);
				if (!isset($conteo_por_puesto_horario[$puesto_horario_id])) {
					$conteo_por_puesto_horario[$puesto_horario_id] = 0;
				}
				$conteo_por_puesto_horario[$puesto_horario_id]++;

				$colaborador_id = intval($detalle['colaborador_id']);
				if ($colaborador_id > 0) {
					$colaboradores_usados[$colaborador_id] = true;
				}
			}
		}

		$colaboradores = Colaboradores_Model::Load([
			'select' => 'id, puesto, horario_id',
			'where' => 'cliente=' . $empresa_id . ' AND sede=' . $sede_id . ' AND status=3',
			'result' => 'array',
			'sortBy' => 'codigo',
			'sortDirection' => 'asc'
		]);
		$colaboradores_por_puesto_horario = [];
		if ($colaboradores) {
			foreach ($colaboradores as $colaborador) {
				$key = intval($colaborador['puesto']) . '_' . intval($colaborador['horario_id']);
				if (!isset($colaboradores_por_puesto_horario[$key])) {
					$colaboradores_por_puesto_horario[$key] = [];
				}
				$colaboradores_por_puesto_horario[$key][] = intval($colaborador['id']);
			}
		}

		foreach ($puestos_horarios as $puesto_horario) {
			$puesto_horario_id = intval($puesto_horario['id']);
			$cantidad = intval($puesto_horario['cantidad']);
			$creados = isset($conteo_por_puesto_horario[$puesto_horario_id]) ? intval($conteo_por_puesto_horario[$puesto_horario_id]) : 0;
			$key = intval($puesto_horario['puesto_id']) . '_' . intval($puesto_horario['horario_id']);
			$candidatos = isset($colaboradores_por_puesto_horario[$key]) ? $colaboradores_por_puesto_horario[$key] : [];

			while ($creados < $cantidad && !empty($candidatos)) {
				$candidato_id = array_shift($candidatos);
				if (isset($colaboradores_usados[$candidato_id])) {
					continue;
				}

				Empresas_Horarios_Cubiertos_Detalle_Model::Insert([
					'horario_cubierto_id' => $horario_cubierto_id,
					'puesto_horario_id' => $puesto_horario_id,
					'colaborador_id' => $candidato_id,
					'asistencia_tipo_id' => $tipo_falta_id,
					'horas_extras' => null,
					'confirmar_dl' => 0
				]);
				$colaboradores_usados[$candidato_id] = true;
				$creados++;
			}
			$colaboradores_por_puesto_horario[$key] = $candidatos;

			while ($creados < $cantidad) {
				Empresas_Horarios_Cubiertos_Detalle_Model::Insert([
					'horario_cubierto_id' => $horario_cubierto_id,
					'puesto_horario_id' => $puesto_horario_id,
					'colaborador_id' => 0,
					'asistencia_tipo_id' => $tipo_falta_id,
					'horas_extras' => null,
					'confirmar_dl' => 0
				]);
				$creados++;
			}
		}

		return [
			'status' => true,
			'horario_cubierto_id' => $horario_cubierto_id
		];
	}

	private function set_selected_option($html, $selected_value)
	{
		if ($selected_value === null || $selected_value === '' || $html === '') {
			return $html;
		}

		$selected_value = (string) $selected_value;
		return preg_replace('/(<option\b[^>]*value="' . preg_quote($selected_value, '/') . '"(?![^>]*\bselected\b)[^>]*)(>)/i', '$1 selected$2', $html, 1);
	}
	private function get_horario_cubierto($empresa_id, $sede_id, $fecha)
	{
		return Empresas_Horarios_Cubiertos_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			"where" => "empresa_id=" . intval($empresa_id) . " AND fecha='" . addslashes($fecha) . "' AND sede_id='" . intval($sede_id) . "'"
		));
	}
	public function save_puestos_cubiertos()
	{
		$post = $this->input->post();
		$fecha = $post['users']['fecha'];
		$empresa_id = $post['users']['empresa'];
		$sede_id = $post['users']['sede'];
		$asignaciones = isset($post['cubiertos']) ? $post['cubiertos'] : [];
		$tipos = isset($post['tipos']) ? $post['tipos'] : [];
		$he = isset($post['he']) ? $post['he'] : [];
		$confirmar_dl = isset($post['confirmar_dl']) ? $post['confirmar_dl'] : [];
		$finalizar = isset($post['finalizar_asistencias']) && intval($post['finalizar_asistencias']) === 1 ? 1 : 0;
		$id = 0;
		$hasValue = $this->get_horario_cubierto($empresa_id, $sede_id, $fecha);
		if ($hasValue) {
			if (intval($hasValue->finalizado) === 1) {
				$this->output_json([
					'status' => 'error',
					'mensaje' => 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.'
				]);
				return;
			}
			$id = $hasValue->id;
			$extras_sin_confirmar = Empresas_Horarios_Cubiertos_Extras_Model::Load([
				'select' => 'id',
				'result' => '1row',
				'where' => 'empresa_id=' . intval($empresa_id) .
					' AND sede_id=' . intval($sede_id) .
					" AND fecha='" . addslashes($fecha) . "'" .
					' AND IFNULL(confirmar_dl,0)!=1'
			]);
			if ($extras_sin_confirmar) {
				$this->output_json([
					'status' => 'error',
					'mensaje' => 'Debes confirmar los extras DL antes de guardar.'
				]);
				return;
			}
			Empresas_Horarios_Cubiertos_Detalle_Model::Delete('horario_cubierto_id=' . $id);
		} else {
			$inserted = Empresas_Horarios_Cubiertos_Model::Insert([
				'empresa_id' => $empresa_id,
				'sede_id' => $sede_id,
				'fecha' => $fecha,
				'finalizado' => 0
			]);
			$id = $inserted['insert_id'];
		}
		foreach ($asignaciones as $puesto_horario_id => $colaboradores) {
			foreach ($colaboradores as $index => $colaborador_id) {
				if (!empty($colaborador_id)) {
					$tipo_asistencia = isset($tipos[$puesto_horario_id][$index]) ? $tipos[$puesto_horario_id][$index] : null;
					$horas_extras = isset($he[$puesto_horario_id][$index]) ? $he[$puesto_horario_id][$index] : null;
					$confirmar_dl_valor = isset($confirmar_dl[$puesto_horario_id][$index]) ? intval($confirmar_dl[$puesto_horario_id][$index]) : 0;
					Empresas_Horarios_Cubiertos_Detalle_Model::Insert([
						'horario_cubierto_id' => $id,
						'puesto_horario_id' => $puesto_horario_id,
						'colaborador_id' => $colaborador_id,
						'asistencia_tipo_id' => $tipo_asistencia,
						'horas_extras' => $horas_extras,
						'confirmar_dl' => $confirmar_dl_valor
					]);
				}
			}
		}

		if ($finalizar === 1) {
			Empresas_Horarios_Cubiertos_Model::Update([
				'finalizado' => 1
			], 'id=' . intval($id));
		}

		$this->output_json(['status' => 'ok', 'finalizado' => $finalizar]);
	}
	function save_extra()
	{
		$post = $this->input->post('puesto');
		$post['confirmar_dl'] = isset($post['confirmar_dl']) ? intval($post['confirmar_dl']) : 0;
		$horario_cubierto = $this->get_horario_cubierto($post['empresa_id'], $post['sede_id'], $post['fecha']);
		if ($horario_cubierto && intval($horario_cubierto->finalizado) === 1) {
			$this->output_json([
				'status' => 'error',
				'mensaje' => 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.'
			]);
			return;
		}
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
	function update_extra_horario()
	{
		$id = intval($this->input->post('id'));
		$horario_id = intval($this->input->post('horario_id'));
		if ($id <= 0 || $horario_id <= 0) {
			$this->output_json([
				'status' => 'error',
				'mensaje' => 'Debes seleccionar un horario valido.'
			]);
			return;
		}

		$extra = Empresas_Horarios_Cubiertos_Extras_Model::Load([
			'select' => '*',
			'result' => '1row',
			'where' => 'id=' . $id
		]);
		if (!$extra) {
			$this->output_json([
				'status' => 'error',
				'mensaje' => 'No se encontro el extra.'
			]);
			return;
		}

		$horario_cubierto = $this->get_horario_cubierto($extra->empresa_id, $extra->sede_id, $extra->fecha);
		if ($horario_cubierto && intval($horario_cubierto->finalizado) === 1) {
			$this->output_json([
				'status' => 'error',
				'mensaje' => 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.'
			]);
			return;
		}

		$horario = Empresas_Horarios_Model::Load([
			'select' => 'id',
			'result' => '1row',
			'where' => 'id=' . $horario_id . ' AND empresa_id=' . intval($extra->empresa_id) . ' AND status=1'
		]);
		if (!$horario) {
			$this->output_json([
				'status' => 'error',
				'mensaje' => 'El horario seleccionado no pertenece a la empresa.'
			]);
			return;
		}

		$duplicado = Empresas_Horarios_Cubiertos_Extras_Model::Load([
			'select' => 'id',
			'result' => '1row',
			'where' => 'id!=' . $id .
				' AND empresa_id=' . intval($extra->empresa_id) .
				' AND puesto_id=' . intval($extra->puesto_id) .
				' AND sede_id=' . intval($extra->sede_id) .
				' AND horario_id=' . $horario_id .
				' AND colaborador_id=' . intval($extra->colaborador_id) .
				" AND fecha='" . addslashes($extra->fecha) . "'"
		]);
		if ($duplicado) {
			$this->output_json([
				'status' => 'error',
				'mensaje' => 'Ya existe una asignacion extra con ese horario.'
			]);
			return;
		}

		$result = Empresas_Horarios_Cubiertos_Extras_Model::Update([
			'horario_id' => $horario_id
		], 'id=' . $id);

		$this->output_json([
			'status' => !empty($result['result']) ? 'ok' : 'error',
			'mensaje' => !empty($result['result']) ? '' : 'No fue posible actualizar el horario.'
		]);
	}
	function confirmar_extra_dl()
	{
		$id = intval($this->input->post('id'));
		$confirmado = intval($this->input->post('confirmado')) === 1 ? 1 : 0;
		if ($id <= 0) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'Debes enviar un extra valido.'
			]);
			return;
		}

		$extra = Empresas_Horarios_Cubiertos_Extras_Model::Load([
			'select' => '*',
			'result' => '1row',
			'where' => 'id=' . $id
		]);
		if (!$extra) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'No se encontro el extra.'
			]);
			return;
		}

		$horario_cubierto = $this->get_horario_cubierto($extra->empresa_id, $extra->sede_id, $extra->fecha);
		if ($horario_cubierto && intval($horario_cubierto->finalizado) === 1) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.'
			]);
			return;
		}

		$result = Empresas_Horarios_Cubiertos_Extras_Model::Update([
			'confirmar_dl' => $confirmado
		], 'id=' . $id);

		$this->output_json([
			'status' => !empty($result['result'])
		]);
	}
	function eliminar_extra()
	{
		$id = $this->input->post("id");
		$extra = Empresas_Horarios_Cubiertos_Extras_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			'where' => 'id=' . intval($id)
		));
		if ($extra) {
			$horario_cubierto = $this->get_horario_cubierto($extra->empresa_id, $extra->sede_id, $extra->fecha);
			if ($horario_cubierto && intval($horario_cubierto->finalizado) === 1) {
				$this->output_json([
					'status' => 'error',
					'mensaje' => 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.'
				]);
				return;
			}
		}
		Empresas_Horarios_Cubiertos_Extras_Model::Delete('id=' . $id);
		$this->output_json(['status' => 'ok']);
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
						SUM(CASE WHEN empresas_horarios_cubiertos_detalle.colaborador_id = 0 or empresas_horarios_cubiertos_detalle.asistencia_tipo_id = 2  THEN 1 ELSE 0 END) AS faltas,
						SUM(CASE WHEN empresas_horarios_cubiertos_detalle.colaborador_id != 0 and empresas_horarios_cubiertos_detalle.asistencia_tipo_id != 2 THEN 1 ELSE 0 END) AS asistencias,
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
				<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-eye"></i></button>';
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
