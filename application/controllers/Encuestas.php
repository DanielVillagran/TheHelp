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
	function reporte()
	{
		$data['razones'] = Razones_Sociales_Model::get_select();
        $data['empresas'] = Empresas_Model::get_select();
        $data['fecha_inicio'] = "2025-06-08";//date("Y-m-d");
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
		$result = Encuestas_Model::Update(['status' => 0], "id=" . $id);
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
		$data['head'] = "<tr><th>Pregunta</th><th>Respuesta</th><th>Comentario</th></tr>";
		$data['table'] = '';

		$aux = Encuestas_Preguntas_Model::Load(array(
			'select' => "*",
			'where' => 'encuesta_id=' . $post['encuesta_id'],
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
						$respuesta = '<td>
						<div class="d-flex gap-3">
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="B" /> B</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="R" /> R</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="M" /> M</label>
							<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="N/A" /> N/A</label>
						</div>
					</td>';
						break;

					case 2:
						$respuesta = '<td><div class="d-flex gap-3">';
						for ($i = 1; $i <= 5; $i++) {
							$respuesta .= '<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="' . $i . '" /> ' . $i . '</label>';
						}
						$respuesta .= '</div></td>';
						break;

					case 3:
						$respuesta = '<td>
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
						$respuesta = '<td><div class="d-flex gap-3">';
						for ($i = 1; $i <= 5; $i++) {
							$respuesta .= '<label style="margin-right:10px"><input type="radio" name="cubiertos[' . $id . ']" value="' . $i . '" /> ' . $i . '</label>';
						}
						$respuesta .= '</div></td>';
						$comentarioInput = '<td><input type="text" class="form-control" name="comentarios[' . $id . ']" /></td>';
						break;

					case 5:
						$respuesta = '<td><input type="number" class="form-control" name="cubiertos[' . $id . ']" min="1" max="10" oninput="this.value = Math.min(10, Math.max(1, this.value))" /></td>';
						break;

					case 6:
						$respuesta = '<td colspan="2"><textarea class="form-control" name="cubiertos[' . $id . ']" rows="2"></textarea></td>';
						break;

					default:
						$respuesta = '<td><input class="form-control" name="cubiertos[' . $id . ']" /></td>';
						break;
				}

				$data['table'] .= '<tr><td>' . $pregunta . '</td>' . $respuesta . $comentarioInput . '</tr>';
			}
		}

		if ($data['table'] == "") {
			$data['table'] = '<tr><td colspan="3">No se han encontrado preguntas en esta encuesta</td></tr>';
		}

		$this->output_json($data);
	}
	public function save_respuestas_encuestas()
	{
		$post = $this->input->post();
		$fecha = $post['users']['fecha'];
		$empresa_id = $post['users']['empresa_id'];
		$encuesta_id = $post['users']['encuesta_id'];
		$cubiertos = $this->input->post('cubiertos');
		$comentarios = $this->input->post('comentarios');
		$id = 0;
		$hasValue = Encuestas_Preguntas_Respuestas_Model::Load(array(
			'select' => '*',
			'result' => '1row',
			"where" => "empresa_id=" . $empresa_id . " AND fecha='" . $fecha . "' AND encuesta_id='" . $encuesta_id . "'"

		));
		if ($hasValue) {
			$id = $hasValue->id;
			Encuestas_Preguntas_Respuestas_Detalle_Model::Delete('encuestas_preguntas_respuesta_id=' . $id);
		} else {
			$inserted = Encuestas_Preguntas_Respuestas_Model::Insert([
				'empresa_id' => $empresa_id,
				'encuesta_id' => $encuesta_id,
				'fecha' => $fecha
			]);
			$id = $inserted['insert_id'];
		}
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
		$this->output_json(['status' => 'ok']);
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
							$respuesta .= '<label style="margin-right:10px"><input type="radio" disabled ' . $checked . ' /> ' . $op . '</label>';
						}
						$respuesta .= '</div></td>';

						if ($tipo == 3) {
							$comentarioInput = '<td><input type="text" class="form-control" disabled value="' . htmlspecialchars($comentario) . '" /></td>';
						}
						break;

					case 2: // escala 1-5
					case 4:
						$respuesta = '<td><div class="d-flex gap-3">';
						for ($i = 1; $i <= 5; $i++) {
							$checked = ($valor == $i) ? 'checked' : '';
							$respuesta .= '<label style="margin-right:10px"><input type="radio" disabled ' . $checked . ' /> ' . $i . '</label>';
						}
						$respuesta .= '</div></td>';

						if ($tipo == 4) {
							$comentarioInput = '<td><input type="text" class="form-control" disabled value="' . htmlspecialchars($comentario) . '" /></td>';
						}
						break;

					case 5: // numérico
						$respuesta = '<td><input type="number" class="form-control" disabled value="' . htmlspecialchars($valor) . '" /></td>';
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
		$where = "";
		if ($user_role_id > 2) {
			$where = "empresas.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
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
		<th>Fecha</th>
		<th class='th-editar-colonia'>Ver</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-eye"></i></button>';
				$data['table'] .= '<tr>
				<td>' . $a['empresa'] . '</td>
				<td>' . $a['encuesta'] . '</td>
				<td>' . $a['fecha'] . '</td>
				<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
}
