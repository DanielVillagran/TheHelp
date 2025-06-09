<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Empresas_Horarios_Cubiertos_Extras_Model extends ANT_Model
{

	protected static $table = 'empresas_horarios_cubiertos_extras';
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
			'select' => "empresas_horarios_cubiertos_extras.*,
			e.nombre as empresa,
			p.nombre as puesto,
			es.nombre as sede,
			eh.nombre as horario,
			c.codigo,c.nombre,c.apellido_paterno,c.apellido_materno,
			CONCAT(c.codigo,' - ',c.nombre,' ',c.apellido_paterno,' ',c.apellido_materno) as colaborador",
			'joinsLeft' => array(
				'empresas as e' => 'e.id=empresas_horarios_cubiertos_extras.empresa_id',
				'empresas_sedes as es' => 'es.id=empresas_horarios_cubiertos_extras.sede_id',
				'empresas_horarios as eh' => 'eh.id=empresas_horarios_cubiertos_extras.horario_id',
				'puestos as p' => 'p.id=empresas_horarios_cubiertos_extras.puesto_id',
				'colaboradores as c' => 'c.id=empresas_horarios_cubiertos_extras.colaborador_id',
			),
			'result' => 'array',

		);
		if ($where) {
			$options['where'] = $where;
		}

		$result = Empresas_Horarios_Cubiertos_Extras_Model::Load($options);
		return $result;
	}
	static function get_select($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '<option value="all">-- Todos los empresas --</option>';
		$lista = "";
		$result = array();
		$options = array(
			'select' => '*',
			'result' => 'array'
		);

		$result = Empresas_Model::Load($options);
		foreach ($result as $key) {
			$lista .= '<option value="' . $key['id'] . '">' . $key['nombre'] . '</option>';
		}
		return $lista;
	}
}
