<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Eventos_Preguntas_Respuestas_Model extends ANT_Model {

	protected static $table = 'eventos_preguntas_respuestas';
	protected static $uk = array('id');

	function __construct() {
		parent::__construct();
	}

	static function get_grid_info($where = NULL, $list = NULL, $agent = NULL) {
		$lista = '';
		$result = array();
		$options = array(
			'select' => '*',
			'result' => 'array',
		);

		$result = Eventos_Model::Load($options);
		return $result;
	}

}
