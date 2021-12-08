<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Departamentos_Servicios_Citas_Model extends ANT_Model
{

    protected static $table = 'departamentos_servicios_citas';
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
            'select' => 'departamentos_servicios_citas.*,departamentos_servicios.nombre as servicio,departamentos.nombre as departamento,departamentos_servicios_citas_agenda.fecha as agendado',
            'joinsLeft' => array(
				'departamentos_servicios' => 'departamentos_servicios.id=departamentos_servicios_citas.departamento_servicio_id',
				'departamentos' => 'departamentos_servicios.departamento_id=departamentos.id',
				'departamentos_servicios_citas_agenda'=>'departamentos_servicios_citas_agenda.departamentos_servicios_cita_id=departamentos_servicios_citas.id'
			),
            'result' => 'array',
            'where'=>'user_type='.$user_type
        );

        $result = Departamentos_Servicios_Citas_Model::Load($options);
        return $result;
    }

}
