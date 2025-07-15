<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Empresas_Sedes_Model extends ANT_Model
{

	protected static $table = 'empresas_sedes';
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
			'select' => "*",
			'result' => 'array',
			'where' =>  $where ? $where . " AND status=1" : "status=1"
		);

		$result = Empresas_Sedes_Model::Load($options);
		return $result;
	}
	static function get_select($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '<option hidden>Seleccionar sede</option>';
		$result = array();
		$options = array(
			'select' => "*",
			'result' => 'array',
			'where' =>  $where ? $where . " AND status=1" : "status=1"
		);

		$result = Empresas_Sedes_Model::Load($options);
		if ($result) {
			foreach ($result as $key) {
				$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
			}
		}
		return $lista;
	}
}
