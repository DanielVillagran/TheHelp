<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Colaboradores_Model extends ANT_Model
{

	protected static $table = 'colaboradores';
	protected static $uk = array('id');

	function __construct()
	{
		parent::__construct();
	}
	static function get_grid_info_prealtas($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '';
		$result = array();
		$status = '(1,2,6)';
		$options = array(
			'select' => 'colaboradores.*, colaboradores_status.nombre as estatus, 
			empresas.nombre as empresa, 
			razones_sociales.name as razon, 
			empresas_sedes.nombre as sede_nombre,
			puestos.nombre as puesto_nombre,
			empresas_horarios.nombre as horario_nombre,
			tipos_nominas.nombre as nomina_nombre',
			'joinsLeft' => [
				'colaboradores_status' => 'colaboradores.status=colaboradores_status.id',
				'empresas' => 'colaboradores.cliente=empresas.id',
				'empresas_horarios' => 'colaboradores.horario_id=empresas_horarios.id',
				'puestos' => 'colaboradores.puesto=puestos.id',
				'empresas_sedes' => 'colaboradores.sede=empresas_sedes.id',
				'tipos_nominas' => 'colaboradores.tipo_nomina=tipos_nominas.id',
				'razones_sociales' => 'colaboradores.razon_social=razones_sociales.id'
			],
			'result' => 'array',
			'where' =>  $where ? $where . " AND colaboradores.status in $status" : "colaboradores.status in $status"
		);

		$result = Colaboradores_Model::Load($options);
		return $result;
	}
	static function get_grid_info_prebajas($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '';
		$result = array();
		$status = '(4,5)';
		$options = array(
			'select' => 'colaboradores.*, colaboradores_status.nombre as estatus, 
			empresas.nombre as empresa, 
			razones_sociales.name as razon, 
			empresas_sedes.nombre as sede_nombre,
			puestos.nombre as puesto_nombre,
			empresas_horarios.nombre as horario_nombre,
			tipos_nominas.nombre as nomina_nombre',
			'joinsLeft' => [
				'colaboradores_status' => 'colaboradores.status=colaboradores_status.id',
				'empresas' => 'colaboradores.cliente=empresas.id',
				'empresas_horarios' => 'colaboradores.horario_id=empresas_horarios.id',
				'puestos' => 'colaboradores.puesto=puestos.id',
				'empresas_sedes' => 'colaboradores.sede=empresas_sedes.id',
				'tipos_nominas' => 'colaboradores.tipo_nomina=tipos_nominas.id',
				'razones_sociales' => 'colaboradores.razon_social=razones_sociales.id'
			],
			'result' => 'array',
			'where' =>  $where ? $where . " AND colaboradores.status in $status" : "colaboradores.status in $status"
		);

		$result = Colaboradores_Model::Load($options);
		return $result;
	}
	static function get_grid_info($where = NULL, $list = NULL, $agent = NULL)
	{
		$lista = '';
		$result = array();
		$status = '(3)';
		$options = array(
			'select' => 'colaboradores.*, colaboradores_status.nombre as estatus, empresas.nombre as empresa, razones_sociales.name as razon',
			'joinsLeft' => [
				'colaboradores_status' => 'colaboradores.status=colaboradores_status.id',
				'empresas' => 'colaboradores.cliente=empresas.id',
				'razones_sociales' => 'colaboradores.razon_social=razones_sociales.id'
			],
			'result' => 'array',
			'where' =>  $where ? $where . " AND colaboradores.status in $status" : "colaboradores.status in $status"
		);

		$result = Colaboradores_Model::Load($options);
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

		$result = Colaboradores_Model::Load($options);
		if ($result) {
			foreach ($result as $key) {
				$lista .= '<option value="' . $key['id'] . '">' . $key['codigo'] . ' - ' . $key['nombre'] . ' ' . $key['apellido_paterno'] . ' ' . $key['apellido_materno'] . '</option>';
			}
		}
		return $lista;
	}
}
