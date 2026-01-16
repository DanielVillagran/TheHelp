<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'phpass-0.1/PasswordHash.php';

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

/**
 * Tank_auth
 *
 * Authentication library for Code Igniter.
 *
 * @package        Tank_auth
 * @author        Ilya Konyukhov (http://konyukhov.com/soft/)
 * @version        1.0.9
 * @based on    DX Auth by Dexcell (http://dexcell.shinsengumiteam.com/dx_auth)
 * @license        MIT License Copyright (c) 2008 Erick Hartanto
 */
class Tank_auth
{

    private $error = array();

    public function __construct()
    {
        $this->ci = &get_instance();

        $this->ci->load->config('tank_auth', true);

        $this->ci->load->library('session');
        $this->ci->load->database();
        $this->ci->load->model('tank_auth/Users');
    }
    public function is_logged_in($activated = true)
    {
        /*
        $hasher = new PasswordHash(
        $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
        $this->ci->config->item('phpass_hash_portable', 'tank_auth'));
        $hashed_password = $hasher->HashPassword("sanborns");
        echo $hashed_password." ";
         */
        return $this->ci->session->userdata('status') === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
    }
    public function login($login, $password, $remember, $login_by_username, $login_by_email)
    {
        if ((strlen($login) > 0) and (strlen($password) > 0)) {

            // Which function to use to login (based on config)
            if ($login_by_username and $login_by_email) {
                $get_user_func = 'get_user_by_email_pass';
            } else if ($login_by_username) {
                $get_user_func = 'get_user_by_username';
            } else {
                $get_user_func = 'get_user_by_email';
            }
            if ($get_user_func == 'get_user_by_email_pass') {
                $user = $this->ci->Users->$get_user_func($login, $password);
            } else {
                $user = $this->ci->Users->$get_user_func($login);
            }
            if (!is_null($user)) {
                // login ok
                // Does password match hash in database?
                $hasher = new PasswordHash(
                    $this->ci->config->item('phpass_hash_strength', 'tank_auth'), $this->ci->config->item('phpass_hash_portable', 'tank_auth'));

                //var_dump(sha1($password));
                //var_dump($user->password);
                //if ($hasher->CheckPassword($password, $user->password)) {     // password ok

                //echo $password."

                //echo $user->user_passwd;
                if (password_verify($password, $user->user_passwd)) {
                    // password ok

                    /*
                    if ($user->banned == 1) {                                 // fail - banned
                    $this->error = array('banned' => $user->ban_reason);

                    } else {
                     */
                    $this->ci->session->set_userdata(array(
                        'user_id' => $user->id,
                        'user_type' => $user->user_type,
                        'username' => $user->user_name,
                        'client' => $user->is_client,
                        //'modules' => $this->ci->Users->get_modules($user->id),
                        'status' => ($user->status == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
                    ));

                    if ($user->status == 0) {
                        // fail - not activated
                        $this->error = array('not_activated' => '');
                    } else {
                        // success
                        /*
                        if ($remember) {
                        $this->create_autologin($user->id);
                        }
                         */
                        //$this->clear_login_attempts($login);

                        //$this->ci->Users->update_login_info(
                        //$user->user_id, $this->ci->config->item('login_record_ip', 'tank_auth'), $this->ci->config->item('login_record_time', 'tank_auth'));
                        return true;
                    }
                    //}
                } else {
                    // fail - wrong password
                    $this->error = array('password' => 'auth_incorrect_password');
                }
            } else {
                // fail - wrong login
                //$this->increase_login_attempt($login);
                $this->error = array('login' => 'auth_incorrect_login');
            }
        }
        return false;
    }

    /**
     * Logout user from the site
     *
     * @return  void
     */
    public function logout()
    {
        //$this->delete_autologin();
        // See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
        //$this->ci->session->set_userdata(array('id' => '', 'username' => '', 'status' => ''));

        $this->ci->session->sess_destroy();
    }

    public function clear_characters($String)
    {
        $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
        $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
        $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
        $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
        $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
        $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
        $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
        $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
        $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
        $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
        $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']', 'Ø', '”'), "", $String);
        $String = str_replace("ç", "c", $String);
        $String = str_replace("Ç", "C", $String);
        $String = str_replace("ñ", "n", $String);
        $String = str_replace("Ñ", "N", $String);
        $String = str_replace("Ý", "Y", $String);
        $String = str_replace("ý", "y", $String);

        $String = str_replace("&aacute;", "a", $String);
        $String = str_replace("&Aacute;", "A", $String);
        $String = str_replace("&eacute;", "e", $String);
        $String = str_replace("&Eacute;", "E", $String);
        $String = str_replace("&iacute;", "i", $String);
        $String = str_replace("&Iacute;", "I", $String);
        $String = str_replace("&oacute;", "o", $String);
        $String = str_replace("&Oacute;", "O", $String);
        $String = str_replace("&uacute;", "u", $String);
        $String = str_replace("&Uacute;", "U", $String);
        return $String;
    }
    public function get_user_id()
    {
        return $this->ci->session->userdata('user_id');
    }
    public function get_user_type()
    {
        return $this->ci->session->userdata('user_type');
    }
    public function get_user_is_client()
    {
        return $this->ci->session->userdata('client');
    }
    public function get_user_email()
    {
        $email = 'correo@sistemas.com';
        $id = $this->get_user_id();
        if ($id > 0) {
            $options = array(
                'select' => 'user_name as uemail',

                'clauses' => array('id' => $id),
                'result' => '1row',
            );
            $user = Users_Model::Load($options);
            if ($user) {
                $email = $user->uemail;
            }
        }
        return $email;
    }
    public function get_user_name()
    {
        $id = $this->get_user_id();
        $name = 'Pedro Perez';
        if ($id > 0) {
            $options = array(
                'select' => "CONCAT(name , ' ', middle_name ) as name",
                'clauses' => array('id' => $id),
                'result' => '1row',
            );
            $user = Users_Model::Load($options);
            if ($user) {
                $name = ucwords(strtolower($user->name));
            }
        }
        return $name;
    }
    public function get_user_name_por_id($id)
    {
       
        $name = 'Pedro Perez';
        if ($id > 0) {
            $options = array(
                'select' => "CONCAT(name , ' ', middle_name ) as name",
                'clauses' => array('id' => $id),
                'result' => '1row',
            );
            $user = Users_Model::Load($options);
            if ($user) {
                $name = ucwords(strtolower($user->name));
            }
        }
        return $name;
    }
    public function get_user_role_id()
    {
        $id = $this->get_user_id();
        $role_id = 0;
        if ($id > 0) {
            $options = array(
                'select' => "user_role_id",
                'clauses' => array('id' => $id),
                'result' => '1row',
            );
            $user = Users_Model::Load($options);
            if ($user) {
                $role_id = $user->user_role_id;
            }
        }
        return $role_id;
    }
    public function get_user_avatar()
    {
        $avatar = '/assets/images/avatar/women.jpg';
        $id = $this->get_user_id();
        if ($id > 0) {
            $options = array(
                'select' => 'image',
                'clauses' => array('id' => $id),
                'result' => '1row',
            );
            $user = Users_Model::Load($options);
            if ($user) {
                if (!empty($user->user_img)) {
                    if ($user->user_img != "default_avatar_female.jpg") {
                        $avatar = "/" . $user->user_img;
                    }

                }
            }
        }
        return $avatar;
    }

    public function get_user_role()
    {
        $id = $this->get_user_id();
        $role = '';
        if ($id > 0) {
            $options = array(
                'select' => 'groups.name,users_groups.group_id,users_groups.user_id',
                'joins' => array(
                    'groups' => 'groups.id=users_groups.group_id',
                ),
                'clauses' => array('users_groups.user_id' => $id),
                'result' => '1row',
            );
            //$rol = Users_Groups_Model::Load($options);
            $rol = "Programador web";
            if ($rol) {
                $role = $rol;
            }
        }
        return $role;
    }
    public function user_has_privilege($id_privilege = '', $userid = 0)
    {
        $has_privilege = false;
        if (!empty($id_privilege)) {
            $aux = $this->get_modules($userid);
            if ($aux) {
                $privileges = array();
                foreach ($aux as $a) {
                    $privileges[] = $a->nombre;
                }
                if (in_array($id_privilege, $privileges)) {
                    $has_privilege = true;
                }
            }
        }
        return $has_privilege;
    }
    public function get_modules($userid = 0)
    {
        $user = ($userid > 0) ? $userid : $this->ci->session->userdata('user_id');
        return $this->ci->Users->get_modules($user);
    }

    public function get_users()
    {

        return $this->ci->Users->get_users();
    }
    public function get_logos($type)
    {
        $aux = Logos_Model::Load(array('select' => "*",
            'where' => 'type=' . $type,
            'result' => '1row'));
        if (!$aux) {
            $aux = "";
        } else {
            $aux = $aux->imagen;
        }
        return $aux;
    }

}
function get_hash_password($pass = '')
{
    $hash_pass = '';
    if (!empty($pass)) {
        $hash_pass = sha1($pass);
    }
    return $hash_pass;
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */
