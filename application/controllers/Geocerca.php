<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Geocerca extends ANT_Controller
{
	const RADIO_METROS = 1000;

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

	function validar_posicion()
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
			'mensaje' => $en_rango ? 'La posicion esta en rango.' : 'La posicion no esta en rango.',
			'distancia_metros' => round($distancia_metros, 2),
			'radio_metros' => $radio_metros
		]);
	}

	private function get_radio_metros($post)
	{
		if (isset($post['radio_metros']) && is_numeric($post['radio_metros']) && floatval($post['radio_metros']) > 0) {
			return floatval($post['radio_metros']);
		}

		return self::RADIO_METROS;
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
