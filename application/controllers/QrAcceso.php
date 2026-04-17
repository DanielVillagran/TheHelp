<?php

defined('BASEPATH') or exit('No direct script access allowed');

class QrAcceso extends ANT_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	public function sede()
	{
		$token = trim((string) $this->input->get('token'));
		if ($token === '') {
			show_error('No se recibio el token de la sede.', 400);
			return;
		}

		$secret = getenv('QR_SEDE_TOKEN') ?: '';
		if ($secret === '') {
			show_error('No se encontro el secreto QR_SEDE_TOKEN.', 500);
			return;
		}

		$this->load->library('qr_tokenizer');
		$sede_id = $this->qr_tokenizer->decode($token, $secret);
		if (!$sede_id || !ctype_digit((string) $sede_id)) {
			show_error('El QR de la sede no es valido.', 400);
			return;
		}

		$sede = Empresas_Sedes_Model::Load([
			'select' => 'empresas_sedes.id, empresas_sedes.nombre, empresas_sedes.empresa_id, empresas.nombre as empresa',
			'where' => 'empresas_sedes.id=' . intval($sede_id) . ' AND empresas_sedes.status=1',
			'joins' => [
				'empresas' => 'empresas.id=empresas_sedes.empresa_id'
			],
			'result' => '1row'
		]);

		if (!$sede) {
			show_error('La sede no existe o no esta disponible.', 404);
			return;
		}

		$data = [
			'title' => 'Registro de asistencia QR',
			'sede' => $sede
		];

		$this->load->view('QrAcceso/sede', $data);
	}

	public function validar_posicion()
	{
		$post = $this->get_request_data();
		$sede_id = intval($post['sede_id'] ?? 0);
		$lat = $post['lat'] ?? null;
		$lng = $post['lng'] ?? null;
		$radio_metros = $this->get_radio_metros($post);
		if ($sede_id <= 0 || !is_numeric($lat) || !is_numeric($lng)) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'Debes enviar sede_id, lat y lng validos.'
			]);
			return;
		}

		$sede = Empresas_Sedes_Model::Load([
			'select' => 'id, nombre, lat, lng',
			'where' => 'id=' . $sede_id . ' AND status=1',
			'result' => '1row'
		]);

		if (!$sede) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'La sede no existe o no esta disponible.'
			]);
			return;
		}

		if (!is_numeric($sede->lat) || !is_numeric($sede->lng)) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'La sede no tiene coordenadas configuradas.'
			]);
			return;
		}

		$distancia_metros = $this->calcular_distancia_metros(
			floatval($lat),
			floatval($lng),
			floatval($sede->lat),
			floatval($sede->lng)
		);

		$en_rango = $distancia_metros <= $radio_metros;

		$this->output_json([
			'status' => true,
			'en_rango' => $en_rango,
			'mensaje' => $en_rango ? 'La posicion esta en rango.' : "La posicion no esta en rango. \n Se encuentra a " . round($distancia_metros, 2) . ' metros de la sede.',
			'distancia_metros' => round($distancia_metros, 2),
			'radio_metros' => $radio_metros
		]);
	}

	public function decode_qr_colaborador()
	{
		date_default_timezone_set('America/Mexico_City');
		$fecha_actual = date('Y-m-d');
		$token = trim((string) $this->input->post('token'));
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');
		$sede_id = intval($this->input->post('sede_id'));
		$empresa_id = intval($this->input->post('empresa_id'));
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

		$sede = Empresas_Sedes_Model::Load([
			'select' => 'id, empresa_id',
			'where' => 'id=' . $sede_id . ' AND status=1',
			'result' => '1row'
		]);
		if (!$sede) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'La sede no existe o no esta disponible.'
			]);
			return;
		}

		if ($empresa_id > 0 && intval($sede->empresa_id) !== $empresa_id) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'La sede no pertenece a la empresa indicada.'
			]);
			return;
		}

		$this->load->library('qr_tokenizer');
		$colaborador_id = $this->qr_tokenizer->decode($token, $secret);
		if (!$colaborador_id || !ctype_digit((string) $colaborador_id)) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'El QR no es valido.'
			]);
			return;
		}

		$colaborador = Colaboradores_Model::Load([
			'select' => 'id, codigo, nombre, apellido_paterno, apellido_materno, cliente, sede',
			'where' => 'id=' . intval($colaborador_id),
			'result' => '1row'
		]);

		if (!$colaborador) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'No se encontro el colaborador.'
			]);
			return;
		}

		if ($empresa_id > 0 && intval($colaborador->cliente) !== $empresa_id) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'El colaborador no pertenece a la empresa de esta sede.'
			]);
			return;
		}

		if (!empty($colaborador->sede) && intval($colaborador->sede) !== $sede_id) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'El colaborador no pertenece a esta sede.'
			]);
			return;
		}

		$asistencia_valida = Asistencias_Validas_Model::Load([
			'select' => 'id',
			'where' => 'colaborador_id=' . intval($colaborador->id) . ' AND fecha="' . $fecha_actual . '"',
			'result' => '1row'
		]);

		if ($asistencia_valida) {
			$this->output_json([
				'status' => false,
				'mensaje' => 'Ya existe un registro para este colaborador en la fecha ' . $fecha_actual . '.'
			]);
			return;
		}

		Asistencias_Validas_Model::Insert([
			'colaborador_id' => $colaborador->id,
			'user_id' => null,
			'lat' => $lat,
			'lng' => $lng,
			'sede_id' => $sede_id,
			'fecha' => $fecha_actual
		]);

		$this->output_json([
			'status' => true,
			'mensaje' => 'QR valido.',
			'colaborador_id' => intval($colaborador->id),
			'codigo' => $colaborador->codigo,
			'nombre' => trim($colaborador->nombre . ' ' . $colaborador->apellido_paterno . ' ' . $colaborador->apellido_materno)
		]);
	}

	private function get_radio_metros($post)
	{
		if (isset($post['radio_metros']) && is_numeric($post['radio_metros']) && floatval($post['radio_metros']) > 0) {
			return floatval($post['radio_metros']);
		}

		return 1000;
	}

	private function get_request_data()
	{
		$post = $this->input->post();
		if (!empty($post)) {
			return $post;
		}

		$raw_input = trim($this->input->raw_input_stream);
		if ($raw_input === '') {
			return [];
		}

		$json = json_decode($raw_input, true);
		if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
			return $json;
		}

		parse_str($raw_input, $parsed);
		return is_array($parsed) ? $parsed : [];
	}

	private function calcular_distancia_metros($lat1, $lng1, $lat2, $lng2)
	{
		$radio_tierra_metros = 6371000;
		$dLat = deg2rad($lat2 - $lat1);
		$dLng = deg2rad($lng2 - $lng1);
		$a = sin($dLat / 2) * sin($dLat / 2) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
			sin($dLng / 2) * sin($dLng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		return $radio_tierra_metros * $c;
	}
}
