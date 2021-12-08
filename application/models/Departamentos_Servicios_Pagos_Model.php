<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Departamentos_Servicios_Pagos_Model extends ANT_Model
{

    protected static $table = 'departamentos_servicios_pagos';
    protected static $uk = array('id');

    public function __construct()
    {
        parent::__construct();
    }

    public static function get_grid_info($user_type)
    {
        $lista = '';
        $result = array();
        $options = array(
            'select' => 'departamentos_servicios_pagos.*,departamentos_servicios.nombre as servicio,departamentos.nombre as departamento',
            'joinsLeft' => array(
				'departamentos_servicios' => 'departamentos_servicios.id=departamentos_servicios_pagos.departamento_servicio_id',
				'departamentos' => 'departamentos_servicios.departamento_id=departamentos.id'
			),
            'result' => 'array',
            'where'=>'user_type='.$user_type
        );

        $result = Departamentos_Servicios_Pagos_Model::Load($options);
        return $result;
    }

}
