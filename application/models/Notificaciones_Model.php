<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Notificaciones_Model extends ANT_Model {

	protected static $table = 'notificaciones';
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
			'where'=>'token is null'
		);

		$result = Notificaciones_Model::Load($options);
		return $result;
	}



}
