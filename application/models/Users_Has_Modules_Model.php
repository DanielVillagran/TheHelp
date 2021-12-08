<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Users_Has_Modules_Model extends ANT_Model {

	protected static $table = 'users_has_module';
	protected static $uk = array('id');

	function __construct() {
		parent::__construct();
	}

	static function get_grid_info($where = NULL, $list = NULL, $agent = NULL) {
		$lista = '';
		$result = array();
		$options = array(
			'select' => '*',
            'result' => 'array'
		);

		$result = Users_Has_Modules_Model::Load($options);
		return $result;
	}
	static function get_permissions($id) {
		$result = array();
		$options = array(
			'select' => "modules.id,modules.nombre, users_has_module.id as has",
			'result' => 'array',
			'joinsLeft' => array('users_has_module'=>'users_has_module.module_id=modules.id AND users_has_module.user_id='.$id),
		);

		$result = Modules_Model::Load($options);
		
		return $result;
	}


}
