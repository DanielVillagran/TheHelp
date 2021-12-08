<?php

defined('BASEPATH') or exit('No direct script access allowed');

class EncuestaNom035 extends ANT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/');
			return;
		}
	}
	public function Resultados_Esprezza() {
		$data['user_id'] = $this->tank_auth->get_user_id();

		$data['title'] = 'Resultados Evaluación';
		$data['view'] = 'grids/nom_035_resultados';
		$this->_load_views('Capacitacion/EncuestaNom035/Resultados_Esprezza', $data);

	}
	public function Resultado_Personal($id) {
		$data['user_id'] = $this->tank_auth->get_user_id();
		$data['id'] = $id;
		$data['title'] = 'Resultados Evaluación';
		$data['view'] = 'grids/nom_035_resultado_personal';

		$this->_load_views('Capacitacion/EncuestaNom035/Resultado_Personal', $data);

	}

}
