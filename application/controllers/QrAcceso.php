<?php

defined('BASEPATH') or exit('No direct script access allowed');

class QrAcceso extends ANT_Controller
{
	private $horas_minimas_relectura_qr = 2;

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
			'user_id' => null,
			'lat' => $lat,
			'lng' => $lng,
			'sede_id' => $sede_id,
			'fecha' => $fecha_actual
		]);

		$detalle_resultado = $this->registrar_detalle_qr_asistencia(
			$colaborador,
			$fecha_actual,
			$sede_id,
			$empresa_id,
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

		$this->output_json([
			'status' => true,
			'mensaje' => 'QR valido.',
			'colaborador_id' => intval($colaborador->id),
			'codigo' => $colaborador->codigo,
			'nombre' => trim($colaborador->nombre . ' ' . $colaborador->apellido_paterno . ' ' . $colaborador->apellido_materno)
		]);
	}

	private function registrar_detalle_qr_asistencia($colaborador, $fecha_actual, $sede_id, $empresa_id, $doble_registro = false)
	{
		$empresa_colaborador = intval($colaborador->cliente);
		$puesto_id = intval($colaborador->puesto);
		$horario_id = intval($colaborador->horario_id);
		$sede_asociada = intval($colaborador->sede);
		$sede_id = intval($sede_id);
		$empresa_id = intval($empresa_id);

		if ($empresa_colaborador <= 0 || $puesto_id <= 0 || $horario_id <= 0) {
			return [
				'status' => false,
				'mensaje' => 'El colaborador no tiene empresa, puesto u horario configurado.'
			];
		}

		if ($empresa_id > 0 && $empresa_colaborador !== $empresa_id) {
			return [
				'status' => false,
				'mensaje' => 'El colaborador no pertenece a la empresa de esta sede.'
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
			'where' => 'empresa_id=' . $empresa_colaborador .
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
			'select' => 'id',
			'where' => 'empresa_id=' . $empresa_colaborador . ' AND sede_id=' . $sede_id . ' AND fecha="' . $fecha_actual . '"',
			'result' => '1row'
		]);
		if (!$horario_cubierto) {
			$insertado = Empresas_Horarios_Cubiertos_Model::Insert([
				'empresa_id' => $empresa_colaborador,
				'sede_id' => $sede_id,
				'fecha' => $fecha_actual
			]);
			$horario_cubierto_id = isset($insertado['insert_id']) ? intval($insertado['insert_id']) : 0;
		} else {
			$horario_cubierto_id = intval($horario_cubierto->id);
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

			return $this->registrar_extra_qr_asistencia($colaborador, $fecha_actual, $sede_id, $empresa_colaborador);
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
