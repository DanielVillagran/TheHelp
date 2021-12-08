<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Eventos_Calificaciones_Model extends ANT_Model {

	protected static $table = 'eventos_calificaciones';
	protected static $uk = array('id');

	function __construct() {
		parent::__construct();
	}

	static function get_grid_info($where = 0) {
		$lista = '';
		$result = array();
		$options = array(
			'select' => '*',
			'where'=>'evento_id='.$where,
			'result' => 'array'
			
		);

		$result = Eventos_Calificaciones_Model::Load($options);
		return $result;
	}

}
