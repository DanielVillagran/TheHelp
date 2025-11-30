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
		$data['estados_mexico'] = $this->get_estados();
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['nominas'] = Tipos_Nominas_Model::get_select();
		$data['clientes'] = Empresas_Model::get_select();
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
		$data['nominas'] = Tipos_Nominas_Model::get_select();
		$data['estados_mexico'] = $this->get_estados();
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['clientes'] = Empresas_Model::get_select();
		$this->_load_views('Colaboradores/add', $data);
	}
	function get_info()
	{
		$post = $this->input->post();
		$data = Colaboradores_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Colaboradores($tipo)
	{
		if ($tipo === 'activo') {
			$aux = Colaboradores_Model::get_grid_info();
		} else if ($tipo === 'prealta') {
			$aux = Colaboradores_Model::get_grid_info_prealtas();
		} else if ($tipo === 'prebaja') {
			$aux = Colaboradores_Model::get_grid_info_prebajas();
		}
		$data['head'] = "<tr>
			<th>Razón social</th>
			<th>Cliente</th>
			<th>RFC</th>
			<th>NSS</th>
			<th>Nombre</th>
			<th>Apellido Paterno</th>
			<th>Apellido Materno</th>
			<th>Estatus</th>
			"
			. ($tipo === 'activo' ? '<th>Datos bancarios</th>' : '') .
			"
			<th class='th-editar-colonia'>Editar</th>
			</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				if ($tipo === 'prebaja') {
					$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-eye"></i></button>
					<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-retweet"></i></button>';
				} else {
					$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
					<button type="button" class="btn btn-default row-delete permisoEdicion" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				}
				$data['table'] .= '<tr>
					<td>' . $a['razon'] . '</td>
					<td>' . $a['empresa'] . '</td>
					<td>' . $a['rfc'] . '</td>
					<td>' . $a['nss'] . '</td>
					<td>' . $a['nombre'] . '</td>
					<td>' . $a['apellido_paterno'] . '</td>
					<td>' . $a['apellido_materno'] . '</td>
					<td>' . $this->get_estatus_color($a['estatus']) . '</td>
						'
					. ($tipo === 'activo' ? (($a['banco'] && $a['numero_cuenta'] && $a['clave_interbancaria']) ? '<th>Si</th>' : '<th>No</th>') : '') .
					'
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
			$post['status'] = 3;
			$result = Colaboradores_Model::Insert($post);
		} else {

			$result = Colaboradores_Model::Update($post, 'id=' . $post['id']);
		}
		$this->output_json($result);
	}
	function eliminar()
	{
		$id = $this->input->post("id");
		$result = Colaboradores_Model::Update(['status' => 0], 'id=' . $id);
		$this->output_json($result);
	}
	function reactivar()
	{
		$id = $this->input->post("id");
		$result = Colaboradores_Model::Update(['status' => 1], 'id=' . $id);
		Colaboradores_Movimientos_Model::Insert([
			'status' => 1,
			'colaborador_id' => $id,
			'acuse_url' => ''
		]);
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
	public function leer_acuse()
	{
		$this->load->library('Pdfreader');
		$this->load->model('Colaboradores_Model');
		$this->load->model('Colaboradores_Movimientos_Model');

		if (empty($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
			$this->output_json(['error' => 'No se recibió un archivo válido.']);
			return;
		}

		$statusParam = trim((string)$this->input->post('status'));
		$fromStatus = null;
		$toStatus = null;

		if (strcasecmp($statusParam, 'prealta') === 0 || $statusParam === '2') {
			$fromStatus = "(1)";
			$toStatus = 2;
		} elseif (strcasecmp($statusParam, 'alta') === 0 || $statusParam === '3') {
			$fromStatus = "(2,1)";
			$toStatus = 3;
		} elseif (strcasecmp($statusParam, 'alta') === 0 || $statusParam === '4') {
			$fromStatus = "(3)";
			$toStatus = 4;
		} elseif (strcasecmp($statusParam, 'alta') === 0 || $statusParam === '5') {
			$fromStatus = "(3,4)";
			$toStatus = 5;
		} else {
			$fromStatus = 1;
			$toStatus = 2;
		}

		$uploadDir = FCPATH . 'uploads/';
		if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

		$fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $_FILES['file']['name']);
		$filePath = $uploadDir . $fileName;

		if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
			$this->output_json(['error' => 'No se pudo guardar el archivo.']);
			return;
		}

		$resultado = $this->pdfreader->extractMovimientos($filePath);

		if (!empty($resultado['operados']) && is_array($resultado['operados'])) {
			foreach ($resultado['operados'] as $linea) {
				$valores = explode(',', $linea);
				$nss = isset($valores[1]) ? trim($valores[1]) : null;
				if (!$nss) continue;

				$colaborador = Colaboradores_Model::Load([
					'select' => 'id',
					'where' => "status in $fromStatus AND nss = '$nss'",
					'result' => '1row'
				]);

				if ($colaborador) {
					Colaboradores_Model::Update(
						['status' => $toStatus],
						"status in $fromStatus AND nss = '$nss'"
					);
					Colaboradores_Movimientos_Model::Insert([
						'status' => $toStatus,
						'colaborador_id' => $colaborador->id,
						'acuse_url' => $fileName
					]);
				}
			}
		}

		$this->output_json([
			'status' => 'ok',
			'archivo' => $fileName,
			'ruta' => base_url('uploads/' . $fileName),
			'resultado' => $resultado
		]);
	}
	function get_info_historico()
	{
		$post = $this->input->post();
		$aux = Colaboradores_Movimientos_Model::get_historico_by_colaborador(
			$post['id']
		);
		$data['head'] = "<tr>
		<th>Fecha</th>
		<th>Tipo movimiento</th>
		<th>Acuse</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '';
				if (!empty($a['acuse_url'])) {
					$fileUrl = base_url('uploads/' . $a['acuse_url']);
					$botones = '
						<a href="' . $fileUrl . '" target="_blank" class="btn btn-default row-edit" rel="' . $a['id'] . '">
							<i class="fa fa-download"></i>
						</a>
					';
				}
				$data['table'] .= '<tr>
				<td>' . $a['created_date'] . '</td>
				<td>' . $a['nombre'] . '</td>
				<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
	public function Formato($tipo)
	{
		// Obtener datos según el tipo
		if ($tipo === 'activo') {
			$aux = Colaboradores_Model::get_grid_info();
		} elseif ($tipo === 'prealta') {
			$aux = Colaboradores_Model::get_grid_info_prealtas();
		} elseif ($tipo === 'prebaja') {
			$aux = Colaboradores_Model::get_grid_info_prebajas();
		} else {
			$aux = [];
		}

		// Nombre del archivo
		$filename = 'colaboradores_' . $tipo . '_' . date('Ymd_His') . '.csv';

		// Encabezados para descarga
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		$out = fopen('php://output', 'w');

		// BOM para que Excel respete UTF-8
		fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// Encabezados en el orden solicitado
		$headers = [
			'Número Empleado',
			'Razón Social',
			'Cliente',
			'Sede',
			'Apellido Paterno',
			'Apellido Materno',
			'Nombre',
			'Puesto',
			'Horario',
			'RFC',
			'CURP',
			'NSS',
			'Tipo de Nómina',
			'SD',
			'Sueldo',
			'Fecha de Alta',
			'Fecha de Nacimiento',
			'Lugar de Nacimiento',
			'Estado Civil',
			'Calle',
			'Numero',
			'Colonia',
			'Municipio',
			'Estado',
			'Codigo postal',
			'Banco',
			'Numero de cuenta',
			'Clave interbancaria',
			'Correo Electronico'
		];
		fputcsv($out, $headers);

		// Filas
		if (!empty($aux)) {
			foreach ($aux as $a) {
				// Asegura arreglo indexado por nombre
				$a = (array)$a;

				$row = [
					isset($a['codigo']) ? $a['codigo'] : '',
					isset($a['razon']) ? $a['razon'] : '',
					isset($a['empresa']) ? $a['empresa'] : '',
					isset($a['sede_nombre']) ? $a['sede_nombre'] : '',
					isset($a['apellido_paterno']) ? $a['apellido_paterno'] : '',
					isset($a['apellido_materno']) ? $a['apellido_materno'] : '',
					isset($a['nombre']) ? $a['nombre'] : '',
					isset($a['puesto_nombre']) ? $a['puesto_nombre'] : '',
					// Si tu modelo ya trae nombre de horario como 'horario', úsalo; si no, cae al id
					isset($a['horario_nombre']) ? $a['horario_nombre'] : '',
					isset($a['rfc']) ? $a['rfc'] : '',
					isset($a['curp']) ? $a['curp'] : '',
					isset($a['nss']) ? $a['nss'] : '',
					isset($a['nomina_nombre']) ? $a['nomina_nombre'] : '',
					isset($a['sd']) ? $a['sd'] : '',
					isset($a['sueldo']) ? $a['sueldo'] : '',
					isset($a['fecha_alta']) ? $a['fecha_alta'] : '',
					isset($a['fecha_nacimiento']) ? $a['fecha_nacimiento'] : '',
					isset($a['lugar_nacimiento']) ? $a['lugar_nacimiento'] : '',
					isset($a['estado_civil']) ? $a['estado_civil'] : '',
					isset($a['calle']) ? $a['calle'] : '',
					isset($a['numero']) ? $a['numero'] : '',
					isset($a['colonia']) ? $a['colonia'] : '',
					isset($a['municipio']) ? $a['municipio'] : '',
					isset($a['estado']) ? $a['estado'] : '',
					isset($a['codigo_postal']) ? $a['codigo_postal'] : '',
					isset($a['banco']) ? $a['banco'] : '',
					isset($a['numero_cuenta']) ? $a['numero_cuenta'] : '',
					isset($a['clave_interbancaria']) ? $a['clave_interbancaria'] : '',
					isset($a['email']) ? $a['email'] : '',
				];

				fputcsv($out, $row);
			}
		}

		fclose($out);
		exit;
	}
}
