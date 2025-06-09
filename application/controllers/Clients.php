<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class Clients extends ANT_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        if (!$this->tank_auth->is_logged_in()) {
            redirect('/');
            return;
        }
    }
    public function index()
    {
        $data['title'] = 'Usuarios';
        $data['view'] = 'grids/clients';
        $data['styles'] = 'jquery.shuttle';
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('clients/list', $data);
    }
    public function add()
    {
        $data['title'] = 'Nuevo Usuario';
        $data['view'] = 'forms/clients';
        $data['styles'] = 'jquery.shuttle';
        $data['id'] = 0;
        $data['permissions'] = array();
       
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('clients/add', $data);
    }
    public function edit($id)
    {
        $data['title'] = 'Editar Usuario';
        $data['view'] = 'forms/clients';
        $data['styles'] = 'jquery.shuttle';
        $data['id'] = $id;
        $data['permissions'] = Users_Has_Modules_Model::get_permissions($id);
        $data['js_scripts'] = 'lib/jquery.shuttle';
        $data['user_id'] = $this->tank_auth->get_user_id();
        $this->_load_views('clients/add', $data);
    }
    public function get_info_users()
    {
        $post = $this->input->post();
        $data = Users_Model::Load(array('select' => "*",
            'where' => 'id=' . $post['id'],
            'result' => '1row'));
        $this->output_json($data);
    }
    public function get_clients()
    {
        $aux = Users_Model::get_grid_info();
        $data['head'] = "<tr><th>Nombre</th>
		<th>Apellidos</th>

		<th>Nombre de usuario</th>
		<th class='th-editar-usuarios'>Editar</th>
		</tr>";
        $data['table'] = '';
        if ($aux) {
            foreach ($aux as $a) {
                $botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
                $data['table'] .= '<tr><td>' . $a['name'] . '</td>
			<td>' . $a['middle_name'] . '</td>
			<td>' . $a['user_name'] . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
            }
        } else {
            
        }
        $this->output_json($data);
    }
    public function save_info()
    {
        $post = $this->input->post('users');
        $id_row = null;
        if ($post['id'] > 0) {
            $id_row = Users_Model::Load(array('select' => "*",
                'where' => 'id=' . $post['id'],
                'result' => '1row'));
        }
        if ($post['user_passwd'] != "") {
            $passwdFromPost = $post['user_passwd'];
            $newPasswd = $post['user_name'] . '?' . $passwdFromPost . '?' . 'uralvasm';
            $newPasswd = password_hash($newPasswd, PASSWORD_DEFAULT);
            $post['user_passwd'] = $newPasswd;
        } else {
            unset($post['user_passwd']);
        }
        $post['is_client'] = 1;
        $post['status'] = 1;
        if (!$id_row) {
            $result = Users_Model::Insert($post);
        } else {
            $result = Users_Model::Update($post, 'id=' . $post['id']);
            Users_Has_Modules_Model::Delete('user_id=' . $post['id']);
			$permisos =  json_decode($this->input->post('permisos'));
			
            foreach ($permisos as $key) {
                if ($key->respuesta == 1) {
                    $data = Users_Has_Modules_Model::Insert(array('user_id' => $post['id'],
                        'module_id' => $key->module_id,
                    ));
                }
            }
        }
        $this->output_json($result);
    }
    public function eliminar()
    {
        $id = $this->input->post("id");
        Users_Model::Delete('id=' . $id);
    }
}
