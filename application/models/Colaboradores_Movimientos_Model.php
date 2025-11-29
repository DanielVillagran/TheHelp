<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Colaboradores_Movimientos_Model extends ANT_Model
{

	protected static $table = 'colaboradores_movimientos';
	protected static $uk = array('id');

	function __construct()
	{
		parent::__construct();
	}
	static function get_historico_by_colaborador($id)
	{
		$aux = Colaboradores_Movimientos_Model::Load([
			'select' => 'colaboradores_movimientos.*, colaboradores_status.nombre',
			'joins' => ['colaboradores_status' => 'colaboradores_status.id=colaboradores_movimientos.status'],
			'result' => 'array',
			'clauses' => ['colaboradores_movimientos.colaborador_id' => $id]
		]);
		return $aux;
	}
}
