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
		$options = array(
			'select' => 'tickets.*, e.nombre as empresa_nombre, s.nombre as sede_nombre, ts.nombre as tipoServicio',
			'joinsLeft' => array(
				'empresas as e' => 'e.id = tickets.empresaId',
				'empresas_sedes as s' => 's.id = tickets.sedeId',
				'tipo_servicios as ts' => 'ts.id = tickets.tipoServicioId',
			),

			'result' => 'array'
		);
		if ($where != NULL && $where != '') {
			$options['where'] = $where;
		}
		return Tickets_Model::Load($options);
	}
	static function get_Tickets_pendientes($where = NULL, $list = NULL, $agent = NULL)
	{
		$options = array(
			'select' => 'COUNT(*) as pendientes',
			'joinsLeft' => array(
				'empresas as e' => 'e.id = tickets.empresaId',
				'empresas_sedes as s' => 's.id = tickets.sedeId',
				'tipo_servicios as ts' => 'ts.id = tickets.tipoServicioId',
			),

			'result' => '1row'
		);
		if ($where != NULL && $where != '') {
			$options['where'] = $where;
		}
		return Tickets_Model::Load($options);
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
