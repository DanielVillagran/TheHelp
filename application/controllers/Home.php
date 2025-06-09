<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once BASEPATH . '../application/models/common_library.php';

class Home extends ANT_Controller
{

    /**
     * Controlador que manejara los dashboards
     */
    public $config_leftnav;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        //$this->load->library('form_validation');
        //$this->load->helper('url');
        //$data ['user_id'] = $this->tank_auth->get_user_id ();
        //$data ['username'] = $this->tank_auth->get_username ();
        //$this->ci =& get_instance();
        //$this->ci->load->config('tank_auth', TRUE);
        //$this->load->helper('customer');
        //$this->load->helper('vehicles');
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/');
            return;
        }
    }

    public function index()
    {
        $data['user_id'] = $this->tank_auth->get_user_id();
        //$ids = $this->tank_auth->get_user_role();
        $data['view'] = 'forms/Home';
        $this->session->unset_userdata('pagina_previa');
        $this->_load_views('Home/dashboard', $data);
    }
    public function get_denuncias()
    {
        $aux = Denuncias_Model::get_grid_info();
        $data['head'] = "<tr><th>Nombre</th>

		<th class='th-editar-colonia'>Editar</th>
		</tr>";
        $data['table'] = '';
        $data['table2'] = '';
        if ($aux) {
            foreach ($aux as $a) {

                $botones = '<button type="button" class="btn btn-denuncia" onclick="atender_denuncia(' . $a['id'] . ')">Atender</button>';
				$span = '<span class="badge badge-denuncia">Atendida</span>';
                $ubicacion='<button type="button" class="btn btn-ver-ubicacion" data-toggle="modal" data-target="#exampleModalCenter" onclick="initMap(\''.$a['lat'].'\',\''.$a['lon'].'\')"><img src="../assets/images/icon-map.svg" alt="">Ver ubicación</button>';
                if ($a['status'] == 0) {
                    $data['table'] .= '<tr>
				<th class="td-foto"><img onclick="abrir_imagen(\'https://zumpango.vmcomp.com.mx/assets/files/denuncias/' . $a['imagen'] . '\')" src="https://zumpango.vmcomp.com.mx/assets/files/denuncias/' . $a['imagen'] . '" alt=""></th>
				<td class="td-descripcion">' . $a['descripcion'] . '</td>
				<td class="td-tipo">' . $a['tipo_denuncia'] . '</td>
				<td class="td-fecha"> ' . $a['created'] . '</td>
				<td class="td-ubicacion"> ' . $ubicacion . '</td>
				<td class="td-btn">' . $botones . '</td>
			</tr>';
                } else {
                    $data['table2'] .= '<tr>
				<th class="td-foto"><img onclick="abrir_imagen(\'https://zumpango.vmcomp.com.mx/assets/files/denuncias/' . $a['imagen'] . '\')" src="https://zumpango.vmcomp.com.mx/assets/files/denuncias/' . $a['imagen'] . '" alt=""></th>
				<td class="td-descripcion">' . $a['descripcion'] . '</td>
				<td class="td-tipo">' . $a['tipo_denuncia'] . '</td>
				<td class="td-fecha"> ' . $a['created'] . '</td>
				<td class="td-ubicacion"> ' . $ubicacion . '</td>
				<td class="td-btn">' . $span . '</td>
			</tr>';
                }
            }
        } else {
            
        }
        $this->output_json($data);
    }
    public function atender_denuncia()
    {
        $id = $this->input->post("id");
        $data = Denuncias_Model::Update(array('status' => 1), 'id=' . $id);
        $this->output_json($data);

    }

}
