<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Empresas extends ANT_Controller
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
		$data['title'] = 'Empresas';
		$data['view'] = 'grids/Empresas';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Empresas/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Vehiculo';
		$data['view'] = 'forms/Empresas';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['nominas'] = Tipos_Nominas_Model::get_select();
		$data['facturacion'] = Tipos_Facturacion_Model::get_select();
		$data['zonas'] = Zonas_Model::get_select();
		$data['municipios'] = Municipios_Model::get_select();
		$data['responsables'] = Users_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Empresas/add', $data);
	}
	function assign()
	{
		$data['title'] = 'Asignar Empresas';
		$data['view'] = 'forms/Empresas_assign';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['empresas'] = Empresas_Model::get_select();
		$data['usuarios'] = Users_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Empresas/assign', $data);
	}
	function edit($id)
	{
		$data['title'] = 'Editar Colonia';
		$data['view'] = 'forms/Empresas';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['puestos'] = Puestos_Model::get_select();
		$data['nominas'] = Tipos_Nominas_Model::get_select();
		$data['facturacion'] = Tipos_Facturacion_Model::get_select();
		$data['zonas'] = Zonas_Model::get_select();
		$data['municipios'] = Municipios_Model::get_select();
		$data['responsables'] = Users_Model::get_select();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Empresas/edit', $data);
	}
	function get_info_Empresas()
	{
		$post = $this->input->post();
		$data = Empresas_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Empresas()
	{
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$where = "";
		if ($user_role_id > 2) {
			$where = "empresas.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		$aux = Empresas_Model::get_grid_info($where);
		$data['head'] = "<tr>
		<th>Nombre cliente</th>
		<th>Empresa</th>
		<th>Responsable</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>
		";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete permisoEdicion" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['nombre'] . '</td>
				<td>' . $a['razon_social'] . '</td>
				<td>' . $a['responsable_name'] . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
	function get_Empresas_Assign()
	{
		$aux = Empresas_Has_Users_Model::get_grid_info();
		$data['head'] = "<tr><th>Usuario</th>
		<th>Empresa</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr><td>' . $a['usuario'] . '</td>
				<td>' . $a['empresa'] . '</td>
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
			$result = Empresas_Model::Insert($post);
		} else {

			$result = Empresas_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function save_assign()
	{
		$post = $this->input->post('users');
		if ($post['empresa_id'] === '0') {
			Empresas_Has_Users_Model::Delete("user_id=" . $post['user_id']);
			$aux = Empresas_Model::Load(array(
				'select' => "empresas.*",
				'result' => 'array',
				'where' => "empresas.status=1"
			));
			if ($aux) {
				foreach ($aux as $key) {
					$result = Empresas_Has_Users_Model::Insert(['user_id' => $post['user_id'], 'empresa_id' => $key['id']]);
				}
				$this->output_json($result);
			}
		} else {
			$hasValue = Empresas_Has_Users_Model::Load(array(
				'select' => '*',
				'result' => '1row',
				"where" => "empresa_id=" . $post['empresa_id'] . " AND user_id=" . $post['user_id']
			));
			if ($hasValue) {
				$this->output_json(false);
			} else {
				$result = Empresas_Has_Users_Model::Insert($post);
				$this->output_json($result);
			}
		}
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		$result = Empresas_Model::Update(['status' => 0], "id=" . $id);
		$this->output_json($result);
	}
	function eliminar_assign()
	{
		$id = $this->input->post("id");
		Empresas_Has_Users_Model::Delete('id=' . $id);
	}
	function save_sede()
	{
		$post = $this->input->post('sede');
		$hasValue = Empresas_Sedes_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			"where" => "empresa_id=" . $post['empresa_id'] . " AND nombre='" . $post['nombre'] . "'"
		));
		if ($hasValue) {
			$this->output_json(false);
		} else {
			$result = Empresas_Sedes_Model::Insert($post);
			$this->output_json($result);
		}
	}
	function get_Empresas_sedes()
	{
		$id = $this->input->post('id');
		$aux = Empresas_Sedes_Model::get_grid_info("empresa_id=" . $id);
		$data['select'] = Empresas_Sedes_Model::get_select("empresa_id=" . $id);
		$data['head'] = "<tr><th>Nombre</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['nombre'] . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
	function get_puestos_by_sede()
	{
		$id = $this->input->post('id');
		$data['select'] = Empresas_Puestos_Horarios_Model::get_select_puesto("sede_id=" . $id);
		$this->output_json($data);
	}
	function get_horarios_by_puesto()
	{
		$id = $this->input->post('id');
		$sede_id = $this->input->post('sede_id');
		$data['select'] = Empresas_Puestos_Horarios_Model::get_select("puesto_id=" . $id . " AND sede_id=" . $sede_id);
		$this->output_json($data);
	}
	function get_datos_sueldo()
	{
		$puesto_id = $this->input->post('puesto_id');
		$sede_id = $this->input->post('sede_id');
		$horario_id = $this->input->post('horario_id');
		$data['select'] = Empresas_Puestos_Horarios_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			'where' =>
			"sede_id = $sede_id AND puesto_id = $puesto_id AND horario_id = $horario_id"
		));
		$this->output_json($data);
	}
	function save_horario()
	{
		$post = $this->input->post('horario');
		$hasValue = Empresas_Horarios_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			"where" => "empresa_id=" . $post['empresa_id'] . " AND nombre='" . $post['nombre'] . "'"
		));
		if ($hasValue) {
			$this->output_json(false);
		} else {
			$result = Empresas_Horarios_Model::Insert($post);
			$this->output_json($result);
		}
	}
	function get_Empresas_horarios()
	{
		$id = $this->input->post('id');
		$aux = Empresas_Horarios_Model::get_grid_info("empresa_id=" . $id);
		$data['select'] = Empresas_Horarios_Model::get_select("empresa_id=" . $id);

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
				<td>' . $a['nombre'] . " - " . $a['horario'] . '</td>
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
	function get_Empresas_horarios_select()
	{
		$id = $this->input->post('id');
		$data['select'] = Empresas_Horarios_Model::get_select("empresa_id=" . $id);
		$this->output_json($data);
	}

	function save_puesto()
	{
		$post = $this->input->post('puesto');
		if ($post['id'] != null && $post['id'] != 0) {
			$result = Empresas_Puestos_Horarios_Model::Update($post, "id=" . $post['id']);
			$this->output_json($result);
		} else {
			$hasValue = Empresas_Puestos_Horarios_Model::Load(array(
				'select' => '*',
				'result' => '1row',
				'where' =>
				"empresa_id = " . intval($post['empresa_id']) .
					" AND puesto_id = " . intval($post['puesto_id']) .
					" AND sede_id = " . intval($post['sede_id']) .
					" AND horario_id = " . intval($post['horario_id'])
			));
			if ($hasValue) {
				$this->output_json(false);
			} else {
				$result = Empresas_Puestos_Horarios_Model::Insert($post);
				$this->output_json($result);
			}
		}
	}

	function get_Empresas_puesto_historico()
	{
		$id = $this->input->post('id');
		$aux = Empresas_Puestos_Horarios_Precios_Model::get_grid_info("empresas_puestos_horarios_id=" . (int)$id);
		$data['head'] = "<tr>
        <th>Salario Diario</th>
        <th>Sueldo Neto Semanal</th>
        <th>Costo Unitario</th>
        <th>Costo por Día</th>
        <th>Costo por Descanso Laborado</th>
        <th>Costo por Día Festivo</th>
        <th>Costo Hora Extra</th>
    </tr>";

		$data['table'] = '';
		$fmt = function ($val) {
			if ($val === null || $val === '') return '—';

			return "$" . number_format((float)$val, 2, '.', ',');
		};

		if (!empty($aux)) {
			foreach ($aux as $a) {
				$data['table'] .= '<tr>'
					. '<td>' . $fmt($a['salario_diario'] ?? null) . '</td>'
					. '<td>' . $fmt($a['sueldo_neto_semanal'] ?? null) . '</td>'
					. '<td>' . $fmt($a['costo_unitario'] ?? null) . '</td>'
					. '<td>' . $fmt($a['costo_por_dia'] ?? null) . '</td>'
					. '<td>' . $fmt($a['costo_descanso_laborado'] ?? null) . '</td>'
					. '<td>' . $fmt($a['costo_dia_festivo'] ?? null) . '</td>'
					. '<td>' . $fmt($a['costo_hora_extra'] ?? null) . '</td>'
					. '</tr>';
			}
		} else {

			$data['table'] = '<tr><td colspan="7" style="text-align:center;">Sin registros</td></tr>';
		}

		$this->output_json($data);
	}

	function save_precio()
	{
		date_default_timezone_set('America/Mexico_City');
		$post = $this->input->post('puesto');
		if ($post['id'] != null && $post['id'] != 0) {
			$result = Empresas_Puestos_Horarios_Model::Update($post, "id=" . $post['id']);
			$post['empresas_puestos_horarios_id'] = $post['id'];
			unset($post['id']);
			$fecha = date('Y-m-d H:i:s');
			$result = Empresas_Puestos_Horarios_Precios_Model::Update(['fecha_fin' => $fecha], 'fecha_fin is null AND empresas_puestos_horarios_id=' . $post['empresas_puestos_horarios_id']);
			$result = Empresas_Puestos_Horarios_Precios_Model::Insert($post);
			$this->output_json($result);
		}
	}
	function get_Empresas_puestos()
	{
		$id = $this->input->post('id');
		$aux = Empresas_Puestos_Horarios_Model::get_grid_info("empresas_puestos_horarios.empresa_id=" . $id);
		$total_personas = 0;
		$total_cantidad = 0;

		$data['head'] = "<tr>
		<th>Puesto</th>
		<th>Horario</th>
		<th>Sede</th>
		<th>Cantidad</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$total_personas += $a['cantidad'];
				$total_cantidad += ($a['cantidad'] * $a['costo_unitario']);
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>
				<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-precio" rel="' . $a['id'] . '"><i class="fa fa-money"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['puesto'] . '</td>
				<td>' . $a['horario'] . '</td>
				<td>' . $a['sede'] . '</td>
				<td>' . $a['cantidad'] . '</td>
				<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$data['total_personas'] = $total_personas;
		$data['total_cantidad'] = $total_cantidad;
		$this->output_json($data);
	}
	function get_Empresas_puesto_id()
	{
		$id = $this->input->post('id');
		$aux = Empresas_Puestos_Horarios_Model::get_grid_info("empresas_puestos_horarios.id=" . $id);
		$data = null;
		if ($aux) {
			$data = $aux[0];
		} else {
		}
		$this->output_json($data);
	}
	function get_Empresas_asistencias()
	{
		$id = $this->input->post('id');
		$aux = Empresas_Horarios_Cubiertos_Model::Load(array(
			'select' => "empresas_horarios_cubiertos.*, s.nombre as sede",
			'where' => 'empresas_horarios_cubiertos.empresa_id=' . $id,
			'joins' => array(
				'empresas_sedes as s' => 's.id=empresas_horarios_cubiertos.sede_id'
			),
			'result' => 'array'
		));
		$data['head'] = "<tr>
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
	function get_Empresas_encuestas()
	{
		$id = $this->input->post('id');
		$aux = Encuestas_Preguntas_Respuestas_Model::Load(array(
			'select' => "encuestas_preguntas_respuestas.*, s.nombre as encuesta",
			'where' => 'encuestas_preguntas_respuestas.empresa_id=' . $id,
			'joins' => array(
				'encuestas as s' => 's.id=encuestas_preguntas_respuestas.encuesta_id'
			),
			'result' => 'array'
		));
		$data['head'] = "<tr>
		<th>Encuesta</th>
		<th>Fecha</th>
		<th class='th-editar-colonia'>Ver</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-eye"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['encuesta'] . '</td>
				<td>' . $a['fecha'] . '</td>
				<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
	function eliminar_Empresas_puesto_id()
	{
		$id = $this->input->post('id');
		$result = Empresas_Puestos_Horarios_Model::Update(['status' => 0], "id=" . $id);
		$this->output_json($result);
	}
	function eliminar_Empresas_horario_id()
	{
		$id = $this->input->post('id');
		$result = Empresas_Horarios_Model::Update(['status' => 0], "id=" . $id);
		$this->output_json($result);
	}
	function eliminar_Empresas_sede_id()
	{
		$id = $this->input->post('id');
		$result = Empresas_Sedes_Model::Update(['status' => 0], "id=" . $id);
		$this->output_json($result);
	}
	function get_Sedes_horarios()
	{
		$id = $this->input->post('id');
		$data['select'] = Empresas_Puestos_Horarios_Model::get_select("sede_id=" . $id);
		$this->output_json($data);
	}
	function carga_masiva()
	{
		$json = file_get_contents("php://input");
		$data = json_decode($json, true);

		if (!isset($data['usuarios'])) {
			show_error("Datos inválidos", 400);
		}
		foreach ($data['usuarios'] as $row) {
			$razon_social = Razones_Sociales_Model::Load(array(
				'select' => "*",
				'where' => "name='" .  $row['razon_social_id'] . "'",
				'result' => '1row'
			));
			$tipo_nomina = Tipos_Nominas_Model::Load(array(
				'select' => "*",
				'where' => "nombre='" .  $row['tipo_nomina'] . "'",
				'result' => '1row'
			));
			$tipo_facturacion = Tipos_Facturacion_Model::Load(array(
				'select' => "*",
				'where' => "nombre='" .  $row['tipo_facturacion'] . "'",
				'result' => '1row'
			));
			$zona = Zonas_Model::Load(array(
				'select' => "*",
				'where' => "nombre='" .  $row['zona'] . "'",
				'result' => '1row'
			));
			$municipio = Municipios_Model::Load(array(
				'select' => "*",
				'where' => "nombre='" .  $row['municipio'] . "'",
				'result' => '1row'
			));
			$responsable = Users_Model::Load(array(
				'select' => "*",
				'where' => "user_name='" .  $row['responsable'] . "'",
				'result' => '1row'
			));

			$exist = Empresas_Model::Load([
				'select' => '*',
				'where' => "nombre='" . $row['nombre'] . "'",
				'result' => '1row'
			]);

			$registro = [
				'razon_social_id'  => $razon_social  ? $razon_social->id : null,
				'nombre'           => $row['nombre'] ?? '',
				'tipo_nomina'      => $tipo_nomina  ? $tipo_nomina->id : null,
				'tipo_facturacion' => $tipo_facturacion  ? $tipo_facturacion->id : null,
				'comentarios'      => $row['comentarios'] ?? '',
				'dias_credito'     => $row['dias_credito'] ?? 0,
				'responsable'      => $responsable  ? $responsable->id : null,
				'municipio'        => $municipio  ? $municipio->id : null,
				'zona'             => $zona  ? $zona->id : null
			];

			if (!$exist) {

				$dato = Empresas_Model::Insert($registro);
				if (isset($row['sedes'])) {
					$sedes = explode(",", $row['sedes'] ?? '');
					foreach ($sedes as $key => $value) {
						Empresas_Sedes_Model::Insert(['nombre' => $value, 'empresa_id' => $dato['insert_id']]);
					}
				}
			} else {
				Empresas_Model::Update($registro, 'id=' . $exist->id);
			}
		}

		$this->output_json(['status' => 'ok']);
	}
}
