<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Encuestas_Model extends ANT_Model
{

	protected static $table = 'encuestas';
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
			'select' => '*',
			'result' => 'array',
			'where' =>  $where ? $where . " AND status=1" : "status=1"

		);

		$result = Encuestas_Model::Load($options);
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
			'where' =>  $where ? $where . " AND status=1" : "status=1"
		);
		if ($where) {
			$options['where'] = $where;
		}

		$result = Encuestas_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
