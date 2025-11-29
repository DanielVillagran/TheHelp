<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Noticias_Model extends ANT_Model {

	protected static $table = 'noticias';
	protected static $uk = array('id');

	function __construct() {
		parent::__construct();
	}

	static function get_grid_info($user_type) {
		$lista = '';
		$result = array();
		$options = array(
			'select' => '*',
			'result' => 'array',
			'where'=>'user_type='.$user_type
		);
		$result = Noticias_Model::Load($options);
		return $result;
	}

	static function get_by_id($id = 0) {
		return Users_Model::Load(array('joinsLeft' => array('erp_contacts' => 'erp_contacts.id = users.erp_account_id'), 'clauses' => array('users.id' => $id), 'result' => '1row'));
	}

	static function get_by_email($email = '') {
		return Erp_Contacts_Model::Load(array('clauses' => array('email' => $email), 'result' => '1row'));
	}
	static function user_has_privilege($id_privilege = '', $userid = 0) {
		$result['has'] = false;
		if ($userid != 0) {
			$aux = Users_Model::Query("SELECT 'true' as has_privilege,usuariosTCE_permisos.id_tipo_permiso from users_user
                LEFT JOIN usuariosTCE_permisos on users_user.user_id = usuariosTCE_permisos.id_usuario
                LEFT JOIN usuariosTCE_submodulos on usuariosTCE_submodulos.id = usuariosTCE_permisos.id_submodulo
                LEFT JOIN usuariosTCE_modulos on usuariosTCE_submodulos.id_modulo = usuariosTCE_modulos.id
                LEFT JOIN usuariosTCE_departamentos on usuariosTCE_departamentos.id = usuariosTCE_modulos.id_departamento
                WHERE CONVERT(varchar(10),usuariosTCE_departamentos.id)+'_'+CONVERT(varchar(10),usuariosTCE_modulos.identificador)+'_'+CONVERT(varchar(10),usuariosTCE_submodulos.identificador)='" . $id_privilege . "' AND users_user.user_id=" . $userid . "
                ");
			if ($aux) {
				$result = $aux[0];
			}
		}
		return $result;
	}
	static function user_has_special_privilege($id_privilege = '', $userid = 0) {
		$result['has'] = false;
		if ($userid != 0) {
			$aux = Users_Model::Query("SELECT 'true' as has_privilege, usuariosTCE_permisos_especiales.id_tipo_permiso from users_user
                LEFT JOIN usuariosTCE_permisos_especiales on users_user.user_id = usuariosTCE_permisos_especiales.id_usuario
                LEFT JOIN usuariosTCE_submodulos_especiales on usuariosTCE_submodulos_especiales.id=usuariosTCE_permisos_especiales.id_submodulo_especial
                LEFT JOIN usuariosTCE_submodulos on usuariosTCE_submodulos.id = usuariosTCE_submodulos_especiales.id_submodulo
                LEFT JOIN usuariosTCE_modulos on usuariosTCE_submodulos.id_modulo = usuariosTCE_modulos.id
                LEFT JOIN usuariosTCE_departamentos on usuariosTCE_departamentos.id = usuariosTCE_modulos.id_departamento
                WHERE CONVERT(varchar(10),usuariosTCE_departamentos.id)+'_'+CONVERT(varchar(10),usuariosTCE_modulos.identificador)+'_'+CONVERT(varchar(10),usuariosTCE_submodulos.identificador)='" . $id_privilege . "' AND users_user.user_id=" . $userid . "
                ");
			if ($aux) {
				$result = $aux[0];
			}
		}
		return $result;
	}

	static function get_profile_info($id = 0, $onlyAddress = FALSE) {
		$info = array(
			'account' => FALSE,
			'contact' => FALSE,
			'user' => FALSE,
			'group' => FALSE,
			'address' => FALSE,
			'phones' => FALSE,
			'tags' => FALSE,
			'membership' => FALSE,
			'membership2' => FALSE,
		);
		if ($id > 0) {
			$info['contact'] = Erp_Contacts_Model::Load(array('clauses' => array('id' => $id), 'result' => '1row'));
			if (isset($info['contact']->erp_account_id)) {
				if ($onlyAddress) {
					$info = Erp_Contacts_Address_Model::Load(array('clauses' => array('erp_contact_id' => $info['contact']->id), 'result' => '1row', 'sortBy' => 'main', 'sortDirection' => 'DESC'));
					unset($info->inserted, $info->insertedBy, $info->modified, $info->modifiedBy);
				} else {
					$options = array(
						'select' => 'erp_accounts.*, client_status.name as status,client_type.name as client_type_name,erp_contacts.id as erp_contact_id',
						'joinsLeft' => array('client_status' => 'client_status.id = erp_accounts.client_status_id', 'erp_account_details' => 'erp_account_details.erp_account_id=erp_accounts.id', 'client_type' => 'client_type.id=erp_account_details.client_type_id', 'erp_contacts' => 'erp_contacts.erp_account_id = erp_accounts.id'),
						'clauses' => array('erp_accounts.id' => $info['contact']->erp_account_id),
						'result' => '1row',
					);
					$info['account'] = Erp_Accounts_Model::Load($options);
					$info['address'] = Erp_Contacts_Address_Model::Load(array('clauses' => array('erp_contact_id' => $info['contact']->id), 'result' => '1row', 'sortBy' => 'main', 'sortDirection' => 'DESC'));
					$info['phones'] = Erp_Contacts_Phones_Model::Load(array('clauses' => array('erp_contact_id' => $info['contact']->id), 'result' => 'array', 'sortBy' => array('main' => 'DESC', 'id' => 'ASC')));
					$info['user'] = Users_Model::Load(array('clauses' => array('erp_account_id' => $info['contact']->id), 'result' => '1row', 'sortBy' => 'id', 'sortDirection' => 'DESC'));
					$memberships = Erp_Membership_Rates_Model::Load(array('select' => 'erp_membership_rates.*,inv_products.name as producto,client_status.name as client_status, payment_conditions.name as payment_condition', 'joinsLeft' => array('inv_products' => 'inv_products.id = erp_membership_rates.product_id', 'client_status' => 'client_status.id = erp_membership_rates.concept', 'payment_conditions' => 'payment_conditions.id = erp_membership_rates.payment_conditions_id'), 'where' => 'erp_contact_id =' . $info['account']->erp_contact_id, 'result' => 'array', 'limit' => '1', 'sortBy' => 'id', 'sortDirection' => 'ASC'));

					if ($memberships) {
						foreach ($memberships as $key => $membership) {
							$membership['erp_account_details_eff_date'] = date('d/m/Y', strtotime($membership['erp_account_details_eff_date']));
							$membership['erp_account_details_exp_date'] = date('d/m/Y', strtotime($membership['erp_account_details_exp_date']));
							$info['membership'] = $membership;
							$memberships2 = Erp_Membership_Rates_Model::Load(array('where' => 'erp_contact_id =' . $info['account']->erp_contact_id . " AND status = 1 AND id !=" . $membership['id'], 'result' => 'array', 'limit' => '1', 'sortBy' => 'id', 'sortDirection' => 'ASC'));
							if ($memberships2) {
								$memberships2[0]['expiration_date'] = date('d/m/Y', strtotime($memberships2[0]['expiration_date']));
								$info['membership2'] = $memberships2;
							}
						}
					}

					if ($info['user']) {
						$options = array(
							'select' => 'users_groups.id,users_groups.user_id,users_groups.group_id,groups.name as role',
							'joins' => array('groups' => 'groups.id = users_groups.group_id'), 'clauses' => array('users_groups.user_id' => $info['user']->id),
							'result' => '1row',
						);
						$info['group'] = Users_Groups_Model::Load($options);
					}
					$info['details'] = Erp_Accounts_Details_Model::Load(array('clauses' => array('erp_account_id' => $info['account']->id), 'result' => '1row', 'sortBy' => 'id', 'sortDirection' => 'DESC'));
					if ($info['account']) {
						$info['account']->is_principal = (isset($info['account']->erp_account_type_id) && $info['account']->erp_account_type_id == 12) ? TRUE : FALSE;
						if ($info['account']->is_principal) {
							$tags = '<span class="tm-tag tm-tag-success"> <span> Staff</span></span>';
							if (isset($info['group']->role)) {
								$tags .= '<span class="tm-tag tm-tag-info"> <span> ' . $info['group']->role . '</span></span>';
							}
							$info['tags'] = (object) array('tags' => $tags);
						}
					}
					foreach (array('contact', 'account', 'user', 'address') as $t) {
						if ($info[$t]) {
							unset($info[$t]->inserted, $info[$t]->insertedBy, $info[$t]->modified, $info[$t]->modifiedBy);
							if (isset($info[$t]->password)) {
								unset($info[$t]->password);
							}
						}
					}
				}
			}
		}
		return $info;
	}

	static function get_list_campaigns($ant_account_id) {
		$options = array(
			'select' => "users.id,
            concat(
            erp_contacts.first_name, ' ', erp_contacts.last_name, ' ', erp_contacts.second_last_name
        ) as name",
			'joins' => array(
				'users_groups' => 'users_groups.user_id = users.id',
				'groups' => 'groups.id = users_groups.group_id',
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
			),
			'where' => "groups.id != 6  AND users.active = 1 and users.ant_account_id =" . $ant_account_id,
			'sortBy' => 'erp_contacts.first_name, erp_contacts.last_name, erp_contacts.second_last_name',
			'sortDirection' => 'DESC',
			'result' => 'array',
			'groupBy' => 'users.id, erp_contacts.first_name, erp_contacts.last_name, erp_contacts.second_last_name',
		);
		$lista = '';
		$aux = Users_Model::Load($options);
		if ($aux) {
			foreach ($aux as $a) {
				$lista .= '<option value="' . $a['id'] . '">' . $a['name'] . '</option>';
			}
		}
		return $lista;
	}

	static function get_modules_users($id_user) {
		$options = array(
			'select' => "users.email, groups.name, modules.name, groups.name as grupo",
			'joins' => array('users_groups' => 'users.id = users_groups.user_id',
				'groups' => 'groups.id = users_groups.group_id',
				'group_has_module' => 'groups.id = group_has_module.group_id',
				'modules' => 'modules.id = group_has_module.module_id'),
			'where' => "users.id='$id_user'",
		);
		$aux = Users_Model::Load($options);
		return $aux;
	}

	static function get_modules($modulo, $userid = 0) {
		$options = array(
			'select' => 'modules.name',
			'joins' => array(
				'users_groups' => 'users.id = users_groups.user_id',
				'groups' => 'groups.id = users_groups.group_id',
				'group_has_module' => 'groups.id = group_has_module.group_id',
				'modules' => 'modules.id = group_has_module.module_id',
			),
			'clauses' => array('users.id' => $userid),
		);
		$options['where'] = (is_numeric($modulo)) ? 'modules.id = ' . $modulo : "modules.name ILIKE '{$modulo}'";
		$aux = Users_Model::Load($options);
		return $aux;
	}

	static function get_by_group($group = '') {
		$options = array(
			'select' => "users.id, concat(trim(erp_contacts.first_name),' ',trim(erp_contacts.last_name),' ',trim(erp_contacts.second_last_name)) as name, groups.name as rol, erp_contacts.id as contact_id",
			'joins' => array(
				'users_groups' => 'users_groups.user_id = users.id',
				'groups' => 'groups.id = users_groups.group_id',
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
			),
			'clauses' => array('users.active' => 1),
			'result' => 'array',
			'sortBy' => 'groups.name, users.first_name, users.last_name',
		);
		if ('' != $group && 'all' != strtolower($group)) {
			if ($group == 'Ventas') {
				$options['clauses']['in']['groups.name'] = array('Gerente Comercial', 'Director Comercial', 'Ventas', 'CM');
			} else {
				$options['clauses']['groups.name'] = $group;
			}
		}
		$users = Users_Model::Load($options);
		return $users;
	}

	static function get_by_many_groups($groups) {
		$options = array(
			'select' => "users.id, concat(trim(erp_contacts.first_name),' ',trim(erp_contacts.last_name),' ',trim(erp_contacts.second_last_name)) as name, groups.name as rol, erp_contacts.id as contact_id",
			'joins' => array(
				'users_groups' => 'users_groups.user_id = users.id',
				'groups' => 'groups.id = users_groups.group_id',
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
			),
			'clauses' => array('users.active' => 1),
			'result' => 'array',
			'sortBy' => 'groups.name, users.first_name, users.last_name',
		);

		$options['clauses']['in']['groups.name'] = $groups;

		$users = Users_Model::Load($options);
		return $users;
	}

	static function get_by_group_and_by_sucursal($group = '', $sucursal = '') {
		$options = array(
			'select' => "users.id, concat(trim(erp_contacts.first_name),' ',trim(erp_contacts.last_name),' ',trim(erp_contacts.second_last_name)) as name, groups.name as rol, erp_contacts.id as contact_id",
			'joins' => array(
				'users_groups' => 'users_groups.user_id = users.id',
				'groups' => 'groups.id = users_groups.group_id',
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
			),
			'clauses' => array('users.active' => 1, 'ant_account_id' => $sucursal),
			'result' => 'array',
			'sortBy' => 'groups.name, users.first_name, users.last_name',
		);
		if ('' != $group && 'all' != strtolower($group)) {
			if ($group == 'Ventas') {
				$options['clauses']['in']['groups.name'] = array('Gerente Comercial', 'Director Comercial', 'Ventas', 'CM');
			} else {
				$options['clauses']['groups.name'] = $group;
			}
		}
		$users = Users_Model::Load($options);
		return $users;
	}

	static function get_group_ventas_by_sucursal($sucursal = '') {
		$options = array(
			'select' => "users.id, concat(trim(erp_contacts.first_name),' ',trim(erp_contacts.last_name),' ',trim(erp_contacts.second_last_name)) as name, groups.name as rol, erp_contacts.id as contact_id",
			'joins' => array(
				'users_groups' => 'users_groups.user_id = users.id',
				'groups' => 'groups.id = users_groups.group_id',
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
			),
			'clauses' => array('users.active' => 1, 'groups.name' => 'Ventas'),
			'result' => 'array',
			'sortBy' => 'groups.name, users.first_name, users.last_name',
		);

		if ($sucursal != 'all') {
			$options['clauses']['ant_account_id'] = $sucursal;
		}

		$users = Users_Model::Load($options);
		return $users;
	}

	static function get_group_recepcion_by_sucursal($sucursal = '') {
		$options = array(
			'select' => "users.id, concat(trim(erp_contacts.first_name),' ',trim(erp_contacts.last_name),' ',trim(erp_contacts.second_last_name)) as name, groups.name as rol, erp_contacts.id as contact_id",
			'joins' => array(
				'users_groups' => 'users_groups.user_id = users.id',
				'groups' => 'groups.id = users_groups.group_id',
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
			),
			'clauses' => array('users.active' => 1, 'groups.name' => 'Recepcion'),
			'result' => 'array',
			'sortBy' => 'groups.name, users.first_name, users.last_name',
		);

		if ($sucursal != 'all') {
			$options['clauses']['ant_account_id'] = $sucursal;
		}

		$users = Users_Model::Load($options);
		return $users;
	}

	static function get_module($userid = 0) {
		$options = array(
			'select' => 'erp_contacts.id, groups.name',
			'joins' => array(
				'users_groups' => 'users.id = users_groups.user_id',
				'groups' => 'groups.id = users_groups.group_id',
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
			),
			'clauses' => array('users.id' => $userid),
		);
		//$options['where'] = (is_numeric($modulo)) ? 'modules.id = ' . $modulo : "modules.name ILIKE '{$modulo}'";
		$aux = Users_Model::Load($options);
		return $aux;
	}

	static function get_asignado($where = NULL, $list = NULL, $agent = NULL) {
		$lista = '';
		$options = array(
			'select' => 'users.id, users.active, erp_contacts.first_name, erp_contacts.last_name, erp_contacts.second_last_name, erp_contacts.email, erp_contacts.notes, groups.name as rol, erp_contacts.id as erp_contact_id',
			'joinsLeft' => array(
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
				'users_groups' => 'users_groups.user_id = users.id',
				'groups' => 'groups.id = users_groups.group_id',
			),
			'result' => 'array',
		);
		if (!empty($agent)) {
			$options['joinsLeft'] += array('ant_accounts' => 'ant_accounts.id = users.ant_account_id');
		}
		if (!empty($where)) {
			if (is_string($where)) {
				$options['where'] = $where;
			} else {
				$options['where'] = array();
				foreach ($where as $key => $value) {
					$options['where'][] = "{$key} {$value['op']} {$value['val']}";
				}
				$options['where'] = implode(' AND ', $options['where']);
			}
		}
		$result = Users_Model::Load($options);
		if (!empty($list)) {
			$lista = '<option value=""> Todos </option>';
			foreach ($result as $a) {
				$a['id'] = !empty($agent) ? $a['erp_contact_id'] : $a['id'];
				$lista .= '<option value="' . $a['id'] . '">' . $a['first_name'] . " " . $a['last_name'] . " - " . $a['email'] . '</option>';
			}
			$result = $lista;
		}
		return $result;
	}

	static function get_agents($list = false, $ant_account_id = 0, $obj = false, $fechaI = '', $fechaF = '', $agent_id = '') {
		$options = array(
			'select' => "users.id, users.active, concat(erp_contacts.first_name, ' ', erp_contacts.last_name, ' ', erp_contacts.second_last_name) as agent_name, erp_contacts.email, erp_contacts.notes, groups.name as rol, erp_contacts.id as erp_contact_id, users.agent_target as target",
			'joinsLeft' => array(
				'erp_contacts' => 'erp_contacts.id = users.erp_account_id',
				'users_groups' => 'users_groups.user_id = users.id',
				'groups' => 'groups.id = users_groups.group_id',
			),
			'result' => 'array',
			'where' => "groups.name != 'Clientes' AND groups.name != 'Proveedor' AND erp_contacts.id is not null",
		);
		if ($ant_account_id > 0) {
			$options['where'] .= " AND users.ant_account_id =" . $ant_account_id;
		}
		if ($obj) {
			$options['joinsLeft'] += array('erp_targets' => 'erp_targets.agent_id = erp_contacts.id');
			$options['select'] .= ', erp_targets.target, erp_targets.accomplishment, erp_targets.month, erp_targets.year, erp_targets.agent_id';
			if ($fechaI != '' && $fechaF != '') {
				$options['where'] .= " AND to_Date(erp_targets.year || '-' || erp_targets.month || '-01', 'YYYY-mm-dd') between '{$fechaI}' AND '{$fechaF}'";
			} else if ($fechaI != '') {
				$options['where'] .= " AND to_Date(erp_targets.year || '-' || erp_targets.month || '-01', 'YYYY-mm-dd') >= '{$fechaI}'";
			} else if ($fechaF != '') {
				$options['where'] .= " AND to_Date(erp_targets.year || '-' || erp_targets.month || '-01', 'YYYY-mm-dd') <= '{$fechaF}'";
			}
			if ($agent_id != '') {
				$options['where'] .= " AND users.id = {$agent_id}";
			}
		}
		if ($list) {
			$result = Users_Model::Load($options);
			if ($result) {
				$lista = '';
				foreach ($result as $a) {
					$lista .= '<option value="' . $a['erp_contact_id'] . '">' . $a['agent_name'] . '</option>';
				}
				$result = $lista;
			}
		} else {
			$result = Users_Model::Load($options);
		}
		return $result;
	}

	static function get_commissions($post) {
		$ant_account_id = isset($post['ant_account_id']) ? $post['ant_account_id'] : 0;
		$fechaI = $post['fecha_inicio'];
		$fechaF = $post['fecha_final'];
		$agent_id = $post['vendedor'];
		$agents = Users_Model::get_agents(false, $ant_account_id, true, $fechaI, $fechaF, $agent_id);
		$data = array();
		if ($agents) {
			$data = array();
			foreach ($agents as $index => $agent) {
				$get_commissions = Erp_Targets_Model::get_target_totals($agent['agent_id'], $agent['target'], $agent['month'], $agent['year']);
				if ($get_commissions) {
					$agent['month'] = str_replace(
						array(
							'1', '2', '3', '4', '5', '5', '7', '8', '9', '10', '11', '12', '/',
						),
						array(
							'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
							'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', ' de ',
						),
						$agent['month']
					);
					$get_commissions['month'] = $agent['month'];
					$get_commissions['year'] = $agent['year'];
					$get_commissions['target'] = $agent['target'];
					$data[] = $get_commissions;
				}
			}
		}
		/*var_dump($data);
        die();*/
		return $data;
	}

}
