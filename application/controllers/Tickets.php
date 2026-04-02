<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once BASEPATH . '../application/models/common_library.php';
use PHPMailer\PHPMailer\PHPMailer;

require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';

class Tickets extends ANT_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/');
			return;
		}
	}
	function index()
	{
		$data['title'] = 'Tickets';
		$data['view'] = 'grids/Tickets';
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Tickets/list', $data);
	}
	function add()
	{
		$data['title'] = 'Agregar Ticket';
		$data['view'] = 'forms/Tickets';
		$data['id'] = 0;
		$data['styles'] = 'jquery.shuttle';
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['empresas'] = $this->get_empresas_select_for_user();
		$data['tipos_servicios'] = Tipos_Servicios_Model::get_select();
		$data['readonly'] = false;
		$data['ticket_history'] = array();
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Tickets/add', $data);
	}
	function edit($id)
	{
		$data['title'] = 'Ver Ticket';
		$data['view'] = 'forms/Tickets';
		$data['styles'] = 'jquery.shuttle';
		$data['id'] = $id;
		$data['js_scripts'] = 'lib/jquery.shuttle';
		$data['empresas'] = $this->get_empresas_select_for_user();
		$data['tipos_servicios'] = Tipos_Servicios_Model::get_select();
		$data['readonly'] = true;
		$data['ticket_history'] = Tickets_History_Model::Load(array(
			'select' => "tickets_history.*, TRIM(CONCAT(COALESCE(u.name,''), ' ', COALESCE(u.middle_name,''), ' ', COALESCE(u.last_name,''))) as usuario_nombre",
			'joinsLeft' => array(
				'users_user as u' => 'u.id = tickets_history.createdBy'
			),
			'where' => 'tickets_history.ticket_id=' . intval($id),
			'sortBy' => 'tickets_history.createdAt',
			'sortDirection' => 'DESC',
			'result' => 'array'
		));
		$data['user_id'] = $this->tank_auth->get_user_id();
		$this->_load_views('Tickets/add', $data);
	}
	function get_info_Tickets()
	{
		$post = $this->input->post();
		$data = Tickets_Model::Load(array(
			'select' => "*",
			'where' => 'id=' . $post['id'],
			'result' => '1row'
		));
		$this->output_json($data);
	}
	function get_Tickets()
	{
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$can_delete = intval($user_role_id) <= 2;
		$where = "";
		if ($user_role_id > 2) {
			$where = "e.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		$aux = Tickets_Model::get_grid_info($where);
		$data['head'] = "<tr>
		<th>Empresa</th>
		<th>Sede</th>
		<th>Tipo de Ticket</th>
		<th>Status</th>
		<th>Fecha</th>
		<th>Descripcion</th>
		<th class='th-editar-colonia'>Editar</th>
		</tr>";
		$data['table'] = '';
		if ($aux) {
			foreach ($aux as $a) {
				$botones = '<button type="button" class="btn btn-default row-edit" rel="' . $a['id'] . '"><i class="fa fa-eye"></i></button>';
				if ($can_delete) {
					$botones .= '<button type="button" class="btn btn-default row-delete" rel="' . $a['id'] . '"><i class="fa fa-trash"></i></button>';
				}
				if (isset($a['status']) && $a['status'] == 'Pendiente') {
					$botones .= '<button type="button" class="permisoCompletar btn btn-default row-edit" rel="' . $a['id'] . '" tipo="second" tipo-servicio-id="' . intval($a['tipoServicioId']) . '"><i class="fa fa-check"></i></button>';
				}
				$status = isset($a['status']) ? $a['status'] : '';
				$status_badge = '<span class="badge" style="background-color:#9e9e9e; color:#fff;">' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</span>';
				if ($status === 'Pendiente') {
					$status_badge = '<span class="badge" style="background-color:#f0ad4e; color:#fff;">Pendiente</span>';
				} elseif ($status === 'Completado') {
					$status_badge = '<span class="badge" style="background-color:#5cb85c; color:#fff;">Completado</span>';
				}
				$fecha = '';
				if (!empty($a['createdAt'])) {
					$fecha = $a['createdAt'];
				} elseif (!empty($a['created_at'])) {
					$fecha = $a['created_at'];
				} elseif (!empty($a['created'])) {
					$fecha = $a['created'];
				}
				$data['table'] .= '<tr>
				<td>' . htmlspecialchars($a['empresa_nombre'], ENT_QUOTES, 'UTF-8') . '</td>
				<td>' . htmlspecialchars($a['sede_nombre'], ENT_QUOTES, 'UTF-8') . '</td>
				<td>' . htmlspecialchars($a['tipoServicio'], ENT_QUOTES, 'UTF-8') . '</td>
				<td>' . $status_badge . '</td>
				<td>' . htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') . '</td>
				<td>' . htmlspecialchars(isset($a['descripcion']) ? $a['descripcion'] : '', ENT_QUOTES, 'UTF-8') . '</td>
			<td class="td-center"><div class="btn-toolbar"><div class="btn-group btn-group-sm">' . $botones . '</div></div></td></tr>';
			}
		} else {
		}
		$this->output_json($data);
	}
	function get_Tickets_pendientes()
	{
		$tickets = 0;
		$completar = $this->tank_auth->user_has_privilege('Completar tickets');

		if ($completar) {
			$user_role_id = $this->tank_auth->get_user_role_id();
			$user_id = $this->tank_auth->get_user_id();
			$where = "tickets.status='Pendiente'";
			if ($user_role_id > 2) {
				$where .= " AND e.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
			}
			$aux = Tickets_Model::get_Tickets_pendientes($where);
			if ($aux) {
				$tickets = intval($aux->pendientes);
			}
		}
		$this->output_json(array('pendientes' => $tickets));
	}
	function save_info()
	{
		$post = $this->input->post('users');
		$payload = $this->build_ticket_payload($post);

		if ($post['id'] == 0) {
			$result = Tickets_Model::Insert($payload);
			if (!empty($result['insert_id'])) {
				$ticket_id = intval($result['insert_id']);
				$this->log_ticket_history($ticket_id, 'Pendiente');
				$this->send_email_nuevo_ticket($ticket_id);
			}
		} else {
			$result = Tickets_Model::Update($payload, 'id=' . intval($post['id']));
		}
		$this->output_json($result);
	}
	function eliminar()
	{
		if (intval($this->tank_auth->get_user_role_id()) !== 2) {
			$this->output_json(array('status' => false, 'message' => 'No tienes permisos para eliminar tickets.'));
			return;
		}
		$id = $this->input->post("id");
		Tickets_Model::Delete('id=' . $id);
		$this->output_json(array('status' => true));
	}
	function completar()
	{
		$id = $this->input->post("id");
		$comentario = trim((string)$this->input->post('comentario'));
		$ticket = Tickets_Model::Load(array(
			'select' => 'id, tipoServicioId',
			'where' => 'id=' . intval($id),
			'result' => '1row'
		));

		if (!$ticket) {
			$this->output_json(array('status' => false, 'message' => 'No se encontro el ticket.'));
			return;
		}

		if ($comentario === '') {
			$this->output_json(array('status' => false, 'message' => 'Debes capturar un comentario.'));
			return;
		}

		$documento = null;
		$requiere_documento = intval($ticket->tipoServicioId) === 2;
		if ($requiere_documento) {
			if (
				empty($_FILES['documento']) ||
				!isset($_FILES['documento']['tmp_name']) ||
				$_FILES['documento']['tmp_name'] === '' ||
				!file_exists($_FILES['documento']['tmp_name'])
			) {
				$this->output_json(array('status' => false, 'message' => 'Debes adjuntar un documento.'));
				return;
			}
			$documento = $this->guardar_documento_historial($_FILES['documento'], intval($id));
			if ($documento === null) {
				$this->output_json(array('status' => false, 'message' => 'No se pudo guardar el documento.'));
				return;
			}
		}

		Tickets_Model::Update(array('status' => 'Completado'), 'id=' . $id);
		$this->log_ticket_history(intval($id), 'Completado', $comentario, $documento);
		$this->output_json(array('status' => true));
	}

	private function get_empresas_select_for_user()
	{
		$user_role_id = $this->tank_auth->get_user_role_id();
		$user_id = $this->tank_auth->get_user_id();
		$where = "";
		if ($user_role_id > 2) {
			$where = "empresas.id in (SELECT empresa_id from empresas_has_users where user_id=$user_id)";
		}
		return Empresas_Model::get_select($where);
	}

	private function build_ticket_payload($post)
	{
		$payload = array();
		$empresa_id = intval($post['empresa'] ?? 0);
		$sede_id = intval($post['sede'] ?? 0);
		$descripcion = trim((string)($post['descripcion'] ?? ''));
		$tipo_servicio_id = intval($post['tipoServicioId'] ?? 0);
		$user_id = intval($this->tank_auth->get_user_id());
		$payload['empresaId'] = $empresa_id;
		$payload['sedeId'] = $sede_id;
		$payload['descripcion'] = $descripcion;
		$payload['tipoServicioId'] = $tipo_servicio_id;

		if (empty($post['id'])) {
			$payload['status'] = 'Pendiente';
		}
		if (empty($post['id']) && $user_id > 0) {
			$payload['createdBy'] = $user_id;
		}

		return $payload;
	}

	private function log_ticket_history($ticket_id, $status, $comentario = null, $documento = null)
	{
		if ($ticket_id <= 0 || !$this->db->table_exists('tickets_history')) {
			return;
		}

		$user_id = intval($this->tank_auth->get_user_id());

		Tickets_History_Model::Insert(array(
			'ticket_id' => $ticket_id,
			'status' => $status,
			'comentario' => $comentario,
			'documento' => $documento,
			'createdBy' => $user_id > 0 ? $user_id : null
		));
	}

	private function send_email_nuevo_ticket($ticket_id)
	{
		$ticket = Tickets_Model::Load(array(
			'select' => "tickets.*, e.nombre as empresa_nombre, s.nombre as sede_nombre, ts.nombre as tipo_ticket_nombre, ts.usuario_asignado, ts.con_copia_correo, u.user_name as correo_destino, TRIM(CONCAT(COALESCE(u.name,''), ' ', COALESCE(u.middle_name,''), ' ', COALESCE(u.last_name,''))) as usuario_asignado_nombre",
			'joinsLeft' => array(
				'empresas as e' => 'e.id = tickets.empresaId',
				'empresas_sedes as s' => 's.id = tickets.sedeId',
				'tipo_servicios as ts' => 'ts.id = tickets.tipoServicioId',
				'users_user as u' => 'u.id = ts.usuario_asignado'
			),
			'where' => 'tickets.id=' . intval($ticket_id),
			'result' => '1row'
		));

		if (!$ticket || empty($ticket->correo_destino)) {
			return;
		}

		$usuario_nombre = trim((string)$ticket->usuario_asignado_nombre);
		if ($usuario_nombre === '') {
			$usuario_nombre = trim((string)$ticket->correo_destino);
		}

		$this->send_email(array(
			'correo' => trim((string)$ticket->correo_destino),
			'con_copia_correo' => intval($ticket->con_copia_correo) === 1,
			'usuario_asignado' => $usuario_nombre,
			'empresa' => !empty($ticket->empresa_nombre) ? $ticket->empresa_nombre : 'Sin empresa',
			'sede' => !empty($ticket->sede_nombre) ? $ticket->sede_nombre : 'Sin sede',
			'tipo_ticket' => !empty($ticket->tipo_ticket_nombre) ? $ticket->tipo_ticket_nombre : 'Sin tipo',
			'descripcion' => !empty($ticket->descripcion) ? $ticket->descripcion : '',
			'ticket_id' => intval($ticket->id)
		));
	}

	public function send_email($datos)
	{
		date_default_timezone_set("America/Mexico_City");

		$correo_destino = trim((string)($datos['correo'] ?? ''));
		if ($correo_destino === '') {
			return;
		}

		$usuario_asignado = htmlspecialchars((string)($datos['usuario_asignado'] ?? ''), ENT_QUOTES, 'UTF-8');
		$empresa = htmlspecialchars((string)($datos['empresa'] ?? ''), ENT_QUOTES, 'UTF-8');
		$sede = htmlspecialchars((string)($datos['sede'] ?? ''), ENT_QUOTES, 'UTF-8');
		$tipo_ticket = htmlspecialchars((string)($datos['tipo_ticket'] ?? ''), ENT_QUOTES, 'UTF-8');
		$descripcion = nl2br(htmlspecialchars((string)($datos['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8'));
		$ticket_id = intval($datos['ticket_id'] ?? 0);

		$message = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width" name="viewport" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700&display=swap" rel="stylesheet">
    <style type="text/css">
        body { margin: 0; padding: 0; background-color: #f4f4f4; font-family: Roboto, Arial, sans-serif; }
        table, td, tr { vertical-align: top; border-collapse: collapse; }
        .wrapper { width: 100%; background-color: #f4f4f4; padding: 24px 0; }
        .card { width: 100%; max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; }
        .header { background: #0066D1; color: #ffffff; padding: 24px; font-size: 22px; font-weight: 700; }
        .content { padding: 24px; color: #333333; font-size: 14px; line-height: 1.6; }
        .label { font-weight: 700; color: #111111; }
        .box { margin-top: 18px; padding: 16px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">Nuevo ticket asignado</div>
            <div class="content">
                <p>Hola ' . $usuario_asignado . ',</p>
                <p>Se ha creado un nuevo ticket y fue asignado a tu usuario.</p>
                <div class="box">
                    <p><span class="label">Ticket:</span> #' . $ticket_id . '</p>
                    <p><span class="label">Empresa:</span> ' . $empresa . '</p>
                    <p><span class="label">Sede:</span> ' . $sede . '</p>
                    <p><span class="label">Tipo de ticket:</span> ' . $tipo_ticket . '</p>
                    <p><span class="label">Descripcion:</span><br>' . $descripcion . '</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';

		$mail = new PHPMailer();
		$mail->SMTPDebug = 0;
		$mail->isSMTP();
		$mail->Host = 'mail.vmcomp.com.mx';
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
		$mail->Username = 'sender@vmcomp.com.mx';
		$mail->Password = 'Odvm200796*';

		$mail->setFrom('sender@vmcomp.com.mx', 'The Help');
		$mail->Subject = 'Nuevo ticket asignado';
		$mail->isHTML(true);
		$mail->Body = $message;
		$mail->AddAddress($correo_destino);
		if (!empty($datos['con_copia_correo'])) {
			$mail->addCC('david.vl@thehelp.com.mx');
		}

		if (!$mail->Send()) {
			log_message('error', 'Error al enviar correo de ticket: ' . $mail->ErrorInfo);
		}
	}

	private function guardar_documento_historial($file, $ticket_id)
	{
		$file_path = 'assets/files/tickets/';
		if (!is_dir($file_path)) {
			@mkdir($file_path, 0777, true);
		}

		$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$nombre = pathinfo($file['name'], PATHINFO_FILENAME);
		$nombre = preg_replace('/[^A-Za-z0-9_\-]/', '', $nombre);
		if ($nombre === '') {
			$nombre = 'documento';
		}
		$filename = 'ticket_' . intval($ticket_id) . '_cierre_' . date('YmdHis') . '_' . $nombre . '.' . $extension;
		$destino = $file_path . DIRECTORY_SEPARATOR . $filename;

		if (!move_uploaded_file($file['tmp_name'], $destino)) {
			return null;
		}

		return 'files/tickets/' . $filename;
	}
}
