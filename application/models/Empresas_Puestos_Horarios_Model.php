<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Empresas_Puestos_Horarios_Model extends ANT_Model
{

	protected static $table = 'empresas_puestos_horarios';
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
			'select' => 'empresas_puestos_horarios.*,s.nombre as sede, h.nombre as horario, p.nombre as puesto',
			'joinsLeft' => array(
				'empresas_sedes s' => 's.id=empresas_puestos_horarios.sede_id',
				'puestos p' => 'p.id=empresas_puestos_horarios.puesto_id',
				'empresas_horarios h' => 'h.id=empresas_puestos_horarios.horario_id',
			),
			'result' => 'array',
			'where' => $where
		);

		$result = Empresas_Puestos_Horarios_Model::Load($options);
		return $result;
	}
	static function get_select($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '<option hidden>Seleccionar horario</option>';
		$result = array();
		$options = array(
			'select' => "*",
			'result' => 'array',
			'where' => $where
		);

		$result = Empresas_Horarios_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
