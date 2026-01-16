<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Reportes extends ANT_Controller
{
	public $valores_asistencias = ['A', 'D', 'DL', 'PCG'];
	public $dias = [
		'Monday'    => 'lunes',
		'Tuesday'   => 'martes',
		'Wednesday' => 'miercoles',
		'Thursday'  => 'jueves',
		'Friday'    => 'viernes',
		'Saturday'  => 'sabado',
		'Sunday'    => 'domingo',
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
	function asistencias()
	{
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$where = "";
		if ($user_role_id > 2) {
			$where = "empresas.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		$data['empresas'] = Empresas_Model::get_select($where);
		$data['title'] = 'Reporte Asistencias';
		$data['view'] = 'reportes/Asistencias';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Reportes/asistencias', $data);
	}
	function asistencias_facturacion()
	{
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$where = "";
		if ($user_role_id > 2) {
			$where = "empresas.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		$data['empresas'] = Empresas_Model::get_select($where);
		$data['title'] = 'Reporte Asistencias-Facturacion';
		$data['view'] = 'reportes/Asistencias_Facturacion';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Reportes/asistencias_facturacion', $data);
	}
	function get_asistencias_report()
	{
		$start_date = $this->input->post('fecha_inicio');
		$end_date = $this->input->post('fecha_fin');
		$empresa_id = $this->input->post('empresa_id');
		$sede_id = $this->input->post('sede_id');
		$period = new DatePeriod(
			new DateTime($start_date),
			new DateInterval('P1D'),
			(new DateTime($end_date))->modify('+1 day')
		);
		$where = '';
		if ($empresa_id != 'Seleccionar empresa') {
			$where .= ' AND e.id=' . $empresa_id;
		}
		if ($sede_id != 'Seleccionar sede') {
			$where .= ' AND ecc.sede_id=' . $sede_id;
		}
		$razon_social_id = $this->input->post('razon_social');
		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$where .= ' AND e.razon_social_id=' . $razon_social_id;
		}
		$registros = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
			'select' => "empresas_horarios_cubiertos_detalle.*,c.*, p.nombre as puesto, ecc.fecha, 
			concat(at.prefijo) as asistencia_tipo, e.nombre as empresa, es.nombre as sede, 
			eh.nombre as horario, eh.lunes, eh.martes, eh.miercoles, eh.jueves, eh.viernes, eh.sabado, eh.domingo",
			'where' => "ecc.fecha >= '$start_date' AND ecc.fecha <= '$end_date' AND c.id is not null " . $where,
			'joinsLeft' => array(
				'empresas_horarios_cubiertos ecc' => 'ecc.id=empresas_horarios_cubiertos_detalle.horario_cubierto_id',
				'empresas_puestos_horarios as ep' => 'ep.id=empresas_horarios_cubiertos_detalle.puesto_horario_id',
				'puestos as p' => 'p.id=ep.puesto_id',
				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id',
				'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_detalle.colaborador_id',
				'empresas_horarios as eh' => 'eh.id=c.horario_id',
				'empresas as e' => 'e.id=c.cliente',
				'empresas_sedes as es' => 'es.id=ecc.sede_id'
			),
			'sortBy' => 'c.codigo, ecc.fecha',
			'sortDirection' => 'ASC',
			'result' => 'array'
		));
		$reporte = [];
		foreach ($registros as $r) {
			$codigo = $r['codigo'];
			if (!isset($reporte[$codigo])) {
				$reporte[$codigo] = [
					'codigo' => $codigo,
					'nombre' => $r['nombre'],
					'apellido_paterno' => $r['apellido_paterno'],
					'apellido_materno' => $r['apellido_materno'],
					'puesto' => $r['puesto'],
					'nomina_nomipaq' => $r['nomina_nomipaq'],
					'empresa' => $r['empresa'],
					'sede' => $r['sede'],
					'horario' => $r['horario'],
					'lunes' => $r['lunes'],
					'martes' => $r['martes'],
					'miercoles' => $r['miercoles'],
					'jueves' => $r['jueves'],
					'viernes' => $r['viernes'],
					'sabado' => $r['sabado'],
					'domingo' => $r['domingo'],
					'asistencias' => []
				];
			}
			$reporte[$codigo]['asistencias'][$r['fecha']] = $r['asistencia_tipo'];
		}
		$data['head'] = '
		<tr>
			<th>Departamento</th>
			<th>Sede</th>
			<th>Horario</th>
			<th>Codigo de empleado</th>
			<th>Tipo de Nómina</th>
			<th>Nombre</th>
			<th>Puesto</th>';
		$dias_es = $this->dias;
		foreach ($period as $date) {
			$dia_en = $date->format('l'); // Día en inglés
			$nombre_dia = $dias_es[$dia_en]; // Traducción
			$data['head'] .= '<th>' . $nombre_dia . '<br>' . $date->format('d/m/Y') . '</th>';
		}
		$data['head'] .= '<th>Comentarios</th></tr>';
		$data['table'] = '';
		foreach ($reporte as $r) {
			$data['table'] .= '<tr>
			<td>' . $r['empresa'] . '</td>
			<td>' . $r['sede'] . '</td>
			<td>' . $r['horario'] . '</td>
			<td>' . $r['codigo'] . '</td>
			<td>' . $r['nomina_nomipaq'] . '</td>
			<td>' . $r['nombre'] . ' ' . $r['apellido_paterno'] . '</td>
			<td>' . $r['puesto'] . '</td>';

			foreach ($period as $date) {
				$fecha = $date->format('Y-m-d');
				$dia_en = $date->format('l');
				$nombre_dia = strtolower($dias_es[$dia_en]); // e.g., "lunes", "martes"

				$valor = isset($r['asistencias'][$fecha]) ? $r['asistencias'][$fecha] : '';

				if ($valor === '' && array_key_exists($nombre_dia, $r) && is_null($r[$nombre_dia])) {
					$valor = 'D';
				}

				$clase = "";
				switch ($valor) {
					case "F":
						$clase = 'style="background-color:#F7C7AC;"';
						break;
					case "VAC":
						$clase = 'style="background-color:#FFFF00;"';
						break;
					case "DL":
						$clase = 'style="background-color:#DAE9F8;"';
						break;
				}
				if ($valor == null) {
					$valor = 'Falta Información';
				}

				$data['table'] .= '<td ' . $clase . '>' . $valor . '</td>';
			}
			$data['table'] .= '<td></td></tr>';
		}
		$this->output_json($data);
	}
	function get_asistencias_facturacion_report()
	{
		$start_date = $this->input->post('fecha_inicio');
		$end_date = $this->input->post('fecha_fin');
		$empresa_id = $this->input->post('empresa_id');
		$sede_id = $this->input->post('sede_id');
		$period = new DatePeriod(
			new DateTime($start_date),
			new DateInterval('P1D'),
			(new DateTime($end_date))->modify('+1 day')
		);
		$where = '';
		if ($empresa_id != 'Seleccionar empresa') {
			$where .= ' AND e.id=' . $empresa_id;
		}
		if ($sede_id != 'Seleccionar sede') {
			$where .= ' AND ecc.sede_id=' . $sede_id;
		}
		$razon_social_id = $this->input->post('razon_social');
		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$where .= ' AND e.razon_social_id=' . $razon_social_id;
		}
		$registros = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
			'select' => "empresas_horarios_cubiertos_detalle.*,c.*, p.nombre as puesto, ecc.fecha, 
			concat(at.prefijo) as asistencia_tipo, e.nombre as empresa, es.nombre as sede, 
			eh.nombre as horario, eh.lunes, eh.martes, eh.miercoles, eh.jueves, eh.viernes, eh.sabado, eh.domingo,
			ep.salario_diario, ep.sueldo_neto_semanal,ep.costo_unitario, ep.costo_por_dia, ep.costo_descanso_laborado,
			ep.costo_dia_festivo, ep.costo_hora_extra",
			'where' => "ecc.fecha >= '$start_date' AND ecc.fecha <= '$end_date' AND c.id is not null " . $where,
			'joinsLeft' => array(
				'empresas_horarios_cubiertos ecc' => 'ecc.id=empresas_horarios_cubiertos_detalle.horario_cubierto_id',
				'empresas_puestos_horarios as ep' => 'ep.id=empresas_horarios_cubiertos_detalle.puesto_horario_id',
				'puestos as p' => 'p.id=ep.puesto_id',
				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id',
				'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_detalle.colaborador_id',
				'empresas_horarios as eh' => 'eh.id=c.horario_id',
				'empresas as e' => 'e.id=c.cliente',
				'empresas_sedes as es' => 'es.id=ecc.sede_id'
			),
			'sortBy' => 'c.codigo, ecc.fecha',
			'sortDirection' => 'ASC',
			'result' => 'array'
		));
		$reporte = [];
		foreach ($registros as $r) {
			$codigo = $r['codigo'];
			if (!isset($reporte[$codigo])) {
				$reporte[$codigo] = [
					'codigo' => $codigo,
					'nombre' => $r['nombre'],
					'apellido_paterno' => $r['apellido_paterno'],
					'apellido_materno' => $r['apellido_materno'],
					'puesto' => $r['puesto'],
					'nomina_nomipaq' => $r['nomina_nomipaq'],
					'empresa' => $r['empresa'],
					'sede' => $r['sede'],
					'horario' => $r['horario'],
					'lunes' => $r['lunes'],
					'martes' => $r['martes'],
					'miercoles' => $r['miercoles'],
					'jueves' => $r['jueves'],
					'viernes' => $r['viernes'],
					'sabado' => $r['sabado'],
					'domingo' => $r['domingo'],
					'salario_diario' => $r['salario_diario'],
					'sueldo_neto_semanal' => $r['sueldo_neto_semanal'],
					'costo_unitario' => $r['costo_unitario'],
					'costo_por_dia' => $r['costo_por_dia'],
					'costo_descanso_laborado' => $r['costo_descanso_laborado'],
					'costo_dia_festivo' => $r['costo_dia_festivo'],
					'costo_hora_extra' => $r['costo_hora_extra'],
					'asistencias' => []
				];
			}
			$reporte[$codigo]['asistencias'][$r['fecha']] = $r['asistencia_tipo'];
		}
		$data['head'] = '
		<tr>
			<th>Departamento</th>
			<th>Sede</th>
			<th>Horario</th>
			<th>Codigo de empleado</th>
			<th>Tipo de Nómina</th>
			<th>Nombre</th>
			<th>Puesto</th>';
		$dias_es = $this->dias;
		foreach ($period as $date) {
			$dia_en = $date->format('l'); // Día en inglés
			$nombre_dia = $dias_es[$dia_en]; // Traducción
			$data['head'] .= '<th>' . $nombre_dia . '<br>' . $date->format('d/m/Y') . '</th>';
		}
		$data['head'] .= '<th>Total Asistencias</th>
		<th>Total Faltas</th>
		<th>Total Descuento</th>
		<th>Total DL</th>
		<th>Total Festivo</th>
		<th>Total Hora Extra</th>
		</tr>';
		$data['table'] = '';

		foreach ($reporte as $r) {
			$data['table'] .= '<tr>
			<td>' . $r['empresa'] . '</td>
			<td>' . $r['sede'] . '</td>
			<td>' . $r['horario'] . '</td>
			<td>' . $r['codigo'] . '</td>
			<td>' . $r['nomina_nomipaq'] . '</td>
			<td>' . $r['nombre'] . ' ' . $r['apellido_paterno'] . '</td>
			<td>' . $r['puesto'] . '</td>';
			$asistencias_count = 0;
			$dl_count = 0;
			$faltas_count = 0;
			foreach ($period as $date) {
				$fecha = $date->format('Y-m-d');
				$dia_en = $date->format('l');
				$nombre_dia = strtolower($dias_es[$dia_en]); // e.g., "lunes", "martes"

				$valor = isset($r['asistencias'][$fecha]) ? $r['asistencias'][$fecha] : '';

				if ($valor === '' && array_key_exists($nombre_dia, $r) && is_null($r[$nombre_dia])) {
					$valor = 'D';
				}

				$clase = "";
				switch ($valor) {
					case "F":
						$clase = 'style="background-color:#F7C7AC;"';
						break;
					case "VAC":
						$clase = 'style="background-color:#FFFF00;"';
						break;
					case "DL":
						$clase = 'style="background-color:#DAE9F8;"';
						break;
				}
				if (in_array($valor, $this->valores_asistencias)) {
					$asistencias_count++;
				} else {
					$faltas_count++;
				}
				if ($valor == 'DL') {
					$dl_count++;
				}
				if ($valor == null) {
					$valor = 'Falta Información';
				}

				$data['table'] .= '<td ' . $clase . '>' . $valor . '</td>';
			}
			$data['table'] .= '<td>' . $asistencias_count . '</td>
			<td>' . $faltas_count . '</td>
			<td>' . number_format($faltas_count * $r['costo_por_dia'], 2) . '</td>
			<td>' . number_format($dl_count * $r['costo_descanso_laborado'], 2) . '</td>
			<td>' . number_format(0, 2) . '</td>
			<td>' . number_format(0, 2) . '</td>
			</tr>';
		}
		$this->output_json($data);
	}
	public function get_asistencias_totales()
	{
		$start_date = $this->input->post('fecha_inicio');
		$end_date = $this->input->post('fecha_fin');
		$empresa_id = $this->input->post('empresa_id');
		$sede_id = $this->input->post('sede_id');

		$period = new DatePeriod(
			new DateTime($start_date),
			new DateInterval('P1D'),
			(new DateTime($end_date))->modify('+1 day')
		);

		$where = '';
		if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
			$where .= ' AND e.id=' . $empresa_id;
		}
		if (!empty($sede_id) && $sede_id !== 'Seleccionar sede') {
			$where .= ' AND ecc.sede_id=' . $sede_id;
		}
		$razon_social_id = $this->input->post('razon_social');
		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$where .= ' AND e.razon_social_id=' . $razon_social_id;
		}

		$registros = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
			'select' => "empresas_horarios_cubiertos_detalle.*, c.codigo, c.nombre, c.apellido_paterno, c.apellido_materno, 
			e.nombre as empresa, es.nombre as sede, at.prefijo as asistencia_tipo, 
			eh.lunes, eh.martes, eh.miercoles, eh.jueves, eh.viernes, eh.sabado, eh.domingo, ecc.fecha",
			'where' => "ecc.fecha >= '$start_date' AND ecc.fecha <= '$end_date' AND c.id IS NOT NULL " . $where,
			'joinsLeft' => array(
				'empresas_horarios_cubiertos ecc' => 'ecc.id=empresas_horarios_cubiertos_detalle.horario_cubierto_id',
				'empresas_puestos_horarios as ep' => 'ep.id=empresas_horarios_cubiertos_detalle.puesto_horario_id',
				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id',
				'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_detalle.colaborador_id',
				'empresas_horarios as eh' => 'eh.id=c.horario_id',
				'empresas as e' => 'e.id=c.cliente',
				'empresas_sedes as es' => 'es.id=ecc.sede_id'
			),
			'result' => 'array'
		));

		$dias_es = $this->dias;

		$total_asistencias = 0;
		$total_faltas = 0;
		$valores_asistencia = $this->valores_asistencias;
		if ($registros) {
			foreach ($registros as $r) {
				$fecha = $r['fecha'];
				$valor = $r['asistencia_tipo'];
				$dia_en = (new DateTime($fecha))->format('l');
				$nombre_dia = $dias_es[$dia_en];

				if ($valor === '' && is_null($r[$nombre_dia])) {
					$valor = 'D';
				}

				if (in_array($valor, $valores_asistencia)) {
					$total_asistencias++;
				} elseif ($valor === 'F') {
					$total_faltas++;
				}
			}
		}
		$this->output_json([
			'asistencias' => $total_asistencias,
			'faltas' => $total_faltas
		]);
	}
	public function get_asistencias_detalle()
	{
		$start_date = $this->input->post('fecha_inicio');
		$end_date = $this->input->post('fecha_fin');
		$empresa_id = $this->input->post('empresa_id');
		$sede_id = $this->input->post('sede_id');
		$tipo = $this->input->post('tipo');

		$period = new DatePeriod(
			new DateTime($start_date),
			new DateInterval('P1D'),
			(new DateTime($end_date))->modify('+1 day')
		);

		$where = '';
		if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
			$where .= ' AND e.id=' . $empresa_id;
		}
		if (!empty($sede_id) && $sede_id !== 'Seleccionar sede') {
			$where .= ' AND ecc.sede_id=' . $sede_id;
		}
		$razon_social_id = $this->input->post('razon_social');
		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$where .= ' AND e.razon_social_id=' . $razon_social_id;
		}

		$registros = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
			'select' => "empresas_horarios_cubiertos_detalle.*,c.*, p.nombre as puesto, ecc.fecha, 
			concat(at.prefijo) as asistencia_tipo, e.nombre as empresa, es.nombre as sede, 
			eh.nombre as horario, eh.lunes, eh.martes, eh.miercoles, eh.jueves, eh.viernes, eh.sabado, eh.domingo",
			'where' => "ecc.fecha >= '$start_date' AND ecc.fecha <= '$end_date' AND c.id is not null " . $where,
			'joinsLeft' => array(
				'empresas_horarios_cubiertos ecc' => 'ecc.id=empresas_horarios_cubiertos_detalle.horario_cubierto_id',
				'empresas_puestos_horarios as ep' => 'ep.id=empresas_horarios_cubiertos_detalle.puesto_horario_id',
				'puestos as p' => 'p.id=ep.puesto_id',

				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id',
				'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_detalle.colaborador_id',
				'empresas_horarios as eh' => 'eh.id=c.horario_id',
				'empresas as e' => 'e.id=c.cliente',
				'empresas_sedes as es' => 'es.id=ecc.sede_id'
			),
			'sortBy' => 'c.codigo, ecc.fecha',
			'sortDirection' => 'ASC',
			'result' => 'array'
		));

		$dias_es = $this->dias;

		$total_asistencias = 0;
		$total_faltas = 0;
		$valores_asistencia = $this->valores_asistencias;
		$data['table'] = '';
		$data['head'] = '
		<tr>
			<th>Departamento</th>
			<th>Sede</th>
			<th>Horario</th>
			<th>Codigo de empleado</th>
			<th>Tipo de Nómina</th>
			<th>Nombre</th>
			<th>Puesto</th>
			<th>Fecha</th>
			</tr>';
		if ($registros) {
			foreach ($registros as $r) {
				$fecha = $r['fecha'];
				$valor = $r['asistencia_tipo'];
				$dia_en = (new DateTime($fecha))->format('l');
				$nombre_dia = $dias_es[$dia_en];

				if ($valor === '' && is_null($r[$nombre_dia])) {
					$valor = 'D';
				}

				if ((in_array($valor, $valores_asistencia) && $tipo != 'Faltas') || $valor === 'F' && $tipo === 'Faltas') {
					$data['table'] .= '<tr>
			<td>' . $r['empresa'] . '</td>
			<td>' . $r['sede'] . '</td>
			<td>' . $r['horario'] . '</td>
			<td>' . $r['codigo'] . '</td>
			<td>' . $r['nomina_nomipaq'] . '</td>
			<td>' . $r['nombre'] . ' ' . $r['apellido_paterno'] . '</td>
			<td>' . $r['puesto'] . '</td>
			<td>' . $r['fecha'] . '</td>
			</tr>';
				}
			}
		}
		$this->output_json($data);
	}
	public function get_facturados_totales()
	{
		$start_date = $this->input->post('fecha_inicio');
		$end_date = $this->input->post('fecha_fin');
		$empresa_id = $this->input->post('empresa_id');
		$sede_id = $this->input->post('sede_id');

		$period = new DatePeriod(
			new DateTime($start_date),
			new DateInterval('P1D'),
			(new DateTime($end_date))->modify('+1 day')
		);

		$where = '';
		if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
			$where .= ' AND e.id=' . $empresa_id;
		}
		if (!empty($sede_id) && $sede_id !== 'Seleccionar sede') {
			$where .= ' AND ecc.sede_id=' . $sede_id;
		}
		$razon_social_id = $this->input->post('razon_social');
		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$where .= ' AND e.razon_social_id=' . $razon_social_id;
		}
		$registros = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
			'select' => "empresas_horarios_cubiertos_detalle.*, c.codigo, c.nombre, c.apellido_paterno, c.apellido_materno, 
			e.nombre as empresa, es.nombre as sede, at.prefijo as asistencia_tipo, 
			eh.lunes, eh.martes, eh.miercoles, eh.jueves, eh.viernes, eh.sabado, eh.domingo, ecc.fecha,
			ep.salario_diario, ep.sueldo_neto_semanal,ep.costo_unitario, ep.costo_por_dia, ep.costo_descanso_laborado,
			ep.costo_dia_festivo, ep.costo_hora_extra",
			'where' => "ecc.fecha >= '$start_date' AND ecc.fecha <= '$end_date' AND c.id IS NOT NULL " . $where,
			'joinsLeft' => array(
				'empresas_horarios_cubiertos ecc' => 'ecc.id=empresas_horarios_cubiertos_detalle.horario_cubierto_id',
				'empresas_puestos_horarios as ep' => 'ep.id=empresas_horarios_cubiertos_detalle.puesto_horario_id',
				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id',
				'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_detalle.colaborador_id',
				'empresas_horarios as eh' => 'eh.id=c.horario_id',
				'empresas as e' => 'e.id=c.cliente',
				'empresas_sedes as es' => 'es.id=ecc.sede_id'
			),
			'result' => 'array'
		));

		$dias_es = $this->dias;

		$total_asistencias = 0;
		$total_faltas = 0;
		$valores_asistencia = $this->valores_asistencias;
		if ($registros) {
			foreach ($registros as $r) {

				$fecha = $r['fecha'];
				$valor = $r['asistencia_tipo'];
				$dia_en = (new DateTime($fecha))->format('l');
				$nombre_dia = $dias_es[$dia_en];


				if ($valor === '' && is_null($r[$nombre_dia])) {
					$valor = 'D';
				}

				if (in_array($valor, $valores_asistencia)) {
					$total_asistencias += number_format(1 * $r['costo_por_dia'], 2);
				} elseif ($valor === 'F') {
					$total_faltas += number_format(1 * $r['costo_por_dia'], 2);
				}
			}
		}
		$this->output_json([
			'facturado' => $total_asistencias,
			'noFacturado' => $total_faltas
		]);
	}
	public function get_facturados_detalle()
	{
		$start_date = $this->input->post('fecha_inicio');
		$end_date = $this->input->post('fecha_fin');
		$empresa_id = $this->input->post('empresa_id');
		$sede_id = $this->input->post('sede_id');
		$tipo = $this->input->post('tipo');

		$period = new DatePeriod(
			new DateTime($start_date),
			new DateInterval('P1D'),
			(new DateTime($end_date))->modify('+1 day')
		);

		$where = '';
		if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
			$where .= ' AND e.id=' . $empresa_id;
		}
		if (!empty($sede_id) && $sede_id !== 'Seleccionar sede') {
			$where .= ' AND ecc.sede_id=' . $sede_id;
		}
		$razon_social_id = $this->input->post('razon_social');
		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$where .= ' AND e.razon_social_id=' . $razon_social_id;
		}

		$registros = Empresas_Horarios_Cubiertos_Detalle_Model::Load(array(
			'select' => "empresas_horarios_cubiertos_detalle.*,c.*, p.nombre as puesto, ecc.fecha, 
			concat(at.prefijo) as asistencia_tipo, e.nombre as empresa, es.nombre as sede, 
			eh.nombre as horario, eh.lunes, eh.martes, eh.miercoles, eh.jueves, eh.viernes, eh.sabado, eh.domingo,
			ep.salario_diario, ep.sueldo_neto_semanal,ep.costo_unitario, ep.costo_por_dia, ep.costo_descanso_laborado,
			ep.costo_dia_festivo, ep.costo_hora_extra",
			'where' => "ecc.fecha >= '$start_date' AND ecc.fecha <= '$end_date' AND c.id is not null " . $where,
			'joinsLeft' => array(
				'empresas_horarios_cubiertos ecc' => 'ecc.id=empresas_horarios_cubiertos_detalle.horario_cubierto_id',
				'empresas_puestos_horarios as ep' => 'ep.id=empresas_horarios_cubiertos_detalle.puesto_horario_id',
				'puestos as p' => 'p.id=ep.puesto_id',

				'asistencias_tipos as at' => 'at.id=empresas_horarios_cubiertos_detalle.asistencia_tipo_id',
				'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_detalle.colaborador_id',
				'empresas_horarios as eh' => 'eh.id=c.horario_id',
				'empresas as e' => 'e.id=c.cliente',
				'empresas_sedes as es' => 'es.id=ecc.sede_id'
			),
			'sortBy' => 'c.codigo, ecc.fecha',
			'sortDirection' => 'ASC',
			'result' => 'array'
		));

		$dias_es = $this->dias;

		$total_asistencias = 0;
		$total_faltas = 0;
		$valores_asistencia = $this->valores_asistencias;
		$data['table'] = '';
		$data['head'] = '
		<tr>
			<th>Departamento</th>
			<th>Sede</th>
			<th>Horario</th>
			<th>Codigo de empleado</th>
			<th>Tipo de Nómina</th>
			<th>Nombre</th>
			<th>Puesto</th>
			<th>Fecha</th>
			<th>Costo</th>
			</tr>';
		if ($registros) {
			foreach ($registros as $r) {
				$fecha = $r['fecha'];
				$valor = $r['asistencia_tipo'];
				$dia_en = (new DateTime($fecha))->format('l');
				$nombre_dia = $dias_es[$dia_en];

				if ($valor === '' && is_null($r[$nombre_dia])) {
					$valor = 'D';
				}

				if ((in_array($valor, $valores_asistencia) && $tipo != 'Faltas') || $valor === 'F' && $tipo === 'Faltas') {
					$data['table'] .= '<tr>
			<td>' . $r['empresa'] . '</td>
			<td>' . $r['sede'] . '</td>
			<td>' . $r['horario'] . '</td>
			<td>' . $r['codigo'] . '</td>
			<td>' . $r['nomina_nomipaq'] . '</td>
			<td>' . $r['nombre'] . ' ' . $r['apellido_paterno'] . '</td>
			<td>' . $r['puesto'] . '</td>
			<td>' . $r['fecha'] . '</td>
			<td>$' .  number_format($r['costo_por_dia'], 2) . '</td>
			</tr>';
				}
			}
		}
		$this->output_json($data);
	}
	public function get_hc_totales()
	{
		$empresa_id = $this->input->post('empresa_id');
		$sede_id = $this->input->post('sede_id');

		$where = '';
		if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
			$where .= ' AND empresas_puestos_horarios.empresa_id=' . $empresa_id;
		}
		if (!empty($sede_id) && $sede_id !== 'Seleccionar sede') {
			$where .= ' AND empresas_puestos_horarios.sede_id=' . $sede_id;
		}
		$razon_social_id = $this->input->post('razon_social');
		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$where .= ' AND e.razon_social_id=' . $razon_social_id;
		}

		$puestos = Empresas_Puestos_Horarios_Model::Load(array(
			'select' => "empresas_puestos_horarios.empresa_id, empresas_puestos_horarios.horario_id, SUM(empresas_puestos_horarios.cantidad) as cantidad",
			'where' => "empresas_puestos_horarios.status=1 " . $where,
			'joinsLeft' => array(
				'empresas as e' => 'e.id=empresas_puestos_horarios.empresa_id',
			),
			'groupBy' => "empresas_puestos_horarios.empresa_id,empresas_puestos_horarios.horario_id",
			'result' => 'array'
		));

		$total_requeridos = 0;
		$total_cubiertos = 0;

		if ($puestos) {
			foreach ($puestos as $puesto) {
				$empresa_id = $puesto['empresa_id'];
				$horario_id = $puesto['horario_id'];
				$cantidad = (int)$puesto['cantidad'];
				$total_requeridos += $cantidad;

				// Obtener colaboradores activos en ese departamento
				$colaboradores = Colaboradores_Model::Load(array(
					'select' => "COUNT(*) as total",
					'where' => "status=1 AND cliente=" . $empresa_id . " AND horario_id=" . $horario_id,
					'result' => '1row'
				));
				$totalColaboradores = $colaboradores ? (int)$colaboradores->total : 0;
				$total_cubiertos += $totalColaboradores > $cantidad ? $cantidad : $totalColaboradores;
			}
		}

		$vacantes = max(0, $total_requeridos - $total_cubiertos);

		$this->output_json([
			'total_puestos' => $total_requeridos,
			'cubiertos' => $total_cubiertos,
			'vacantes' => $vacantes
		]);
	}
	public function get_hc_detalle()
	{
		$empresa_id = $this->input->post('empresa_id');
		$sede_id = $this->input->post('sede_id');
		$tipo = $this->input->post('tipo');
		if ($tipo === "Vacantes") {
			$where = '';
			if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
				$where .= ' AND empresas_puestos_horarios.empresa_id=' . $empresa_id;
			}
			if (!empty($sede_id) && $sede_id !== 'Seleccionar sede') {
				$where .= ' AND empresas_puestos_horarios.sede_id=' . $sede_id;
			}
			$razon_social_id = $this->input->post('razon_social');
			if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
				$where .= ' AND e.razon_social_id=' . $razon_social_id;
			}

			$puestos = Empresas_Puestos_Horarios_Model::Load(array(
				'select' => "empresas_puestos_horarios.empresa_id, e.nombre as empresa_name, eh.nombre as horario_name, empresas_puestos_horarios.horario_id, SUM(empresas_puestos_horarios.cantidad) as cantidad",
				'where' => "empresas_puestos_horarios.status=1 " . $where,
				'joinsLeft' => array(
					'empresas as e' => 'e.id=empresas_puestos_horarios.empresa_id',
					'empresas_horarios as eh' => 'eh.id=empresas_puestos_horarios.horario_id',
				),
				'groupBy' => "empresas_puestos_horarios.empresa_id,empresas_puestos_horarios.horario_id, e.nombre, eh.nombre",
				'result' => 'array'
			));

			$total_requeridos = 0;
			$total_cubiertos = 0;
			$data['head'] = "<tr>
			<th>Horario</th>
			<th>Empresa</th>
			<th>Cantidad vacantes</th>
			</tr>";
			$data['table'] = '';
			if ($puestos) {
				foreach ($puestos as $puesto) {
					$empresa_id = $puesto['empresa_id'];
					$horario_id = $puesto['horario_id'];
					$cantidad = (int)$puesto['cantidad'];
					// Obtener colaboradores activos en ese departamento
					$colaboradores = Colaboradores_Model::Load(array(
						'select' => "COUNT(*) as total",
						'where' => "status=1 AND cliente=" . $empresa_id . " AND horario_id=" . $horario_id,
						'result' => '1row'
					));
					$totalColaboradores = $colaboradores ? (int)$colaboradores->total : 0;
					if ($cantidad > $totalColaboradores) {

						$data['table'] .= '<tr><td>' . $puesto['horario_name'] . '</td>
						<td>' . $puesto['empresa_name'] . '</td>
						<td>' . ($cantidad - $totalColaboradores) . '</td>
						</tr>';
					}
				}
			}
		} else {
			$where = '';
			if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
				$where .= ' AND empresas_puestos_horarios.empresa_id=' . $empresa_id;
			}
			$aux = Colaboradores_Model::Load(array(
				'select' => "*",
				'where' => "status=1 " . $where,
				'result' => 'array'
			));

			$data['head'] = "<tr><th>Código</th>
		<th>Nombre</th>
		<th>Apellido Paterno</th>
		<th>Apellido Materno</th>
		</tr>";
			$data['table'] = '';

			if ($aux) {
				foreach ($aux as $a) {
					$data['table'] .= '<tr><td>' . $a['codigo'] . '</td>
				<td>' . $a['nombre'] . '</td>
				<td>' . $a['apellido_paterno'] . '</td>
				<td>' . $a['apellido_materno'] . '</td>
				</tr>';
				}
			}
		}
		$this->output_json($data);
	}
	public function get_satisfaccion_encuestas()
	{
		$start_date = $this->input->post('fecha_inicio');
		$end_date = $this->input->post('fecha_fin');
		$empresa_id = $this->input->post('empresa_id');
		$razon_social_id = $this->input->post('razon_social');

		$where = '';

		if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
			$empresa_id = (int)$empresa_id;
			$where .= " AND e.id = $empresa_id";
		}

		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$razon_social_id = (int)$razon_social_id;
			$where .= " AND e.razon_social_id = $razon_social_id";
		}

		$query = "
        SELECT
            IFNULL(
                AVG(
                    CAST(
                        CASE WHEN ep.tipo IN (2,4) THEN eprd.valor * 2 ELSE eprd.valor END 
                    AS DECIMAL
                    ) / 10
                ) * 100,
            0
            ) AS promedio
        FROM encuestas_preguntas_respuestas_detalle eprd
        INNER JOIN encuestas_preguntas_respuestas epr ON epr.id = eprd.encuestas_preguntas_respuesta_id
        INNER JOIN empresas e ON e.id = epr.empresa_id
        INNER JOIN encuestas_preguntas ep ON ep.id = eprd.encuestas_pregunta_id
        WHERE 
		CAST(eprd.valor AS UNSIGNED) BETWEEN 0 AND 11 AND 
		eprd.created_at >= '$start_date' AND
		 eprd.created_at <= '$end_date' 
        $where
    ";
		//--eprd.valor REGEXP '^[0-9]+$' AND 
		$data = Encuestas_Model::Query($query);

		$promedio = isset($data[0]['promedio']) ? floatval($data[0]['promedio']) : 0;

		$this->output_json($promedio);
	}

	public function get_graficas_encuestas()
	{
		$start_date = $this->input->post('fecha_inicio');
		$end_date = $this->input->post('fecha_fin');
		$empresa_id = $this->input->post('empresa_id');
		$razon_social_id = $this->input->post('razon_social');

		$period = new DatePeriod(
			new DateTime($start_date),
			new DateInterval('P1D'),
			(new DateTime($end_date))->modify('+1 day')
		);

		$where = '';
		if (!empty($empresa_id) && $empresa_id !== 'Seleccionar empresa') {
			$where .= ' AND e.id=' . $empresa_id;
		}
		if (!empty($razon_social_id) && $razon_social_id !== 'Seleccionar empresa') {
			$where .= ' AND e.razon_social_id=' . $razon_social_id;
		}

		$data = Encuestas_Model::Query("SELECT
			ep.id AS pregunta_id,
			ep.pregunta AS pregunta,
			AVG(
				CAST(
					CASE WHEN ep.tipo IN (2,4) THEN eprd.valor * 2 ELSE eprd.valor END 
				AS DECIMAL)
				/
				10
			) * 100 AS promedio
		FROM encuestas_preguntas_respuestas_detalle eprd
		INNER JOIN encuestas_preguntas_respuestas epr ON epr.id = eprd.encuestas_preguntas_respuesta_id
		INNER JOIN empresas e ON e.id = epr.empresa_id
		INNER JOIN encuestas_preguntas ep ON ep.id = eprd.encuestas_pregunta_id
		WHERE eprd.valor REGEXP '^[0-9]+$'
		AND CAST(eprd.valor AS UNSIGNED) BETWEEN 1 AND 10
		AND eprd.created_at >= '$start_date'
		AND eprd.created_at <= '$end_date'
		$where
		GROUP BY ep.id, ep.pregunta

		");
		$this->output_json($data);
	}
}
