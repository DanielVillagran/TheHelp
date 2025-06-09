<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Empresas_Has_Users_Model extends ANT_Model {

	protected static $table = 'empresas_has_users';
	protected static $uk = array('id');

	function __construct() {
		parent::__construct();
	}

	static function get_grid_info($where = NULL, $list = NULL, $agent = NULL) {
		$lista = '';
		$result = array();
		$options = array(
			'select' => "empresas_has_users.id,empresas.nombre as empresa, CONCAT(users_user.name,' ',users_user.middle_name) as usuario",
            'joinsLeft' => array(
                'empresas' => 'empresas.id=empresas_has_users.empresa_id',
				'users_user' => 'users_user.id=empresas_has_users.user_id',
            ), 
            'result' => 'array',
			
		);

		$result = Empresas_Has_Users_Model::Load($options);
		return $result;
	}
	static function get_select($where = NULL, $list = NULL, $agent = NULL) {
		$lista = '<option value="all">-- Todos los empresas --</option>';
		$lista="";
		$result = array();
		$options = array(
			'select' => '*',
            'result' => 'array'
		);

		$result = Empresas_Model::Load($options);
		foreach ($result as $key ) {
			$lista .= '<option value="'.$key['id'].'">'.$key['nombre'].'</option>';
		}
		return $lista;
	}


}
