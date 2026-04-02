<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Tipos_Servicios_Model extends ANT_Model
{

	protected static $table = 'tipo_servicios';
	protected static $uk = array('id');

	function __construct()
	{
		parent::__construct();
	}

	static function get_grid_info($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '';
		$result = array();
		$options = array(
			'select' => "tipo_servicios.*, TRIM(CONCAT(COALESCE(users_user.name,''), ' ', COALESCE(users_user.middle_name,''), ' ', COALESCE(users_user.last_name,''))) as usuario_asignado_nombre, users_user.user_name as usuario_asignado_user_name",
			'joinsLeft' => array(
				'users_user' => 'users_user.id = tipo_servicios.usuario_asignado'
			),
			'result' => 'array'
		);

		if ($where) {
			$options['where'] = $where;
		}

		$result = Tipos_Servicios_Model::Load($options);
		return $result;
	}
	static function get_select($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '<option value="all">-- Todos los vehiculos --</option>';
		$lista = "";
		$result = array();
		$options = array(
			'select' => '*',
			'result' => 'array'
		);

		$result = Tipos_Servicios_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
