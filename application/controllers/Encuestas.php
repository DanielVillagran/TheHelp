<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';
use PHPMailer\PHPMailer\PHPMailer;

require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';

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
	private  $meses = [
		1 => "Enero",
		2 => "Febrero",
		3 => "Marzo",
		4 => "Abril",
		5 => "Mayo",
		6 => "Junio",
		7 => "Julio",
		8 => "Agosto",
		9 => "Septiembre",
		10 => "Octubre",
		11 => "Noviembre",
		12 => "Diciembre"
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
	function reporte()
	{
		$data['razones'] = Razones_Sociales_Model::get_select();
		$data['empresas'] = Empresas_Model::get_select();
		$data['fecha_inicio'] = "2025-06-08"; //date("Y-m-d");
		$data['fecha_fin'] = date("Y-m-d");
		$data['title'] = 'Encuestas';
		$data['view'] = 'reportes/Encuestas';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Encuestas/reporte', $data);
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
				$botones = '<button type="button" class="btn btn-default row-edit permisoEdicion" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete permisoEdicion" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
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
		$result = Encuestas_Model::Update(['status' => 0], "id=" . $id);
		$this->output_json($result);
	}
	function eliminar_pregunta()
	{
		$id = $this->input->post("id");
		$result = Encuestas_Preguntas_Model::Update(['status' => 0], "id=" . $id);
		$this->output_json($result);
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
		$aux = Encuestas_Preguntas_Model::get_grid_info("status= 1 AND encuesta_id=" . $id);
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
	function respuestas()
	{
		$data['title'] = 'Ver respuestas encuestas';
		$data['view'] = 'grids/RespuestasEncuestas';
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

		$this->_load_views('RespuestasEncuestas/list', $data);
	}
	function respuestasAdd()
	{
		$data['title'] = 'Respuestas encuestas';
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
		$data['head'] = "<tr><th style='width:70%'>Pregunta</th><th>Comentario</th></tr>";
		$data['table'] = '';
		$data['introduccion'] = '';
		$aux = Encuestas_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['encuesta_id'],
			'result' => '1row'
		));
		$data['introduccion'] = $aux->introduccion;

		$aux = Encuestas_Preguntas_Model::Load(array(
			'select' => "*",
			'where' => 'status=1 AND encuesta_id=' . $post['encuesta_id'],
			'sortBy' => 'orden',
			'sortDirection' => 'ASC',
			'result' => 'array'
		));

		if ($aux) {
			foreach ($aux as $a) {
				$id = $a['id'];
				$pregunta = $a['pregunta'];
				$tipo = $a['tipo'];
				$comentarioInput = '<td></td>';

				switch ($tipo) {
					case 1:
						$respuesta = '
						<div class="d-flex gap-3">
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="B" /> B</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="R" /> R</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="M" /> M</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="N/A" /> N/A</label>
						</div>
					</td>';
						break;

					case 2:
						$respuesta = '<div class="d-flex gap-3">';
						for ($i = 1; $i <= 5; $i++) {
							$respuesta .= '<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="' . $i . '" /> ' . $i . '</label>';
						}
						$respuesta .= '</div></td>';
						break;

					case 3:
						$respuesta = '
						<div class="d-flex gap-3">
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="B" /> B</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="R" /> R</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="M" /> M</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="N/A" /> N/A</label>
						</div>
					</td>';
						$comentarioInput = '<td><input type="text" class="form-control" name="comentarios[' . $id . ']" /></td>';
						break;

					case 4:
						$respuesta = '<div class="d-flex gap-3">';
						for ($i = 1; $i <= 5; $i++) {
							$respuesta .= '<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="' . $i . '" /> ' . $i . '</label>';
						}
						$respuesta .= '</div></td>';
						$comentarioInput = '<td><input type="text" class="form-control" name="comentarios[' . $id . ']" /></td>';
						break;

					case 5:
						$respuesta = '<div class="d-flex gap-3">';
						for ($i = 1; $i <= 10; $i++) {
							$respuesta .= '<label style="margin-right:10px">
												   <input type="radio" name="cubiertos[' . $id . ']" value="' . $i . '" /> ' . $i . '
											   </label>';
						}
						// Opción N/A con valor 0
						$respuesta .= '<label style="margin-right:10px">
											   <input type="radio" name="cubiertos[' . $id . ']" value="0" /> N/A
										   </label>';

						$respuesta .= '</div></td>';

						$comentarioInput = '<td><input type="text" class="form-control" name="comentarios[' . $id . ']" /></td>';
						break;


					case 6:
						$respuesta = '<textarea class="form-control" name="cubiertos[' . $id . ']" rows="2"></textarea></td>';
						break;

					default:
						$respuesta = '<input class="form-control" name="cubiertos[' . $id . ']" /></td>';
						break;
				}

				$data['table'] .= '<tr><td>' . $pregunta . '<br>' . $respuesta . $comentarioInput . '</tr>';
			}
		}

		if ($data['table'] == "") {
			$data['table'] = '<tr><td colspan="3">No se han encontrado preguntas en esta encuesta</td></tr>';
		}

		$this->output_json($data);
	}
	public function eliminar_respuestas()
	{
		$id = $this->input->post('id');
		Encuestas_Preguntas_Respuestas_Detalle_Model::Delete('encuestas_preguntas_respuesta_id=' . $id);
		Encuestas_Preguntas_Respuestas_Model::Delete('id=' . $id);
		$this->output_json(['status' => 'ok']);
	}
	public function save_respuestas_encuestas()
	{
		$post = $this->input->post();
		$fecha = $post['users']['fecha'] ?? null;
		$empresa_id = $post['users']['empresa_id'] ?? null;
		$encuesta_id = $post['users']['encuesta_id'] ?? null;
		$mes = $post['users']['mes'] ?? null;
		$anio = $post['users']['anio'] ?? null;
		$cubiertos = $this->input->post('cubiertos');
		$comentarios = $this->input->post('comentarios');
		$id = 0;
		$firma_url = null;

		// 🔹 Guardar la firma si existe
		if (!empty($_FILES['firma']['tmp_name'])) {
			$uploadDir = FCPATH . 'uploads/firmas/';
			if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

			$fileName = 'firma_' . time() . '_' . uniqid() . '.png';
			$filePath = $uploadDir . $fileName;

			move_uploaded_file($_FILES['firma']['tmp_name'], $filePath);
			$firma_url = base_url('uploads/firmas/' . $fileName);
		}

		// 🔹 Buscar si ya existe un registro
		$hasValue = Encuestas_Preguntas_Respuestas_Model::Load([
			'select' => '*',
			'result' => '1row',
			"where" => "empresa_id=" . $empresa_id .
				" AND fecha='" . $fecha .
				"' AND encuesta_id='" . $encuesta_id . "'" .
				" AND mes=" . $mes .
				" AND anio=" . $anio
		]);

		if ($hasValue) {
			$id = $hasValue->id;
			Encuestas_Preguntas_Respuestas_Detalle_Model::Delete('encuestas_preguntas_respuesta_id=' . $id);

			if ($firma_url) {
				Encuestas_Preguntas_Respuestas_Model::Update(
					['firma_url' => $firma_url, 'firmado_por' => $this->tank_auth->get_user_id()],
					['id' => $id]
				);
			}
		} else {
			$inserted = Encuestas_Preguntas_Respuestas_Model::Insert([
				'empresa_id' => $empresa_id,
				'encuesta_id' => $encuesta_id,
				'mes' => $mes,
				'anio' => $anio,
				'fecha' => $fecha,
				'firma_url' => $firma_url,
				'firmado_por' => $this->tank_auth->get_user_id()
			]);
			$id = $inserted['insert_id'];

			$query = [
				'select' => "encuestas_preguntas_respuestas.*, s.nombre as encuesta, e.nombre as empresa",
				'joins' => [
					'encuestas as s' => 's.id=encuestas_preguntas_respuestas.encuesta_id',
					'empresas as e' => 'e.id=encuestas_preguntas_respuestas.empresa_id'
				],
				'result' => '1row',
				'where' => 'encuestas_preguntas_respuestas.id=' . $id
			];
			$aux = Encuestas_Preguntas_Respuestas_Model::Load($query);

			$this->send_email([
				'encuesta' => $aux->encuesta,
				'fecha' => $fecha,
				'cliente' => $aux->empresa
			]);
		}

		// 🔹 Guardar respuestas y comentarios
		if (!empty($cubiertos) || !empty($comentarios)) {
			$preguntas_ids = array_unique(array_merge(
				array_keys($cubiertos ?? []),
				array_keys($comentarios ?? [])
			));

			foreach ($preguntas_ids as $pregunta_id) {
				$respuesta = isset($cubiertos[$pregunta_id]) ? trim($cubiertos[$pregunta_id]) : null;
				$comentario = isset($comentarios[$pregunta_id]) ? trim($comentarios[$pregunta_id]) : null;

				if ($respuesta !== null || $comentario !== null) {
					Encuestas_Preguntas_Respuestas_Detalle_Model::Insert([
						'encuestas_preguntas_respuesta_id' => $id,
						'encuestas_pregunta_id' => $pregunta_id,
						'valor' => $respuesta,
						'comentario' => $comentario,
					]);
				}
			}
		}

		$this->output_json([
			'status' => 'ok',
			'firma_url' => $firma_url
		]);
	}


	function respuestaView($id)
	{
		$hc = Encuestas_Preguntas_Respuestas_Model::Load(array(
			'select' => "encuestas_preguntas_respuestas.*, s.nombre as encuesta, e.nombre as empresa",
			'where' => 'encuestas_preguntas_respuestas.id=' . $id,
			'joins' => array(
				'encuestas as s' => 's.id=encuestas_preguntas_respuestas.encuesta_id',
				'empresas as e' => 'e.id=encuestas_preguntas_respuestas.empresa_id'
			),
			'result' => '1row'
		));
		$data['title'] = 'Ver Respuesta Encuesta';
		$data['view'] = 'grids/RespuestaView';
		$data['meses'] = $this->meses;
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['hc'] = $hc;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('RespuestasEncuestas/view', $data);
	}

	function get_info_respuestas($id)
	{
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();

		$options = array(
			'select' => "encuestas_preguntas_respuestas_detalle.*, ep.pregunta, ep.tipo, ep.orden",
			'joins' => array(
				'encuestas_preguntas as ep' => 'ep.id=encuestas_preguntas_respuestas_detalle.encuestas_pregunta_id',
			),
			"where" => "encuestas_preguntas_respuestas_detalle.encuestas_preguntas_respuesta_id=" . $id,
			"sortBy" => 'ep.orden',
			'result' => 'array'
		);

		$aux = Encuestas_Preguntas_Respuestas_Detalle_Model::Load($options);
		$data['head'] = "<tr>
		<th>Orden de pregunta</th>
		<th>Pregunta</th>
		<th>Respuesta</th>
		<th>Comentario</th>
		</tr>";
		$data['table'] = '';

		if ($aux) {
			foreach ($aux as $a) {
				$orden = $a['orden'];
				$pregunta = $a['pregunta'];
				$tipo = $a['tipo'];
				$valor = $a['valor'];
				$comentario = $a['comentario'];
				$comentarioInput = '<td></td>';

				switch ($tipo) {
					case 1: // B/R/M/N/A
					case 3:
						$respuesta = '<td><div class="d-flex gap-3">';
						foreach (['B', 'R', 'M', 'N/A'] as $op) {
							$checked = ($valor == $op) ? 'checked' : '';
							$respuesta .= '<label style="margin-right:10px"><input type="radio" ' . $checked . ' /> ' . $op . '</label>';
						}
						$respuesta .= '</div></td>';

						if ($tipo == 3) {
							$comentarioInput = '<td><textarea class="form-control" disabled rows="2">' . htmlspecialchars($comentario) . '</textarea></td>';
						}
						break;

					case 2: // escala 1-5
					case 4:
						$respuesta = '<td><div class="d-flex gap-3">';
						for ($i = 1; $i <= 5; $i++) {
							$checked = ($valor == $i) ? 'checked' : '';
							$respuesta .= '<label style="margin-right:10px"><input type="radio" ' . $checked . ' /> ' . $i . '</label>';
						}
						$respuesta .= '</div></td>';

						if ($tipo == 4) {
							$comentarioInput = '<td><textarea class="form-control" disabled rows="2">' . htmlspecialchars($comentario) . '</textarea></td>';
						}
						break;

					case 5: // numérico
						$respuesta = '<td><div class="d-flex gap-3">';
						for ($i = 0; $i <= 10; $i++) {
							$checked = ($valor == $i) ? 'checked' : ' disabled ';
							$respuesta .= '<label style="margin-right:10px"><input type="radio" ' . $checked . ' /> ' . ($i == 0 ? 'N/A' : $i) . '</label>';
						}
						$respuesta .= '</div></td>';
						$comentarioInput = '<td><textarea class="form-control" disabled rows="2">' . htmlspecialchars($comentario) . '</textarea></td>';
						break;

					case 6: // texto largo
						$respuesta = '<td colspan="2"><textarea class="form-control" disabled rows="2">' . htmlspecialchars($valor) . '</textarea></td>';
						break;

					default: // texto libre
						$respuesta = '<td><input class="form-control" disabled value="' . htmlspecialchars($valor) . '" /></td>';
						break;
				}

				$data['table'] .= '<tr><td>' . htmlspecialchars($orden) . '</td><td>' . htmlspecialchars($pregunta) . '</td>' . $respuesta . $comentarioInput . '</tr>';
			}
		}

		$this->output_json($data);
	}
	function get_Respuestas_globales()
	{
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$meses = $this->meses;
		$where = "";
		if ($user_role_id > 2) {
			$where = "e.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		$query = array(
			'select' => "encuestas_preguntas_respuestas.*, s.nombre as encuesta, e.nombre as empresa",
			'joins' => array(
				'encuestas as s' => 's.id=encuestas_preguntas_respuestas.encuesta_id',
				'empresas as e' => 'e.id=encuestas_preguntas_respuestas.empresa_id'
			),
			'result' => 'array'
		);
		if ($where) {
			$query['where'] = $where;
		}
		$aux = Encuestas_Preguntas_Respuestas_Model::Load($query);
		$data['head'] = "<tr>
		<th>Empresa</th>
		<th>Encuesta</th>
		<th>Mes</th>
		<th>Año</th>
		<th>Fecha</th>
		<th class='th-editar-colonia'>Ver</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-eye"></i></button>
				<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['empresa'] . '</td>
				<td>' . $a['encuesta'] . '</td>
				<td>' . (isset($a['mes']) && $a['mes'] > 0 && isset($meses[$a['mes']]) ? $meses[$a['mes']] : "") . '</td>
				<td>' . (isset($a['anio']) && $a['anio'] > 0 ? $a['anio'] : "") . '</td>
				<td>' . $a['fecha'] . '</td>
				<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
	public function send_email($datos)
	{

		date_default_timezone_set("America/Mexico_City");
		$message = '<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width" name="viewport" />
    <!--[if !mso]><!-->
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <!--<![endif]-->
    <title></title>
    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700&display=swap" rel="stylesheet">
    <!--<![endif]-->
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
        }

        table,
        td,
        tr {
            vertical-align: top;
            border-collapse: collapse;
        }

        * {
            line-height: inherit;
        }

        a[x-apple-data-detectors=true] {
            color: inherit !important;
            text-decoration: none !important;
        }
    </style>
    <style id="media-query" type="text/css">
        @media (max-width: 520px) {

            .block-grid,
            .col {
                min-width: 320px !important;
                max-width: 100% !important;
                display: block !important;
            }

            .block-grid {
                width: 100% !important;
            }

            .col {
                width: 100% !important;
            }

            .col>div {
                margin: 0 auto;
            }

            img.fullwidth,
            img.fullwidthOnMobile {
                max-width: 100% !important;
            }

            .no-stack .col {
                min-width: 0 !important;
                display: table-cell !important;
            }

            .no-stack.two-up .col {
                width: 50% !important;
            }

            .no-stack .col.num4 {
                width: 33% !important;
            }

            .no-stack .col.num8 {
                width: 66% !important;
            }

            .no-stack .col.num4 {
                width: 33% !important;
            }

            .no-stack .col.num3 {
                width: 25% !important;
            }

            .no-stack .col.num6 {
                width: 50% !important;
            }

            .no-stack .col.num9 {
                width: 75% !important;
            }

            .video-block {
                max-width: none !important;
            }

            .mobile_hide {
                min-height: 0px;
                max-height: 0px;
                max-width: 0px;
                display: none;
                overflow: hidden;
                font-size: 0px;
            }

            .desktop_hide {
                display: block !important;
                max-height: none !important;
            }
        }
    </style>
</head>

<body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f4f4f4;">
    <!--[if IE]><div class="ie-browser"><![endif]-->
    <table bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
        style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f4f4f4; width: 100%;"
        valign="top" width="100%">
        <tbody>
            <tr style="vertical-align: top;" valign="top">
                <td style="word-break: break-word; vertical-align: top;" valign="top">
                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f4f4f4"><![endif]-->
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
                                <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:transparent;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                                    <div style="width:100% !important;">
                                        <!--[if (!mso)&(!IE)]><!-->
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                            <!--<![endif]-->
                                            <div align="center" class="img-container center fixedwidth"
                                                style="padding-right: 00px;padding-left: 00px;">
                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 00px;padding-left: 00px;" align="center"><![endif]-->
                                                <div style="font-size:1px;line-height:30px"> </div><img align="center"
                                                    alt="Alternate text" border="0" class="center fixedwidth"
                                                    src="https://thehelp.vmcomp.com.mx/assets/files/fotos/LogoLM.png"
                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 150px; display: block;"
                                                    title="Alternate text" width="150" />
                                                <div style="font-size:1px;line-height:30px"> </div>
                                                <!--[if mso]></td></tr></table><![endif]-->
                                            </div>
                                            <!--[if (!mso)&(!IE)]><!-->
                                        </div>
                                        <!--<![endif]-->
                                    </div>
                                </div>
                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #0081ff;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:#0081ff;background-image:;background-position:top left;background-repeat:no-repeat">
                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#0081ff"><![endif]-->
                                <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#0081ff;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                                    <div style="width:100% !important;">
                                        <!--[if (!mso)&(!IE)]><!-->
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                            <!--<![endif]-->
                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 20px; padding-bottom: 0px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
                                            <div
                                                style="color:#ffffff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:2;padding-top:20px;padding-right:20px;padding-bottom:0px;padding-left:20px;">
                                                <div
                                                    style="line-height: 2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #ffffff; mso-line-height-alt: 24px;">
                                                    <p
                                                        style="font-size: 28px; line-height: 2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 56px; margin: 0; font-weight: 300;">
                                                        <span style="font-size: 28px;">Encuesta contestada</span></p>
                                                </div>
                                            </div>
                                            <!--[if mso]></td></tr></table><![endif]-->
                                            <!--[if (!mso)&(!IE)]><!-->
                                        </div>
                                        <!--<![endif]-->
                                    </div>
                                </div>
                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
                                <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:#ffffff;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                                    <div style="width:100% !important;">
                                        <!--[if (!mso)&(!IE)]><!-->
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                            <!--<![endif]-->
                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 50px; padding-bottom: 20px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
                                            <div
                                                style="color:#555555;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:50px;padding-right:20px;padding-bottom:20px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #555555; mso-line-height-alt: 14px;">
                                                    <p
                                                        style="line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 14px; mso-line-height-alt: 17px; margin: 0;">
                                                        <span style="font-size: 14px;">Hola <strong>The Help</strong>,
                                                            hemos recibido una nueva respuesta a encuestas: </span></p>
                                                </div>
                                            </div>
                                            <!--[if mso]></td></tr></table><![endif]-->
                                            <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                role="presentation"
                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                valign="top" width="100%">
                                                <tbody>
                                                    <tr style="vertical-align: top;" valign="top">
                                                        <td class="divider_inner"
                                                            style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 30px; padding-left: 10px;"
                                                            valign="top">
                                                            <table align="center" border="0" cellpadding="0"
                                                                cellspacing="0" class="divider_content"
                                                                role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;"
                                                                valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                            valign="top"><span></span></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 0px; padding-bottom: 5px; font-family: Tahoma, Verdana, sans-serif"><![endif]-->
                                            <div
                                                style="color:#0081ff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:5px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #0081ff; mso-line-height-alt: 14px;">
                                                    <p
                                                        style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;">
                                                        Encuesta: <span style="font-size: 18px;">' . utf8_decode($datos['encuesta'])
			. '.</span></p>
                                                </div>
                                            </div>
                                            <div
                                                style="color:#0081ff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:5px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #0081ff; mso-line-height-alt: 14px;">
                                                    <p
                                                        style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;">
                                                        Cliente: <span style="font-size: 18px;">' . utf8_decode($datos['cliente'])
			. '.</span></p>
                                                </div>
                                            </div>
                                            <div
                                                style="color:#0081ff;font-family:\'Roboto\', Tahoma, Verdana, Segoe, sans-serif;line-height:1.2;padding-top:0px;padding-right:20px;padding-bottom:5px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.2; font-size: 12px; font-family: \'Roboto\', Tahoma, Verdana, Segoe, sans-serif; color: #0081ff; mso-line-height-alt: 14px;">
                                                    <p
                                                        style="font-size: 18px; line-height: 1.2; word-break: break-word; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 22px; margin: 0;">
                                                        Fecha: <span style="font-size: 18px;">' . utf8_decode($datos['fecha'])
			. '.</span></p>
                                                </div>
                                            </div>



                                            <!--[if mso]></td></tr></table><![endif]-->
                                            <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                role="presentation"
                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                valign="top" width="100%">
                                                <tbody>
                                                    <tr style="vertical-align: top;" valign="top">
                                                        <td class="divider_inner"
                                                            style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 30px; padding-right: 10px; padding-bottom: 50px; padding-left: 10px;"
                                                            valign="top">
                                                            <table align="center" border="0" cellpadding="0"
                                                                cellspacing="0" class="divider_content"
                                                                role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;"
                                                                valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                            valign="top"><span></span></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!--[if (!mso)&(!IE)]><!-->
                                        </div>
                                        <!--<![endif]-->
                                    </div>
                                </div>
                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
                                <!--[if (mso)|(IE)]><td align="center" width="500" style="background-color:transparent;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 30px; padding-left: 30px; padding-top:40px; padding-bottom:40px;"><![endif]-->
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                                    <div style="width:100% !important;">
                                        <!--[if (!mso)&(!IE)]><!-->
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:40px; padding-bottom:40px; padding-right: 30px; padding-left: 30px;">
                                            <!--<![endif]-->

                                            <!--[if mso]></td></tr></table><![endif]-->
                                            <!--[if (!mso)&(!IE)]><!-->
                                        </div>
                                        <!--<![endif]-->
                                    </div>
                                </div>
                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                            </div>
                        </div>
                    </div>
                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                </td>
            </tr>
        </tbody>
    </table>
    <!--[if (IE)]></div><![endif]-->
</body>

</html>';

		$mail = new PHPMailer();
		$mail->SMTPDebug = 0;
		$mail->isSMTP();
		$mail->Host = 'mail.vmcomp.com.mx';
		$mail->SMTPAuth = true;

		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
		$mail->Username = 'sender@vmcomp.com.mx';
		$mail->Password = 'Odvm200796*';

		//Recipients
		$mail->setFrom('sender@vmcomp.com.mx', 'The Help');
		$mail->Subject = 'Nueva respuesta a encuesta';
		$mail->isHTML(true);
		$mail->Body = $message;
		$mail->AddAddress('cristobal.dezulueta@thehelp.com.mx');
		$mail->AddAddress('david.vl@thehelp.com.mx');
		if (!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			//unlink("uploads/p" . $name . ".pdf");
			//echo "Message has been sent";
		}
	}
}
