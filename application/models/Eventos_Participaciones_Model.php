<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Eventos_Participaciones_Model extends ANT_Model {

	protected static $table = 'eventos_participaciones';
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

		$result = Eventos_Participaciones_Model::Load($options);
		return $result;
	}

}
