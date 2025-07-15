<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Empresas_Model extends ANT_Model
{

	protected static $table = 'empresas';
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
			'select' => "empresas.*,razones_sociales.name as razon_social, CONCAT(u.name,' ',u.middle_name) as responsable_name",
			'joinsLeft' => array(
				'razones_sociales' => 'razones_sociales.id=empresas.razon_social_id',
				'users_user as u' => 'u.id=empresas.responsable',
			),
			'result' => 'array',
			'where' => $where ? $where . " AND empresas.status=1" : "empresas.status=1"
		);
		

		$result = Empresas_Model::Load($options);
		return $result;
	}
	static function get_select($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '<option value="all">-- Todos los empresas --</option>';
		$lista = "";
		$result = array();
		$options = array(
			'select' => '*',
			'result' => 'array',
			'where' => $where ? $where . " AND empresas.status=1" : "empresas.status=1"
		);

		$result = Empresas_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
