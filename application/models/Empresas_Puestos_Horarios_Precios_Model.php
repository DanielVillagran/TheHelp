<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Empresas_Puestos_Horarios_Precios_Model extends ANT_Model
{

	protected static $table = 'empresas_puestos_horarios_precios';
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
			'where' => $where ? $where : ""
		);

		$result = Empresas_Puestos_Horarios_Precios_Model::Load($options);
		return $result;
	}
	static function get_select($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '<option hidden>Seleccionar horario</option>';
		$result = array();
		$options = array(
			'select' => "h.*",
			'result' => 'array',
			'joinsLeft' => array(
				"empresas_horarios as h" => 'h.id=empresas_puestos_horarios.horario_id'
			),
			'where' =>  $where ? $where . " AND empresas_puestos_horarios.status=1" : "empresas_puestos_horarios.status=1"
		);

		$result = Empresas_Puestos_Horarios_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
	static function get_select_puesto($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '<option hidden>Seleccionar horario</option>';
		$result = array();
		$options = array(
			'select' => "h.*",
			'result' => 'array',
			'joinsLeft' => array(
				"puestos as h" => 'h.id=empresas_puestos_horarios.puesto_id'
			),
			'where' =>  $where
		);

		$result = Empresas_Puestos_Horarios_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
