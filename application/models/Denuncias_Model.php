<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Denuncias_Model extends ANT_Model {

	protected static $table = 'denuncias';
	protected static $uk = array('id');

	function __construct() {
		parent::__construct();
	}

	static function get_grid_info($where = NULL, $list = NULL, $agent = NULL) {
		$lista = '';
		$result = array();
		$options = array(
			'select' => 'denuncias.*,d.nombre as tipo_denuncia',
			'result' => 'array',
			'joinsLeft' => array('tipos_denuncias as d'=>'d.id=denuncias.tipo_denuncia_id')
		);

		$result = Denuncias_Model::Load($options);
		return $result;
	}


}
