<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Servicios_Model extends ANT_Model
{

	protected static $table = 'servicios';
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
			'select' => 'servicios.*,vehiculos.marca,vehiculos.modelo,vehiculos.serie,tipo_servicios.nombre as tipoServicio',
			'joinsLeft' => array(
				'vehiculos' => 'vehiculos.id = servicios.vehiculoId',
				'tipo_servicios' => 'tipo_servicios.id = servicios.tipoServicioId',
			),
			'result' => 'array'
		);

		$result = Servicios_Model::Load($options);
		return $result;
	}
	static function get_grid_info_by_vehiculo($id)
	{
		$lista = '';
		$result = array();
		$options = array(
			'select' => 'servicios.*, tipo_servicios.nombre as tipoServicio',
			'joinsLeft' => array(
				'tipo_servicios' => 'tipo_servicios.id = servicios.tipoServicioId',
			),
			'where' => 'servicios.vehiculoId=' . $id,
			'result' => 'array'
		);

		$result = Servicios_Model::Load($options);
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

		$result = Servicios_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
