<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Logos_Model extends ANT_Model {

	protected static $table = 'logos';
	protected static $uk = array('id');

	function __construct() {
		parent::__construct();
	}

	static function get_grid_info($user_type) {
		$lista = '';
		$result = array();
		$options = array(
			'select' => '*',
			'result' => 'array',
			'where'=>'user_type='.$user_type
		);
		$result = Eventos_Model::Load($options);
		return $result;
	}

}
