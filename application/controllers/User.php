<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';

class User extends ANT_Controller {

	/**
	 * Controlador que manejara la información de usuarios, autentificacion, perfiles y autorizacion.
	 */
	public function index($logout = '') {
		if (!$this->tank_auth->is_logged_in()) {
			$data['logout'] = $logout;
			$this->load->view('user/login', $data);

		} else {
			redirect('home');
		}
	}

	public function forgot() {
		$this->load->view('user/forgot_password');
	}

	public function login() {
		header('Access-Control-Allow-Origin: http://localhost');
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Max-Age: 604800");
		header("Content-type: application/json");

		$data = $this->input->post();
		if (strpos($data["CorreoUser"], "@") === false) {
			$data["CorreoUser"] = $data["CorreoUser"] . "@esprezza.com";
		} else {
			$data["CorreoUser"] = $data["CorreoUser"];
		}
		$pass_hashed = $data['CorreoUser'] . '?' . $data['NameUser'] . '?' . 'uralvasm';
		//$pass_hashed= password_hash($pass_hashed, PASSWORD_DEFAULT);
		//echo($pass_hashed);

		echo json_encode($this->tank_auth->login($data["CorreoUser"], $pass_hashed, false, true, true));
	}

	public function logout() {
		//$this->registrar_salida();
		$this->tank_auth->logout();
		redirect('/');
	}
	public function profile($id = 0, $account = 0) {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/');
			return;
		}
		$data['user'] = FALSE;
		if ($id > 0) {
			$data['module'] = Modules_Model::get_module();
			$data['cuenta'] = Users_Model::get_profile_info($id);

			$data['view'] = array(
				'forms/perfil',
				'grids/tickets_user',
				'grids/groups_modules',
			);
			$data['user_id'] = $this->tank_auth->get_user_id();
			$id_user = $this->tank_auth->get_user_id();
			$data['modules'] = Users_Model::get_modules_users($id_user);
			$data['select_pais'] = Erp_Countries_Model::get_list();
			$data['select_sangre'] = Erp_Blood_Types_Model::get_list_options();
			$data['select_roles'] = Groups_Model::get_options_list();
			$data['rol'] = Groups_Model::get_list();
			$data['account_id'] = $account;

			$data['edit_target'] = '';
			$logged_user_role = $this->tank_auth->get_user_role();

			// ¿Tiene el usuario logeado permisos y el perfil es un usuario del sistema?
			if (in_array($logged_user_role, array('Administrador', 'Gerente Comercial', 'Gerente de Sucursal')) == false) {
				$data['edit_target'] = ' readonly ';
			}

			if ($data['cuenta']['group'] == false || $data['cuenta']['group']->role !== 'Ventas') {
				$data['edit_target'] = 'NO_APLICA';
			}

			$this->_load_views('user/profile', $data);
		} else {
			redirect('/home');
			return;
		}
	}

	public function change() {
		$data = $this->input->post();
		echo json_encode($this->tank_auth->change_password($data['newpass']));
	}

	function get_user_perfil() {
		$data = array('ressult' => FALSE, 'message' => 'Usuario no encontrado.');
		$userid = $this->tank_auth->get_user_id();
		if ($userid > 0) {
			$options = array(
				'select' => 'erp_account_id',
				'result' => '1row',
				'clauses' => array('id' => $userid),
			);
			$user = Users_Model::Load($options);
			if ($user) {
				if ($user->erp_account_id > 0) {
					$data = array('result' => TRUE, 'perfil_id' => $user->erp_account_id);
				} else {
					$data['message'] = 'Usuario sin relacion de cuenta';
				}
			}
		}
		$this->output_json($data);
	}

	function save_tags() {
		$search = $this->input->post('name');
		if ($search == '') {
			return $this->output_json(array('result' => FALSE, 'message' => 'No hay tags por guardar.'));
		}
		$dato['id_contact'] = $this->input->post('id_contact');
		$tag = Contact_Tags_Model::search_tags($search);
		$idt = $nam = '';
		if ($tag) {
			foreach ($tag as $t) {
				$idt = $t['id'];
				$nam = $t['name'];
			}
		}
		if ($nam == $search) {
			$dato['id_contact_tags'] = $idt;
			return $this->output_json(Contact_HaveTags_Model::save_tag($dato));
		} else {
			$data['name'] = $search;
			$tags[] = Contact_Tags_Model::save_tag($data);
			foreach ($tags as $idta) {
				$idtt = $idta['insert_id'];
			}
			$dato['id_contact_tags'] = $idtt;
			return $this->output_json(Contact_HaveTags_Model::save_tag($dato));
		}
	}

	function tags() {
		$data = array('result' => FALSE, 'message' => 'No se pudo leer el registro.');
		$id = $this->input->post('id_contact');
		if ($id > 0) {
			$aux = Contact_HaveTags_Model::tags($id);
			if ($aux) {
				$data = array('result' => TRUE, 'info' => $aux);
			}
		}
		$this->output_json($data);
	}

	function delete_tag() {
		$data = array('result' => FALSE, 'message' => 'No se encontro la etiqueta.');
		$id = $this->input->post('id_tag');
		if ($id > 0) {
			$result = Contact_HaveTags_Model::Delete(array('id_contact_tags' => $id));
			if ($result['result']) {
				$data = array('result' => TRUE);
			} else {
				$data['message'] = $result['error'];
			}
		}
		$this->output_json($data);
	}

	function update_group() {
		$data = array('result' => FALSE, 'message' => 'No se recibieron datos');
		$id = $this->input->post('id');
		$user_id = $this->input->post('user_id');
		$group_id = $this->input->post('group_id');
		if ($group_id > 0 && ($id > 0 || $user_id > 0)) {
			if ($id > 0) {
				$result = Users_Groups_Model::Update(array('group_id' => $group_id), array('id' => $id));
			} else {
				$result = Users_Groups_Model::Insert(array('user_id' => $user_id, 'group_id' => $group_id));
			}
			$data = array('result' => $result['result']);
			if (!$data['result']) {
				$data['message'] = $result['error'];
			}
		}
		$this->output_json($data);
	}

	public function get_row_tickets() {
		$data = '<tr><td colspan="5">No hay datos</td></tr>';
		$post = $this->input->post();
		$id = $this->tank_auth->get_user_id();
		$aux = Tickets_Model::get_grid_id($id);
		if ($aux) {
			$data = '';
			foreach ($aux['records'] as $a) {
				//$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['erp_tickets_id'] . '"><i class="zmdi zmdi-edit"></i></button>';

				$data .= '<tr><td>' . $a['titulo'] . '</td><td>' . $a['asignado'] . '</td><td>' . $a['relacionado'] . '</td></tr>';
			}
		}
		$this->output_json($data);
	}

	public function impersonate($id = 0) {
		if ($id > 0) {
			$this->tank_auth->impersonate($id);
		} else {
			echo "User id no found";
		}
	}

	public function get_users_ingreso() {
		echo "Creating File <br>\n\r";
		$data = array();
		$resut = Erp_Membership_Rates_Model::get_membership_ingreso();
		echo "Getting Users <br>\n\r";
		foreach ($resut as $user) {
			if (empty($data)) {
				$data = 'var usuarios = {
                   ';
			} else {
				$data = $data . ',
                ';
			}
			if ($user['foto']) {
				$photo_stri = explode("/", $user['foto']);
				$photo = $photo_stri[2];

			} else {
				$photo = 'nophoto.png';
			}
			$user['nombre'] = str_replace('"', '', $user['nombre']);
			if ($user['motivo'] == 'Falta de pago') {
				$user['vigente'] = 'denegado';
			}
			$data = $data . '"' . $user['membresia'] . '": [
       {"nombre": "' . $user['nombre'] . '", "acceso": "' . $user['vigente'] . '", "foto" : "' . $photo . '","erp_contact_id" : "' . $user['erp_contact_id_acceso'] . '", "motivo": "' . $user['motivo'] . '", "nombre_producto": "' . $user['product_name'] . '","entrada": "' . $user['start_date'] . '","salida": "' . $user['end_date'] . '" },
   ]';

		}
		// '.$user['vigente'].'
		$data = $data . '};';
		echo $data . " <br>\n\r";
		$file = fopen("ingreso/assets/js/usuarios.js", "w");
		fwrite($file, $data . PHP_EOL);
		fclose($file);
		echo "Creating Users.js file <br>\n\r";

	}

	public function recover_password($rp_uniqid) {
		if ($rp_uniqid != null && $rp_uniqid != '') {
			$user = Users_Model::Load(array(
				'select' => 'id',
				'where' => "forgotten_password_code = '{$rp_uniqid}'",
				'result' => '1row'));
			if ($user) {
				$data = array('id' => $user->id);
				$this->load->view('user/recover_password', $data);
			} else {
				redirect('/');
				return;
			}
		} else {
			redirect('/');
			return;

		}
	}

	public function send_recover_link() {
		$data = array('result' => FALSE, 'message' => 'Error al enviar email.', 'msg_type' => '');
		$email = $this->input->post('email');
		if ($this->isValidEmail($email)) {
			$options = array(
				'select' => "users.id, users.email, CONCAT(TRIM(erp_contacts.first_name),' ', TRIM(erp_contacts.last_name), ' ', TRIM(erp_contacts.second_last_name)) as name",
				'joins' => array('erp_contacts' => 'erp_contacts.id = users.erp_account_id'),
				'result' => '1row',
				'clauses' => array('users.email' => $email),
			);
			$user = Users_Model::Load($options);
			if ($user) {
				$codigo = $user->id . uniqid();
				$aux = Users_Model::Update(array('forgotten_password_code' => $codigo), array('id' => $user->id));
				$request = '<email>'
					. '<from>donotreply@esprezza.com</from>'
					. "<to>{$email}</to>"
					. "<content>recover_template.xml</content>"
					. '<type>recover_password</type>'
					. '<subject>Solicitud de Recuperación de Contraseña</subject>'
					. "<username>{$user->name}</username>"
					. "<logo>{$_SERVER['HTTP_HOST']}/assets/images/dexfit_gris.png</logo>"
					. "<url>http://{$_SERVER['SERVER_NAME']}/user/recover_password/{$codigo}</url></email>";
				$this->ant_send_email('recover_password', $request);

				$data['result'] = TRUE;
				$data['msg_type'] = 'Enlace de recuperación enviado';
				$data['message'] = 'Se le ha enviado un enviado un enlace de recuperación a su correo.';
			} else {
				$data['msg_type'] = 'Correo no registrado';
				$data['message'] = 'El correo electrónico ingresado no está registrado.';
			}
		} else {
			$data['msg_type'] = 'Correo inválido';
			$data['message'] = 'Por favor introduzca un correo electrónico válido';
		}
		$this->output_json($data);
	}

	function save_password() {
		$data = array('result' => FALSE, 'message' => 'No se pudo actualizar la contraseña.');
		$id = $this->input->post('id');
		$pass = $this->input->post('newpass');
		if ($id > 0 && !empty($pass)) {
			// Solo para asegurar que existe ese usuario y es unico
			$user = Users_Model::Load(array('select' => 'id', 'where' => 'id=' . $id, 'result' => '1row'));
			if ($user) {
				$aux = Users_Model::Update(array('password' => sha1($pass), 'forgotten_password_code' => ''), array('id' => $id));
				if ($aux) {
					$data['message'] = 'Contraseña actualizada correctamente.';
					$data['result'] = $aux['result'];
				}
			}
		}
		$this->output_json($data);
	}

	function get_user_id_info() {
		$id = $this->input->post('id');

		$result = Users_Model::Load($id);

		$this->output_json($result);
	}

	function get_user_id_info2() {
		$id = $this->input->get('id');

		$result = Users_Model::Load($id);

		$this->output_json($result);
	}

	public function link($campania_id, $contacto_id) {
		$campania = Callcenter_Campaigns_Model::Load($campania_id);

		$contacto_info = Erp_Contacts_Model::Load(
			array(
				'joinsLeft' => array(
					'erp_accounts' => 'erp_accounts.id = erp_contacts.erp_account_id',
				),
				'where' => "erp_contacts.id = {$contacto_id}",
			)
		);

		$users_info = Users_Model::Load(
			array(
				'select' => 'erp_contacts.id',
				'joinsLeft' => array(
					'users_groups' => 'users_groups.user_id = users.id',
					'groups' => 'groups.id = users_groups.group_id',
					'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
				),
				'where' => array(
					"groups.name" => "Gerente de Sucursal",
					"users.ant_account_id" => intval($contacto_info[0]->ant_account_id),
				),
			)
		);

		$nombre = "{$contacto_info[0]->first_name} {$contacto_info[0]->last_name} {$contacto_info[0]->second_last_name}";

		foreach ($users_info as $key => $value) {

			$existe = Notification_Model::Load(
				array(
					'where' => array(
						'erp_contact_id' => intval($value->id),
						'erp_ticket_id' => 0,
						'message' => "Campaña - {$nombre} ha ingresado al link de la campaña",
						'type' => 200,
						'erp_account_contact_id' => intval($contacto_id),
						'custom_url' => "/campaigns/view/{$campania_id}",
					),
				)
			);

			if (!$existe) {
				Notification_Model::Insert(
					array(
						'erp_contact_id' => intval($value->id),
						'erp_ticket_id' => 0,
						'message' => "Campaña - {$nombre} ha ingresado al link de la campaña",
						'active' => 1,
						'type' => 200,
						'erp_account_contact_id' => intval($contacto_id),
						'custom_url' => "/campaigns/view/{$campania_id}",
					)
				);
			}
		}
		$url = parse_url($campania->link);

		if (!$url['scheme']) {
			$campania->link = "http://{$campania->link}";
		}

		header("Location: {$campania->link}");
	}

	function get_contact_by_user_id() {
		$id = $this->input->post('id');

		$result = Users_Model::Load(
			array(
				'select' => "
            users.id,
            CONCAT(
            erp_contacts.first_name, ' ',
            erp_contacts.last_name,  ' ',
            erp_contacts.second_last_name
            ) as nombre
            ",
				'joins' => array(
					'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
				),
				'where' => "users.id = {$id}",
				'result' => 'array',
			)
		);

		$this->output_json($result);
	}
	function get_contact_by_user_id_get() {
		$id = $this->input->get('id');

		$result = Users_Model::Load(
			array(
				'select' => "
            users.id,
            CONCAT(
            erp_contacts.first_name, ' ',
            erp_contacts.last_name,  ' ',
            erp_contacts.second_last_name
            ) as nombre
            ",
				'joins' => array(
					'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
				),
				'where' => "users.id = {$id}",
				'result' => 'array',
			)
		);

		$this->output_json($result);
	}

	public function get_role_now() {
		$rol = $this->tank_auth->get_user_role();

		$this->output_json($rol);
	}

	public function change_pass() {
		$data['title'] = "Cambio de contraseña";
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('user/change_pass', $data);
	}

	public function cambia_contrasena() {
		$options = array("select" => "user_name", "where" => "id=" . $this->tank_auth->get_user_id(), "result" => "1row");
		$recupera = Users_Model::Load($options);

		$clave = $this->input->post()['clave'];
		$clave = $recupera->user_name . "?" . $clave . "?" . "uralvasm";
		$clave = password_hash($clave, PASSWORD_DEFAULT);
		Users_Model::Update(array("user_passwd" => $clave), array("id" => $this->tank_auth->get_user_id()));
		$this->output_json(array("status" => "ok"));

	}

	public function cambia_foto() {

		$nombre_archivo = $_FILES['archivo']['name'];
		$tipo_archivo = $_FILES['archivo']['type'];
		$tamano_archivo = $_FILES['archivo']['size'];
		$tmp_archivo = $_FILES['archivo']['tmp_name'];
		$archivador = 'assets/files/images/' . $nombre_archivo;
		if (!move_uploaded_file($tmp_archivo, $archivador)) {
			echo ("ocurrio un error, no pudo cargarse el archivo.");
		} else {
			Users_Model::Update(array("user_img" => $archivador), array("user_id" => $this->tank_auth->get_user_id()));
		}
		$this->output_json(array("foto" => $archivador));
	}
	function eliminar(){
		$id = $this->input->post("id");
		Users_Model::Delete('id='.$id);
	}
	public function add_token_to_user() {
		$token = $this->input->post('token');
		$user_id = $this->tank_auth->get_user_id();
		$result = Users_Model::Query("UPDATE users_user set token='' where token='" . $token . "'");
		$result = Users_Model::Query("UPDATE users_user set token='" . $token . "' where id=" . $user_id);
		$this->output_json($result);
	}
}
