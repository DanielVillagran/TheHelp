<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Eventos_Preguntas_Model extends ANT_Model {

	protected static $table = 'eventos_preguntas';
	protected static $uk = array('id');

	function __construct() {
		parent::__construct();
	}

	static function get_grid_info($where = NULL, $list = NULL, $agent = NULL) {
		$lista = '';
		$result = array();
		$options = array(
			'select' => 'eventos_preguntas.id,eventos_preguntas.pregunta,eventos_preguntas.evento_id,
			 SUM(CASE WHEN respuesta=1 THEN 1 ELSE 0 END) as si,
			 SUM(CASE WHEN respuesta=0 THEN 1 ELSE 0 END) as no',
			'joinsLeft'=>array('eventos_preguntas_respuestas'=>'eventos_preguntas_respuestas.evento_pregunta_id=eventos_preguntas.id'),
			'result' => 'array',
			'groupBy'=>'eventos_preguntas.id,eventos_preguntas.pregunta,eventos_preguntas.evento_id',
            'where'=>'evento_id='.$where
		);

		$result = Eventos_Preguntas_Model::Load($options);
		return $result;
	}


}
