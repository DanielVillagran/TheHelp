<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Tickets_Model extends ANT_Model
{

	protected static $table = 'tickets';
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
			'select' => 'tickets.*,vehiculos.marca,vehiculos.modelo,vehiculos.serie,tipo_servicios.nombre as tipoServicio',
			'joinsLeft' => array(
				'vehiculos' => 'vehiculos.id = tickets.vehiculoId',
				'tipo_servicios' => 'tipo_servicios.id = tickets.tipoServicioId',
			),
			'result' => 'array'
		);

		$result = Tickets_Model::Load($options);
		return $result;
	}
	static function get_grid_info_by_vehiculo($id)
	{
		$lista = '';
		$result = array();
		$options = array(
			'select' => 'tickets.*, tipo_servicios.nombre as tipoServicio',
			'joinsLeft' => array(
				'tipo_servicios' => 'tipo_servicios.id = tickets.tipoServicioId',
			),
			'where' => 'tickets.vehiculoId=' . $id,
			'result' => 'array'
		);

		$result = Tickets_Model::Load($options);
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

		$result = Tickets_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
