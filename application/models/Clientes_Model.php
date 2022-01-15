<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Clientes_Model extends ANT_Model
{

	protected static $table = 'clientes';
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
			'select' => 'clientes.*, empresas.nombre as empresa',
			'joinsLeft' => array(
				'empresas' => 'empresas.id = clientes.empresaId',
			),
			'result' => 'array'
		);

		$result = Clientes_Model::Load($options);
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

		$result = Clientes_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
