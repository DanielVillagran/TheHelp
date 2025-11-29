<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class PreAltas_Model extends ANT_Model
{

	protected static $table = 'colaboradores_pre';
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
			//'where' =>  $where ? $where . " AND estatus=1" : "estatus=1"
		);

		$result = PreAltas_Model::Load($options);
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
			'where' =>  $where ? $where . " AND estatus=1" : "estatus=1"
		);

		$result = PreAltas_Model::Load($options);
		if ($result) {
			foreach ($result as $key) {
				$lista .= '<option value="' . $key['id'] . '">' . $key['codigo'] . ' - ' . $key['nombre'] . ' ' . $key['apellido_paterno'] . ' ' . $key['apellido_materno'] . '</option>';
			}
		}
		return $lista;
	}
}
