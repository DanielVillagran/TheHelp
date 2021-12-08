<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Departamentos_Servicios_Model extends ANT_Model {

	protected static $table = 'departamentos_servicios';
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
            'where'=>'departamento_id='.$where
		);

		$result = Departamentos_Servicios_Model::Load($options);
		return $result;
	}


}
