<?php
require_once BASEPATH.'../reviews/rev_driver.php';

class ANT_Controller extends CI_Controller
{
	public $ruledriver;

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('ui');
		$this->load->library('tank_auth');
		$this->load->library('form_validation');
		$this->ruledriver = new rev_driver();
		$this->data['contacto_id'] = 3;
	}

	public function load_app_template($view = "index", $data = array())
	{
		$data["view"] = $view;
		$this->load->view("app_template/layout", $data);
	}

	public function load_ajax_template($view = 'index', $data = array())
	{
		$data['view'] = $view;
		$this->load->view('app_template/ajax', $data);
	}

	public function output_json($data)
	{
		if (isset($_GET['callback']))
		{
			$this->output->set_content_type('application/json')->set_output($_GET['callback'].'('.json_encode($data).')');
		}
		else
		{
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}

	function check_user()
	{
		$url = $_SERVER['REQUEST_URI'];
		$urltemp = explode("/", $url);
		if (@$urltemp[1] != 'auth' && !(@$urltemp[1]=='erp' && @$urltemp[2]=='orders'))
		{
			if (!$this->ion_auth->logged_in())
			{
				//redirect them to the login page
				$this->session->set_flashdata('message', 'Sin acceso');
				redirect('auth/login', 'refresh');
			}
			elseif (!$this->ion_auth->in_group('Tortas el Moreno') && !$this->ion_auth->in_group('ALEXANDRIA'))
			{
				//redirect them to the login page
				//$this->session->set_flashdata('message', 'Usuario sin permisos para acceder a este  modulo');
				//redirect('/', 'refresh');
			}
		}
	}

	function evaluate_rules($rules, $array_data = array()){

		$rulesResult = $this->ruledriver->process($rules, $array_data);
		$rulesInfo = array();
		$rulesWarning = array();
		$rulesError = array();

		if($rulesResult->Revisions != ""){
			foreach($rulesResult->Revisions as $rule){
				if($rule->type == 1){
					$rulesError[] = $rule;
				}
				if($rule->type == 2){
					$rulesWarning[] = $rule;
				}
				if($rule->type == 3){
					$rulesInfo[] = $rule;
				}
			}
		}

		$data["validation"] = true;
		if($rulesResult->Result){
			$data["validation"] = false;
			$data["errors"] = $rulesError;
			$data["warnings"] = $rulesWarning;
			$data["info"] = $rulesInfo;
		}

		return $rulesResult->Revisions != ""?$this->print_rules($data):NULL;
	}

	function print_rules($data){
		$info = $data["info"];
		$warnings = $data["warnings"];
		$errors = $data["errors"];

		if(!$data["validation"]){

					$html = "<div class=\"content\">";
					if(count($info)>0)
					{
						$html = $html."
							 <div class=\"rounded\">";
								foreach ($info as $item){
									$html = $html."<strong>".$item->RuleIdentifier."</strong> - ".$item->Message."</br>";
								}
							 $html = $html."</div>";
					}
					if(count($warnings)>0)
					{
						$html = $html."
							<div class=\"rounded\">";
								foreach ($warnings as $item){
									$html = $html."<strong>".$item->RuleIdentifier."</strong> - ".$item->Message."</br>";
								}
							 $html = $html."</div>";
					}
					if(count($errors)>0)
					{
						$html = $html."
							<div class=\"rounded\">";
								foreach ($errors as $item){
									$html = $html."<strong>".$item->RuleIdentifier."</strong> - ".$item->Message."</br>";
								}
							$html = $html."</div>";
					}
					$html = $html."</div>";
		}
		return $html;

	}

	function getCatalogs(){

		return null;
	}

	function getTimeZone(){
		$timezone = "
					<select class=\"form-control\" id=\"timezone\" name=\"timezone\">
	                     <optgroup label=\"Pacific Time Zone\">
	                       <option value=\"8\">California - PST</option>
	                     </optgroup>
	                     <optgroup label=\"Arizona Mountain Time Zone\">
	                       <option value=\"12\">Arizona - MST</option>
	                     </optgroup>
	                     <optgroup label=\"Mountain Time Zone\">
	                       <option value=\"17\">New Mexico - MST</option>
	                     </optgroup>
	                     <optgroup label=\"Central Time Zone\">
	                       <option value=\"33\">Texas - CST</option>
	                     </optgroup>
	                  </select>
				";
		  return $timezone;
	}

	function getTimeSet(){
		 $timeset = "
		 				<select class=\"form-control\" id=\"timeset\" name=\"timeset\">
			                    <option>12:00 AM</option>
			                    <option>12:30 AM</option>
			                    <option>1:00 AM</option>
			                    <option>1:30 AM</option>
			                    <option>2:00 AM</option>
			                    <option>2:30 AM</option>
			                    <option>3:00 AM</option>
			                    <option>3:30 AM</option>
			                    <option>4:00 AM</option>
			                    <option>4:30 AM</option>
			                    <option>5:00 AM</option>
			                    <option>5:30 AM</option>
			                    <option>6:00 AM</option>
			                    <option>6:30 AM</option>
			                    <option>7:00 AM</option>
			                    <option>7:30 AM</option>
			                    <option>8:00 AM</option>
			                    <option>8:30 AM</option>
			                    <option>9:00 AM</option>
			                    <option>9:30 AM</option>
			                    <option>10:00 AM</option>
			                    <option>10:30 AM</option>
			                    <option>11:00 AM</option>
			                    <option>11:30 AM</option>
			                    <option>12:00 PM</option>
			                    <option>12:30 PM</option>
			                    <option>1:00 PM</option>
			                    <option>1:30 PM</option>
			                    <option>2:00 PM</option>
			                    <option>2:30 PM</option>
			                    <option>3:00 PM</option>
			                    <option>3:30 PM</option>
			                    <option>4:00 PM</option>
			                    <option>4:30 PM</option>
			                    <option>5:00 PM</option>
			                    <option>5:30 PM</option>
			                    <option>6:00 PM</option>
			                    <option>6:30 PM</option>
			                    <option>7:00 PM</option>
			                    <option>7:30 PM</option>
			                    <option>8:00 PM</option>
			                    <option>8:30 PM</option>
			                    <option>9:00 PM</option>
			                    <option>9:30 PM</option>
			                    <option>10:00 PM</option>
			                    <option>10:30 PM</option>
			                    <option>11:00 PM</option>
			                    <option>11:30 PM</option>
			                  </select>
		 		";
		return $timeset;
	}

	function isValidEmail($text = '')
	{
		return ((FALSE == filter_var($text, FILTER_VALIDATE_EMAIL))?FALSE:TRUE);
	}

	function generate_police_number($id = 0, $view = 'HB')
	{
		$police_number = '';
		/*if ($id>0)
		{
			$options = array(
				'select'=>'orders.id, agencies.producer_code, orders.view, orders.policy_nbr',
				'joins'=>array('agencies'=>'agencies.id=orders.agent_id'),
				'clauses'=>array('orders.id'=>$id),
				'result'=>'1row'
			);
			$order = Orders_Model::Load($options);
			if ($order)
			{
				if (empty($order->policy_nbr))
				{
					$view = ($order->view=='agent')?'HB':'HC';
					$police_number = sprintf('%05s', $order->producer_code) . $view . sprintf('%06s', $order->id);
				}
				else
				{
					$police_number = $order->policy_nbr;
				}
			}
		}*/
		return $police_number;
	}

	function get_notifications()
	{
		//$sql = "SELECT * FROM email_notification WHERE erp_contact_id = ".$this->tank_auth->get_erp_contact_id()." AND active = 1 ORDER BY id DESC";
		//return Tickets_Model::Query($sql);
		return Notification_Model::get_notifications($this->tank_auth->get_erp_contact_id());
	}

	function _load_views($content, $data)
	{
		//$data['notifications'] = Notification_Model::get_notifications($this->tank_auth->get_erp_contact_id());
		$this->load->view("/common/header", $data);
		$this->load->view("/common/leftnav", $data);
		$this->load->view("{$content}", $data);
		$this->load->view("/common/footer", $data);
	}

	/**
	 * is_valid_post_file()
	 *
	 * To validate if a received file exits, is of the expected type, or desired size.
	 *
	 * @param	array	$filevar with a variable of the received file.
	 * @param	string	$filename	with a name of variable of the file to be validated.
	 * @param	array	$valid_types with an array of mime types allowed.
	 * @param	integer	$max_size_allowed with a size in bytes to define the max size allowed, set to zero to ignore.
	 *
	 * @return	array	$valid with the result of the processed data:
	 * 					- boolean   exist with the TRUE value if file exists, any else is FALSE.
	 * 					- boolean   error with the TRUE value if there is an error, any else is FALSE.
	 * 					- string    message with a string of the error, if there is an error.
	 */
	function is_valid_post_file($filevar = NULL, $filename = '', $valid_types = array('image/gif','image/jpeg','image/png','image/pjpeg','image/jpg'), $max_size_allowed = 0)
	{
		$valid = array('exist'=>TRUE, 'error'=>FALSE, 'message'=>'');
		if (!empty($filevar))
		{
			if (empty($filevar['tmp_name'][$filename]) || !file_exists($filevar['tmp_name'][$filename]))
			{
				$valid['exist'] = FALSE;
			}
			elseif (!in_array($filevar['type'][$filename], $valid_types))
			{
				$valid['error'] = TRUE;
				$valid['message'] = 'El tipo de archivo no es valido.';
			}
			elseif ($max_size_allowed>0 && $filevar['size'][$filename]>$max_size_allowed)
			{
				$valid['error'] = TRUE;
				$valid['message'] = 'El archivo supera el maximo permitido.';
			}
		}
		return $valid;
	}

	function is_valid_post_doc($filevar = NULL, $filename = '', $valid_types = array('image/doc','image/pdf','image/txt'), $max_size_allowed = 2097152)
	{
		return $this->is_valid_post_file($filevar, $filename, $valid_types, $max_size_allowed);
	}

	function is_valid_ant_account($id_account=0)
	{
		$is_valid = FALSE;
		if ($id_account>0)
		{
			$options = array(
				'select'=>'ant_account_id',
				'clauses'=>array('id'=>$this->tank_auth->get_user_id()),
				'result'=>'1row'
			);
			$logged_ant_account_id = $this->get_user_ant_account();
			$account_ant_account_id = Erp_Accounts_Model::Load($options);
			$account_ant_account_id = ($account_ant_account_id)?$account_ant_account_id->ant_account_id:0;
			$is_valid = ($this->is_sa_dev() || $logged_ant_account_id==$account_ant_account_id)?TRUE:FALSE;
		}
		return TRUE; //$is_valid;
	}

	function is_sa_dev()
	{
		return in_array($this->tank_auth->get_username(), array('cancun'));
	}

	function getRealIP()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}

	function get_user_ant_account()
	{
		$options = array(
			'select'=>'ant_account_id',
			'clauses'=>array('id'=>$this->tank_auth->get_user_id()),
			'result'=>'1row'
		);
		$logged_ant_account_id = Users_Model::Load($options);
		return ($logged_ant_account_id)?$logged_ant_account_id->ant_account_id:0;
	}

	function get_user_ant_account_info($ant_account_id=0)
	{
		$options = array(
			'select'=>'
				ant_accounts.id,
				ant_accounts.name,
				ant_accounts.website,
				ant_accounts.comments,
				ant_accounts.logo,
				ant_accounts.flag_invaccount,
				ant_accounts.numero_registro_patronal,
				ant_accounts.foliada, saldo,
				ant_accounts.prefijo,
				ant_accounts.nombre_apoderado,
				ant_accounts.sexo_apoderado,
				ant_accounts.tipo_apoderado,
				ant_accounts.next_folio,
				ant_accounts.serial,
				ant_accounts.color_facturacion as color,
				ant_accounts.informacion_adicional,
				inv_accounts.carta_porte,
				inv_accounts.fiscal_locales as impuestos_locales',
			'joinsLeft' => array('inv_accounts' => 'inv_accounts.ant_account_id = ant_accounts.id'),
			'clauses'=>array('ant_accounts.id'=>$ant_account_id),
			'result'=>'1row'
		);
		return Ant_Accounts_Model::Load($options);
	}

	function hasPrivilege($privilege='', $userid=0)
	{
		$has_privilege = FALSE;
		if ($privilege!='')
		{
			$userid = ($userid>0)?$userid:$this->tank_auth->get_user_id();
			$aux = Users_Model::get_modules($privilege, $userid);
			if ($aux)
			{
				$has_privilege = TRUE;
			}
		}
		return $has_privilege;
	}

	function registrar_salida()
	{
		$id = $this->tank_auth->get_user_id();
		if ($id>0)
		{
			Users_Model::Update(array('last_logout'=>date('Y-m-d H:i:s')), array('id'=>$id));
		}
	}

	/**
	 * Generaci\xF3n de Documentos en PDF disponible para su uso en cualquier controlador
	 */
	function get_pdf($type='', $info=array())
	{
		$this->load->library('pdf');
                $this->load->library('pdf_code');
		if (!in_array($type, array('protocolo','alberca','acondicionamiento','staff')) && !$this->tank_auth->is_logged_in())
		{
			redirect('/');
			return;
		}
		if ($type=='protocolo')
		{
			return $this->pdf_protocolo();
		}
		elseif ($type=='alberca')
		{
			return $this->pdf_alberca();
		}
		elseif ($type=='acondicionamiento')
		{
			return $this->pdf_acondicionamiento();
		}
		elseif ($type=='staff')
		{
			return $this->pdf_staff();
		}
		elseif ($type=='ausencias')
		{
			//'TITULAR', 'MEMBRESIA', 'INICIOAUSENCIA', 'NOMBRESOCIO', 'FINAUSENCIA', 'MOTIVO', 'MONTO', 'MESCARGO'
			return $this->pdf_ausencias($info);
		}
		elseif ($type=='aviso')
		{
			//'DOMICILIO', 'TELEFONOS', 'WEBSITE', 'ACTUALIZACION'
			return $this->pdf_aviso($info);
		}
		elseif ($type=='cancelacion')
		{
			//'NOMBRE', 'MEMBRESIA','TARJETA','MONTO','INICIO'
			return $this->pdf_cancelacion($info);
		}
		elseif ($type=='cargo')
		{
			//'FECHA', 'AFILIACION', 'NOMBRE_COMERCIAL', 'TARJETA', 'RAZON_SOCIAL', 'NOMBRE'=>'', 'CONTRATO', 'CONCEPTO', 'MONTO', 'PERIORICIDAD'
			return $this->pdf_cargo($info);
		}
		elseif ($type=='baja')
		{
			//'FECHA', 'NOMBRE', 'MEMBRESIA_TITULAR', 'MEMBRESIA_SOCIO', 'MOTIVO', 'TITULAR'
			return $this->pdf_baja($info);
		}
		elseif ($type=='horario')
		{
			// NOMBRE, MEMBRESIA, MULTA
			return $this->pdf_horario($info);
		}
		elseif ($type=='responsiva')
		{
			//'SOCIO', 'MEMBRESIA', 'RESPONSABLE', 'DIRECCION', 'MENOR', 'EDAD'
			return $this->pdf_responsiva($info);
		}
		elseif ($type=='cesion')
		{
			//'DIA', 'MES', 'ANIO', 'SOCIO', 'MEMBRESIA', 'NOMBRE', 'MOTIVO'
			return $this->pdf_cesion($info);
		}
		elseif ($type=='contrato')
		{
			//'TITULAR', 'DOMICILIO', 'TIPOMEMBRESIA', 'PERIODO', 'INSCRIPCION', 'MANTENIMIENTO', 'ADICIONALES', 'PENALIDAD', 'PORCIENTO', 'DIA', 'MES', 'ANIO'
			return $this->pdf_contrato($info);
		}
		elseif ($type=='modificaciones')
		{
			//'FECHA', 'TITULAR', 'MEMBRESIA', 'MOTIVO', 'CANTIDAD', 'PORCIENTO', TIPOMODIFICACION('MEMBRESIA', 'DEPENDIENTE', 'BAJA', 'REACTIVACION', 'REFERIDO')
			return $this->pdf_modificaciones($info);
		}
		elseif ($type=='credencial')
		{
			//'FECHA', 'TITULAR', 'MEMBRESIA', 'MOTIVO', 'CANTIDAD', 'PORCIENTO', TIPOMODIFICACION('MEMBRESIA', 'DEPENDIENTE', 'BAJA', 'REACTIVACION', 'REFERIDO')
			return $this->pdf_credencial($info);
		}
		else
		{
			redirect('/');
		}
	}

	function contract_atributes(&$pdf)
	{
		$pdf->SetLineWidth(0.1);
		$pdf->AliasNbPages();
		$pdf->SetTitle('Esprezza');
		$pdf->SetAuthor('esprezza.com');
		$pdf->SetCreator('esprezza.com');
		$pdf->SetSubject('Contrato');
		$pdf->SetKeywords('Consultoria');
		$pdf->SetDisplayMode('default','single');
		$pdf->SetLeftMargin(15);
		$pdf->SetRightMargin(15);
		$pdf->SetFillColor(200,200,200);
	}

	function add_page_header(&$pdf, $postion='left', $date=0, $pagesize='Letter', $page='P')
	{
		$pdf->AddPage($page, $pagesize);
		$pdf->SetLineWidth(0.2);
		$path = realpath(dirname(__FILE__).'/../../assets/images');
		$img1 = $path.DIRECTORY_SEPARATOR.'esprezza.png';
		$imgsize = 66;
		if ($postion=='center')
		{
			$x = 88;
			$y = 10;
			$xf = 15;
			$yf = 28;
			$align = 'C';
			$imgsize = 40;
			$width = 186;
		}
		elseif ($postion=='right')
		{
			$x = 130;
			$y = 10;
			$xf = 15;
			$yf = 25;
			$align = 'L';
			$width = 186;
		}
		elseif ($postion=='report')
		{
			$x = 20;
			$y = 14;
			$xf = 15;
			$yf = 28;
			$align = 'R';
			$imgsize = 50;
			$width = 325;
		}
		else
		{
			$x = 20;
			$y = 14;
			$xf = 15;
			$yf = 40;
			$align = 'R';
			$width = 186;
		}
		$pdf->Image($img1,$x,$y,$imgsize);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',10);
		if ($date>0)
		{
			$pdf->SetXY($xf,$yf);
			$pdf->Cell($width, 5, str_replace(array('january','february','march','april','may','june','july','august','september','october','november','december','#'), array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre','de'), strtolower(date("d # F # Y"))), 0, 1, $align);
		}
		else
		{
			$pdf->SetXY($xf,$yf);
		}
		$pdf->Ln();
	}


	function add_page_header_anytext(&$pdf, $postion='left', $text='', $pagesize='Letter', $page='P')
	{
		$pdf->AddPage($page, $pagesize);
		$pdf->SetLineWidth(0.2);
		$path = realpath(dirname(__FILE__).'/../../assets/images');
		$img1 = $path.DIRECTORY_SEPARATOR.'esprezza.png';
		$imgsize = 66;
		if ($postion=='center')
		{
			$x = 88;
			$y = 10;
			$xf = 15;
			$yf = 28;
			$align = 'C';
			$imgsize = 40;
			$width = 186;
		}
		elseif ($postion=='right')
		{
			$x = 130;
			$y = 10;
			$xf = 15;
			$yf = 25;
			$align = 'L';
			$width = 186;
		}
		elseif ($postion=='report')
		{
			$x = 20;
			$y = 14;
			$xf = 15;
			$yf = 28;
			$align = 'R';
			$imgsize = 50;
			$width = 325;
		}
		else
		{
			$x = 20;
			$y = 14;
			$xf = 15;
			$yf = 40;
			$align = 'R';
			$width = 186;
		}
		$pdf->Image($img1,$x,$y,$imgsize);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',10);

		$pdf->SetXY($xf,$yf);
		$pdf->Cell($width, 5, $text, 0, 1, $align);

		$pdf->Ln();
	}

	function add_page_header_anytext_horizontal(&$pdf, $postion='left', $text='', $pagesize='Legal', $page='L')
	{
		$pdf->AddPage($page, $pagesize);
		$pdf->SetLineWidth(0.2);
		$path = realpath(dirname(__FILE__).'/../../assets/images');
		$img1 = $path.DIRECTORY_SEPARATOR.'esprezza.png';
		$imgsize = 66;
		if ($postion=='center')
		{
			$x = 162;
			$y = 10;
			$xf = 15;
			$yf = 28;
			$align = 'C';
			$imgsize = 40;
			$width = 333;
		}
		elseif ($postion=='right')
		{
			$x = 130;
			$y = 10;
			$xf = 15;
			$yf = 25;
			$align = 'L';
			$width = 186;
		}
		elseif ($postion=='report')
		{
			$x = 20;
			$y = 14;
			$xf = 15;
			$yf = 28;
			$align = 'R';
			$imgsize = 50;
			$width = 325;
		}
		else
		{
			$x = 20;
			$y = 14;
			$xf = 15;
			$yf = 40;
			$align = 'R';
			$width = 186;
		}
		$pdf->Image($img1,$x,$y,$imgsize);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',10);

		$pdf->SetXY($xf,$yf);
		$pdf->Cell($width, 5, $text, 0, 1, $align);

		$pdf->Ln();
	}


	function add_page_header_general(&$pdf, $postion='left', $date=0, $pagesize='Legal', $page='L')
	{
		$pdf->AddPage($page, $pagesize);
		$pdf->SetLineWidth(0.2);
		$path = realpath(dirname(__FILE__).'/../../assets/images');
		$img1 = $path.DIRECTORY_SEPARATOR.'esprezza.png';
		$imgsize = 66;
		if ($postion=='center')
		{
			$x = 162;
			$y = 10;
			$xf = 15;
			$yf = 28;
			$align = 'C';
			$imgsize = 40;
			$width = 333;
		}
		elseif ($postion=='right')
		{
			$x = 130;
			$y = 10;
			$xf = 15;
			$yf = 25;
			$align = 'L';
			$width = 186;
		}
		elseif ($postion=='report')
		{
			$x = 20;
			$y = 14;
			$xf = 15;
			$yf = 28;
			$align = 'R';
			$imgsize = 50;
			$width = 325;
		}
		else
		{
			$x = 20;
			$y = 14;
			$xf = 15;
			$yf = 40;
			$align = 'R';
			$width = 186;
		}
		$pdf->Image($img1,$x,$y,$imgsize);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',10);
		if ($date>0)
		{
			$pdf->SetXY($xf,$yf);
			$pdf->Cell($width, 5, str_replace(array('january','february','march','april','may','june','july','august','september','october','november','december','#'), array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre','de'), strtolower(date("d # F # Y"))), 0, 1, $align);
		}
		else
		{
			$pdf->SetXY($xf,$yf);
		}
		$pdf->Ln();
	}
	function add_page_footer($pdf, $calle=null, $num_ext=null, $neighborhood=null,$type='general')
	{
		if ($type=='report')
		{
			$xpos = 10;
			$ypos = 206;
		}
		else
		{
			$xpos = 10;
			$ypos = 268;
		}
		if(($calle!=null and $num_ext!=null) or $neighborhood!=null)
		{

			$pdf->SetTextColor(200, 200, 200);
			$pdf->SetFont('Arial', '', 12);
			$pdf->Text($xpos, $ypos, $calle." ".$num_ext." Col. ".$neighborhood);
			$pdf->SetTextColor(0, 0, 0);

		}

	}

	/**
	 * Area Deportiva: Documento Protocolo de Servicio
	 */
	function pdf_protocolo()
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$pdf->AddPage("P", "Letter");
		$pdf->Cell(186,6,"",0,1);
		$textos = array(
			"#B#PROTOCOLO DE SERVICIO PARA EL COACH EN \xC1REA DE FUERZA Y CARDIO",
			"",
			"-	Recibir al socio con saludo franco",
			"-	Presentarse con nombre y primer  apellido y el puesto que ocupa en ese momento.(p. ej. Mi nombre es\n Rodrigo Camacho y en este momento estoy a cargo del \xE1rea de fuerza)",
			'-	Preguntar nombre del socio y corroborar (si la persona  no desea darnos su nombre no insistir) hablarle siempre de “usted" a menos que el socio nos indique lo contrario.',
			"-	Ponerse a sus \xF3rdenes e informarle que contamos con programas de entrenamiento sin costo si desea uno de acuerdo a sus objetivos y capacidades. (p. ej. “Ok se\xF1or Hern\xE1ndez estoy para servirle, le informo que si usted lo desea podemos asignarle un programa de entrenamiento sin costo de acuerdo a sus necesidades y capacidades) revisar anexo.",
			'-	Durante la estancia del socio el entrenador deber\xE1 acercarse por lo menos una vez con el fin de hacer sentir al socio que estamos al pendiente de \xE9l. (En un momento adecuado: “Se\xF1or Hern\xE1ndez como siente su entrenamiento el d\xEDa de hoy" \xF3 "Como v\xE1 en el desarrollo de su programa")  Retirarse si el socio indica que por ahora no requiere apoyo \xF3 apoyarle seg\xFAn sea el caso.',
			"-	Mantenerse visible en el \xE1rea en todo momento con el fin de prestar pronta ayuda a quien lo requiera. Podr\xE1 el coach solicitar apoyo de otro compa\xF1ero si el volumen de trabajo (cantidad de socios) lo amerita: otro coach, supervisor de \xE1rea, jefe de \xE1rea.",
			"-	Durante la asesor\xEDa a un socio explicar el porqu\xE9 de las sugerencias con lenguaje claro y sencillo.",
			"-	Es deseable que el coach motive al socio de manera franca si no, es mejor evitar comentarios.",
			"-	El tiempo m\xE1ximo para la atenci\xF3n de un socio es de 4 minutos pero puede variar  en funci\xF3n del volumen de socios o del tipo de socio (p. ej. Socios constantes vs. Socios principiantes)",
			"-	Despedirse del socio dici\xE9ndole que lo esperamos ma\xF1ana (crear compromiso)",
			"",
			"#B#Recomendaciones para el seguimiento y atenci\xF3n de los socios:",
			"",
			"•	Mant\xE9n siempre una actitud positiva y con energ\xEDa esto se nota en el desempe\xF1o.",
			"•	Cuida tu aspecto, sonr\xEDe  cuida la imagen de tu \xE1rea, s\xE9 franco en tus palabras.",
			"•	Escucha al socio, sus necesidades son nuestra raz\xF3n de existir como empresa.",
			"•	No tienes la obligaci\xF3n de saberlo todo, si desconoces algo pregunta a quien pueda informar verazmente pero no se vale no querer aprender por flojera.",
			"•	Ac\xE9rcate a los socios todos los d\xEDas con respeto, cada vez te ser\xE1 m\xE1s f\xE1cil atenderlos",
			"•	Si en un momento dado no pudiste dar una respuesta o soluci\xF3n comprom\xE9tete a dar seguimiento \xF3 canalizar con la persona indicada.",
			"•	No olvides que el socio espera un servicio de buena calidad, siempre inv\xEDtalo a volver.",
			"",
			"",
			"#B#Anexo.",
			"Si el socio comenta que si desea un programa de entrenamiento procedemos as\xED:",
			"",
			"-	Se realiza un cuestionario breve con el fin de conocer informaci\xF3n relevante, puede realizarse mientras el socio comienza su calentamiento \xF3 que lo llene con su pu\xF1o y letra.",
			"",
			"",
			"Fecha",
			"",
			"Nombre completo",
			"",
			"Edad,  peso, talla.",
			"",
			"Objetivo",
			"",
			"Habituado al ejercicio s\xED o no",
			"",
			"Nivel de intensidad de la actividad f\xEDsica al momento: Alto, medio, bajo.",
			"",
			"Existe padecimiento cardiovascular,  diabetes, c\xE1ncer, lesiones f\xEDsicas, operaciones",
			"",
			"Toma alg\xFAn medicamento si o no",
			"",
			"Al\xE9rgico a alguna sustancia",
			"",
			"Fuma",
			"",
			"Horas de sue\xF1o al d\xEDa",
			"",
			"",
			"-	Se selecciona la rutina m\xE1s adecuada para el socio de acuerdo con la informaci\xF3n obtenida teniendo en cuenta siempre cierto margen de seguridad (ante la duda asignar la rutina menos intensa)",
			"-	Se llena y se da seguimiento durante la estancia del socio.",
		);
		foreach ($textos as $t)
		{
			if(FALSE!==strpos($t, "#B#"))
			{
				$t = str_replace("#B#", "", $t);
				$pdf->SetFont("Arial", "B", 11);
			}
			else
			{
				$pdf->SetFont("Arial", "", 10);
			}
			$pdf->MultiCell(186,6.5,$t);
		}
		$pdf->Output("Life & Fitness - Protocolo de Servicio.pdf", "D");
		exit;
	}

	/**
	 * Area Deportiva: Documento Reglamento de Alberca
	 */
	function pdf_alberca()
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$pdf->AddPage("P", "Letter");
		$pdf->Cell(186,6,"",0,1);
		$textos = array(
			"#B#                                         REGLAMENTO PARA USO DE AREA DE ALBERCA",
			"",
			"",
			"El presente reglamento tiene por objetivo mantener el orden, la seguridad e higiene dentro del \xE1rea y procurarle un servicio de calidad haciendo uso correcto de las instalaciones.",
			"",
			"     1.  Es obligatorio el uso de gorra, en caso de usar cabello largo deber\xE1 recogerlo y contenerlo dentro de esta.",
			"     2.  Es obligatorio el uso de sandalias.",
			"     3.  Es obligatorio ducharse durante al menos 20 segundos, antes de entrar a la alberca.",
			"     4.  Es obligatorio el uso de traje de ba\xF1o. Para mujeres este deber\xE1 ser de una pieza.",
			"     5.  Est\xE1 prohibida la ingesti\xF3n de alimentos en el \xE1rea.",
			"     6.  Est\xE1 prohibida la ingesti\xF3n de bebidas embriagantes.",
			"     7.  Esta permitido tomar l\xEDquidos siempre que est\xE9n contenidos en un envase con tapa quedando prohibidos los\n          de vidrio.",
			"     8.  Est\xE1 prohibido el acceso al \xE1rea bajo el influjo de drogas.",
			"     9.  Est\xE1 prohibido introducirse al agua si tiene heridas abiertas.",
			"    10.  Est\xE1 prohibido correr en las inmediaciones de la alberca.",
			"    11.  Se recomienda esperar al menos 2 horas despu\xE9s de haber ingerido alimentos  antes de entrar a la alberca.",
			"    12.  Nadar siempre en circuito para evitar accidentes.",
			"    13.  El horario de uso de alberca es de 6:00 a 22:30 hrs.",
			"",
		);
		foreach ($textos as $t)
		{
			if(FALSE!==strpos($t, "#B#"))
			{
				$t = str_replace("#B#", "", $t);
				$pdf->SetFont("Arial", "B", 11);
			}
			else
			{
				$pdf->SetFont("Arial", "", 10);
			}
			$pdf->MultiCell(186,7,$t);
		}
		$pdf->Output("Life & Fitness - Reglamento de Alberca.pdf", "D");
		exit;
	}

	/**
	 * Area Deportiva: Documento Reglamento de Acondicionamiento Fisico
	 */
	function pdf_acondicionamiento()
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$pdf->AddPage("P", "Letter");
		$pdf->Cell(186,6,"",0,1);
		$textos = array(
			"#B#                        REGLAMENTO PARA USO DE AREA DE ACONDICIONAMIENTO FISICO",
			"",
			"",
			"El presente reglamento tiene por objetivo mantener el orden, la seguridad e higiene dentro del \xE1rea y procurarle un servicio de calidad haciendo uso correcto de las instalaciones.",
			"",
			"     1.  Es obligatorio por seguridad el uso de ropa y calzado adecuados para la pr\xE1ctica de ejercicio. (No ropa de\n          vestir, no mezclilla, no calzado de vestir, no sandalias)",
			"     2.  Es obligatorio por higiene el uso de toalla.",
			"     3.  Se sugiere por seguridad no usar ropa demasiado holgada pues podr\xEDa atorarse en el equipo (lo mismo para\n          collares y pulseras)",
			"     4.  No est\xE1 permitido el uso de instalaciones deportivas a personas con heridas aun abiertas o supurantes.",
			"     5.  Por higiene el consumo de alimentos en el \xE1rea deportiva  est\xE1 restringido, solo se permite el consumo de\n          l\xEDquidos, gel, barras o fruta.",
			"     6.  No est\xE1 permitido permanecer en el \xE1rea deportiva a personas en estado inconveniente (ebriedad, bajo efecto\n          de otras drogas).",
			"     7.  No est\xE1 permitido el consumo de cualquier tipo de droga dentro de las instalaciones del club.",
			"     8.  No est\xE1 permitido permanecer en el \xE1rea deportiva a personas menores de  16  a\xF1os.",
			"     9.  Para menores de edad entre 16 y 17 a\xF1os solo podr\xE1n permanecer en el \xE1rea deportiva siempre que un socio\n          adulto los acompa\xF1e y se responsabilice.",
			"    10.  Por respeto a todos modere su lenguaje.",
			"    11.  Cuide sus pertenencias no nos hacemos responsables.",
			"    12.  Siempre que sea necesario los socios deber\xE1n alternar el uso de los equipos para todos puedan usarlos.",
			"    13.  Para un correcto uso del equipo consulte a nuestros entrenadores, est\xE1n capacitados y dispuestos.",
		);
		foreach ($textos as $t)
		{
			if(FALSE!==strpos($t, "#B#"))
			{
				$t = str_replace("#B#", "", $t);
				$pdf->SetFont("Arial", "B", 11);
			}
			else
			{
				$pdf->SetFont("Arial", "", 10);
			}
			$pdf->MultiCell(186,8,$t);
		}
		$pdf->Output("Life & Fitness - Reglamento Acondicionamiento Fisico.pdf", "D");
		exit;
	}

	/**
	 * Area Deportiva: Documento Reglamento Personal de Staff
	 */
	function pdf_staff()
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$pdf->AddPage("P", "Letter");
		$pdf->Cell(186,6,"",0,1);
		$textos = array(
			"#B#REGLAMENTO PARA PERSONAL DEL STAFF AREA DEPORTIVA.",
			"",
			"El presente reglamento tiene por objetivo mantener  la sana convivencia entre el personal, as\xED como delimitar los criterios de apariencia, servicio al socio, est\xE1ndares de calidad y en general normas de comportamiento dentro de las instalaciones.",
			"",
			"#B#Apariencia.",
			"       1.  El entrenador deber\xE1 presentarse en su lugar de trabajo con uniforme completo y limpio (playera, gafete,\n            pantal\xF3n)",
			"       2.  El uniforme puede combinarse con otras prendas siempre que se respeten los colores ______________,\n            ______________, ______________, y siempre que no quede cubierto.",
			"       3.  En caso de que el entrenador tenga tatuajes en zonas visibles, deber\xE1 cubrirlos con otra prenda de los\n            colores antes mencionados.",
			"       4.  En mujeres el cabello deber\xE1 usarse recogido, podr\xE1 usar prendedores discretos. En hombres el cabello\n            deber\xE1 usarse corto sin cortes extravagantes.",
			"       5.  Los hombres deber\xE1n presentarse afeitados o con la barba delineada. ",
			"       6.  Las mujeres podr\xE1n usar aretes discretos, si tuviera cualquier otra perforaci\xF3n con piercing deber\xE1 quitarlo\n            durante su turno de trabajo. Los hombres no podr\xE1n usar ning\xFAn tipo de arete o piercing durante su horario\n            de trabajo.",
			"       7.  Debido a las caracter\xEDsticas del trabajo por seguridad no se permite usar anillos ni pulseras.",
			"       8.  Es responsabilidad del entrenador mantenerse en buena forma f\xEDsica.",
			"",
			"#B#Responsabilidades",
			"       1.  El  entrenador  deber\xE1  prestar los  servicios  para los  que  fue  contratado, a  todos  los  socios por igual  sin\n            distintivos por apariencia, g\xE9nero, edad, creencia ò capacidad. De existir  alg\xFAn caso peculiar deber\xE1 dirigirse\n            con su superior inmediato.",
			"       2.  Deber\xE1  presentarse 5 minutos antes en el \xE1rea de  trabajo al comienzo de su jornada y permanecer  siempre\n            visible. Al retirarse deber\xE1 avisar a su inmediato  superior y a el, (los), compa\xF1ero(s) que  se queden  a  cargo\n            del \xE1rea.",
			"       3.  Los horarios de comida se otorgar\xE1n en momentos que la operaci\xF3n sea afectada lo menos posible  y podr\xE1n\n            ser modificados en funci\xF3n del mismo.",
			"       4.  Los horarios de trabajo son:",
			"",
			"              Acondicionamiento F\xEDsico:",
			"              Matutino  6 a 13 hrs descanso 45 minutos lunes a viernes",
			"              Vespertino 16 a 23 hrs descanso 45 minutos lunes a viernes",
			"              S\xE1bados 8 a 14 hrs matutino descanso 30 minutos ",
			"              S\xE1bados 12 a 18 hrs vespertino descanso 30 minutos",
			"              Domingo 9 a 16 hrs turno \xFAnico descanso 45 minutos",
			"",
			"              Alberca",
			"              Matutino  6 a 12 hrs descanso 30 minutos lunes a viernes",
			"              Intermedio 11:30 a 17:30 hrs descanso 30 minutos lunes a viernes",
			"              Vespertino 17 a 23 hrs descanso 30 minutos lunes a viernes",
			"              S\xE1bados 8 a 14 hrs matutino descanso 30 minutos ",
			"              S\xE1bados 12 a 18 hrs vespertino descanso 30 minutos",
			"              Domingo 9 a 16 hrs turno \xFAnico descanso 45 minutos",
			"",
			"              - Se tendr\xE1 una tolerancia de 10 minutos para la hora de entrada los cuales le ser\xE1n descontados de su\n                descanso.",
			"              - Posterior a 10 minutos se considerar\xE1 retardo y le ser\xE1 descontado el tiempo de su descanso.",
			"",
			"       5.  El entrenador (acond. F\xEDsico, alberca, clases en gpo.) deber\xE1 mantener su \xE1rea en orden por seguridad y\n            presentaci\xF3n.",
			"       6.  El entrenador deber\xE1 vigilar el buen cumplimiento del reglamento de socios por parte  de estos.",
			"       7.  Todo el personal del \xE1rea deportiva deber\xE1 mostrar una honesta actitud de servicio ya que esa es la labor\n            principal del \xE1rea.",
			"",
			"#B#Uso de instalaciones",
			"",
			"       1.  El staff del \xE1rea deportiva podr\xE1 hacer uso de las instalaciones dentro de los siguientes horarios",
			"           -  Lunes a viernes de 9 a 19 horas ",
			"           -  S\xE1bados y domingos despu\xE9s de las 12 horas ",
			"       2.  No est\xE1 permitido que el entrenador ingiera alimentos dentro del \xE1rea deportiva s\xF3lo se permiten l\xEDquidos",
			"       3.  Cuando el entrenador se encuentre entrenando deber\xE1 usar vestimenta adecuada. No se permite:",
			"           -  Uso de playera de tirantes o interiores",
			"           -  Shorts abiertos (tipo corredor)",
			"           -  Usar ropa alusiva a otro gimnasio",
			"           -  Mallas sin soporte (en el caso de hombres)",
			"           -  Mallas demasiado cortas ",
			"           -  Ropa con leyendas sugestivas u ofensivas",
			"",
			"#B#Restricciones",
			"",
			"       1.  Est\xE1 terminantemente prohibido comercializar o recomendar todo tipo de suplementos, drogas o sustancias a\n            nuestros socios. Esto en el entendido de evitar riegos de salud y riesgos legales.",
			"       2.  El entrenador podr\xE1 otorgar el servicio  de entrenador personal  fuera de su horario de trabajo  sin  restricci\xF3n\n            horarios ni d\xEDas.",
			"       3.  El entrenador podr\xE1 otorgar el servicio de entrenador personal dentro de su horario de trabajo siempre que:",
			"           -  Sean un m\xE1ximo de dos horas por d\xEDa",
			"           -  No interfieran con las labores de servicio general para las que fue contratado (horarios pico por ejemplo)",
			"           -  Se encuentren en el \xE1rea otros compa\xF1eros que puedan continuar otorgando el servicio general, es decir no\n              podr\xE1n dar entrenamiento personal de forma simult\xE1nea todos los entrenadores de un mismo turno.",
			"       4.  El servicio de entrenador personal puede darse hasta a 2 socios de forma simult\xE1nea s\xF3lo si estos est\xE1n de\n            acuerdo",
			"       5.  El uso de tel\xE9fono celular se permitir\xE1 solo de forma discreta:",
			"           -  En modo vibrador o silencioso",
			"           -  Uso del equipo solo si es realmente importante (avisar previamente)",
			"           -  Evitar estar a la vista de los socios",
			"           -  2 minutos por uso",
			"           -  Queda a criterio del jefe del \xE1rea la suspensi\xF3n de su uso de forma definitiva dentro del horario de trabajo\n              seg\xFAn casos particulares.",
			"       6.  Para  cambios  excepcionales  de turno, descansos o permisos  deber\xE1n  ser  solicitados  con  72  horas de\n            anticipaci\xF3n por lo menos al jefe del \xE1rea. Se llevar\xE1 un registro en bit\xE1cora.",
			"       7.  Para solicitud de vacaciones deber\xE1n solicitarse 10 d\xEDas antes por los menos.",
		);
		foreach ($textos as $t)
		{
			if(FALSE!==strpos($t, "#B#"))
			{
				$t = str_replace("#B#", "", $t);
				$pdf->SetFont("Arial", "B", 11);
			}
			else
			{
				$pdf->SetFont("Arial", "", 10);
			}
			$pdf->MultiCell(186,5,$t);
		}
		$pdf->Output("Life & Fitness - Reglamento Personal de Staff.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Ausencias
	 */
	function pdf_ausencias($info=NULL)
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$this->add_page_header($pdf, "left", time());

		$pdf->Ln(10);
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(186,5,"AUSENCIA",0,1,"C");
		$pdf->Ln(15);
		$pdf->SetFont("Arial", "B", 10);
		$pdf->Cell(186,5,"DATOS DEL SOCIO:",0,1);
		$pdf->SetFont("Arial", "", 10);
		$txt = "
			Nombre del socio titular: #TITULAR#

			Membres\xEDa No.:     #MEMBRESIA#



			Por este conducto, acuerdo que desde el d\xEDa #INICIOAUSENCIA# el socio (a) de nombre #NOMBRESOCIO# se ausentar\xE1 hasta el d\xEDa #FINAUSENCIA# por el siguiente motivo: #MOTIVO# de acuerdo al contrato se har\xE1 un cargo por la cantidad de $ #MONTO# en el mes de #MESCARGO#.



                                                  ____________________________________________
                                                             Nombre y firma de conformidad socio titular




                                                  ____________________________________________
                                                             Nombre y sello de quotemeta(str)uien recibe";
		$info["TITULAR"] = isset($info["TITULAR"])?utf8_decode($info["TITULAR"]):"___________________________________________";
		$info["MEMBRESIA"] = isset($info["MEMBRESIA"])?utf8_decode($info["MEMBRESIA"]):"_____________";
		$info["INICIOAUSENCIA"] = isset($info["INICIOAUSENCIA"])?utf8_decode($info["INICIOAUSENCIA"]):"___________________";
		$info["NOMBRESOCIO"] = isset($info["NOMBRESOCIO"])?utf8_decode($info["NOMBRESOCIO"]):"_________________________________________________________";
		$info["FINAUSENCIA"] = isset($info["FINAUSENCIA"])?utf8_decode($info["FINAUSENCIA"]):"_______________________";
		$info["MOTIVO"] = isset($info["MOTIVO"])?utf8_decode($info["MOTIVO"]):"__________________________________________________";
		$info["MONTO"] = isset($info["MONTO"])?utf8_decode($info["MONTO"]):"______________________";
		$info["MESCARGO"] = isset($info["MESCARGO"])?utf8_decode($info["MESCARGO"]):"____________________";
		$txt = str_replace(array("#TITULAR#","#MEMBRESIA#","#INICIOAUSENCIA#","#NOMBRESOCIO#","#FINAUSENCIA#","#MOTIVO#","#MONTO#","#MESCARGO#"), array($info["TITULAR"],$info["MEMBRESIA"],$info["INICIOAUSENCIA"],$info["NOMBRESOCIO"],$info["FINAUSENCIA"],$info["MOTIVO"],$info["MONTO"],$info["MESCARGO"]), $txt);
		$pdf->MultiCell(186, 6, $txt);
		$pdf->SetXY(15,-26);
		$pdf->SetTextColor(142, 142, 142);
		$aux = Ant_Accounts_Model::get_info_sucursal($info['ant_accounts_id']);
        $address = $aux[0]['fiscal_address'];
        $address_n = $aux[0]['fiscal_address_ext_num'];
        $neighborhood = $aux[0]['neighborhood'];
        $city = $aux[0]['fiscal_city'];
        $state = $aux[0]['fiscal_address_state'];
        $phone = $aux[0]['fiscal_phone'];
		$pdf->Cell(186, 5, $address." ".$address_n." Col ".$neighborhood." ".$city.", ".$state."      ".$phone,0,0,"C");
		$pdf->Output("Life & Fitness - Ausencias.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Aviso de privacidad
	 */
	function pdf_aviso($info=array())
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$this->add_page_header($pdf, "center");
		$h = 4.9;

		foreach (array("DOMICILIO"=>"(Domicilio).", "TELEFONOS"=>"(N\xFAmeros).", "WEBSITE"=>"(P\xE1gina de Internet)","ACTUALIZACION"=>"Enero 2014.") as $field=>$default)
		{
			$info[$field] = isset($info[$field])?utf8_decode($info[$field]):$default;
		}
		$aux = Ant_Accounts_Model::get_info_sucursal($info['ant_accounts_id']);
		$name = $aux[0]["nombre_apoderado"];
        $gender = $aux[0]['sexo_apoderado'];
        $tipo = utf8_decode($aux[0]['tipo_apoderado']);
        $address = $aux[0]['fiscal_address'];
        $address_n = $aux[0]['fiscal_address_ext_num'];
        $neighborhood = $aux[0]['neighborhood'];
        $city = $aux[0]['fiscal_city'];
        $state = $aux[0]['fiscal_address_state'];
        $phone = $aux[0]['fiscal_phone'];
        $cp = $aux[0]['fiscal_address_zip'];
        $country = $aux[0]['fiscal_country'];

		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(186,$h+0.5,"AVISO DE PRIVACIDAD",0,1,"C");

		$pdf->SetFont("Arial", "", 8);
		$pdf->Cell(33,$h,"La sociedad denominada ",0,0,"J");

		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(102,$h,'"LIFE & FITNESS MEXICO", SOCIEDAD ANONIMA DE CAPITAL VARIABLE,',0,0,"J");

		$pdf->SetFont("Arial", "", 8);
		$pdf->Cell(51,$h," est\xE1 comprometida con la protecci\xF3n de",0,1,"J");

		$txt = "sus datos personales, al ser responsable de su uso, manejo y confidencialidad, y al respecto le informa lo siguiente:
			Esta Sociedad, recaba y usa datos personales para el cumplimiento de las siguientes finalidades:
        	-  Confirmar su identidad.
        	-  Entender y atender sus necesidades.
        	-  Mantener el orden, la seguridad e higiene dentro del \xE1rea y procurarle un servicio de calidad, haciendo uso correcto de las instalaciones.
        	-  Cumplir con los requerimientos legales, reglamentarios u otros que le son aplicables.
       		-  Verificar la informaci\xF3n que nos proporciona.
			De manera adicional, utilizamos su informaci\xF3n personal para realizar encuestas de evaluaci\xF3n del personal de esta sociedad, as\xED como para ofrecerle nuevos servicios o promociones.  Si bien, estas finalidades no son necesarias para prestarle los servicios que solicita o contrata con nosotros, las mismas nos permiten brindarle un mejor servicio y elevar su calidad.  En caso de que no desee que sus datos personales sean tratados para estos fines, desde este momento, Usted puede manifestar su negativa al personalidad de la sociedad de m\xE9rito, como a continuaci\xF3n se indica.
			En  caso  de que NO desee que sus datos  personales se  utilicen para los fines  descritos en el p\xE1rrafo anterior, marque el siguiente recuadro con";
		$pdf->MultiCell(186, $h, $txt);
		$pdf->Cell(36,$h,"una cruz y asiente su firma:",0,0,"J");
		$pdf->Cell(10,$h,"",1);
		$pdf->Cell(3,$h,"");
		$pdf->Cell(70,$h,"",1);
		$pdf->Cell(67,$h,"",0,1);
		$txt = "No consiento que mis datos personales se utilicen para los siguientes fines:
        		-  La realizaci\xF3n de encuestas de evaluaci\xF3n de los servicios prestados.
        		-  El ofrecimiento de nuevos servicios o promociones.
			La negativa para el uso de sus datos personales para estas finalidades no podr\xE1 ser un motivo para que le neguemos los servicios que solicita a esta sociedad.  En caso de que no manifieste su negativa, se entender\xE1 que autoriza el uso de su informaci\xF3n personal para dichos fines.
			Para prestarle los servicios que solicita requerimos por lo menos la siguiente informaci\xF3n y/o documentaci\xF3n:
        		-  Identificaci\xF3n Oficial con fotograf\xEDa y firma.
        		-  Nombre completo (incluyendo cualquier variante del mismo).
        		-  Fecha y lugar de nacimiento.
        		-  Ocupaci\xF3n actual.
        		-  Estado Civil.
        		-  Domicilio actual.
        		-  Tel\xE9fono.
        		-  Correo electr\xF3nico.
        		-  En caso de ser Extranjero, el Documento Migratorio que acredite su legal estancia en el pa\xEDs.
        		-  En caso de ser Representante Legal y/o Apoderado de una persona f\xEDsica o jur\xEDdica, requerimos que presente el documento original del cual se desprenda el car\xE1cter con que comparece.
			Dependiendo del servicio que solicite realicemos en esta sociedad, se le podr\xE1 solicitar mayor informaci\xF3n y documentaci\xF3n, la cual ser\xE1 tratada de manera responsable y confidencial.";
		$pdf->MultiCell(186, $h, $txt);
		$pdf->Cell(74, $h, "Los datos personales que recaba sociedad denominada");
		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(103, $h, '"LIFE & FITNESS MEXICO", SOCIEDAD ANONIMA DE CAPITAL VARIABLE,');
		$pdf->SetFont("Arial", "", 8);
		$pdf->Cell(9, $h," son", 0, 1);

		$txt = "obtenidos de los documentos requisitados por Usted y de los documentos privados u oficiales que nos proporciona directamente, as\xED como de los documentos que expiden los diversos Registros y Dependencias P\xFAblicas Federales, Estatales y Municipales.
			De forma eventual, sus datos personales se comparten para el cumplimiento de requerimientos legales o la atenci\xF3n de una orden fundada y motivada de las autoridades competentes en ejercicio de sus funciones de notificaci\xF3n, vigilancia y fiscalizaci\xF3n.  En cualquier caso, comunicaremos el presente aviso de privacidad a los destinatarios de sus datos personales, a fin de que respeten sus t\xE9rminos.
			Usted podr\xE1 acceder, rectificar, cancelar u oponerse (en lo sucesivo derechos ARCO) al tratamiento de sus datos personales que tenemos en nuestros registros y archivos, o bien, revocar su consentimiento para el uso de los mismos, presentando solicitud por escrito en nuestro domicilio dirigido a la sociedad de m\xE9rito.";
		$pdf->MultiCell(186, $h, $txt);

		$pdf->AddPage("P", "Letter");
		$pdf->Ln();
		$pdf->Cell(186, $h, "Al  respecto,  queremos  manifestarle  que  el  ejercicio  de  los  derechos  ARCO  o  la  revocaci\xF3n  del  consentimiento,  es  independiente  de  las", 0, 1);
		$pdf->Cell(148, $h, "modificaciones a los datos personales asentados en los contratos que se expiden por parte de sociedad denominada");
		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(38, $h, '"LIFE & FITNESS MEXICO",', 0, 1);
		$pdf->Cell(66, $h, "SOCIEDAD AN\xD3NIMA DE CAPITAL VARIABLE.");
		$pdf->SetFont("Arial", "", 8);
		$pdf->Cell(120, $h, "Por lo cual, cualquier acci\xF3n o petici\xF3n que nos solicite  respecto a los mismos se regir\xE1 por la", 0, 1);
		$pdf->Cell(186, $h, "legislaci\xF3n aplicable.",0,1);
		$txt = "Es importante informarle que su solicitud deber\xE1 de contener, al menos, la siguiente informaci\xF3n: (i) su nombre o el de su representante, domicilio u otro medio para comunicarle la respuesta; (ii) los documentos que acrediten s identidad, o en su caso, el de su representante; (iii) la descripci\xF3n clara y precisa de los datos personales respecto de los cuales busca ejercer su derecho; y (iv) cualquier otro elemento o documento que facilite la localizaci\xF3n de los datos personales.  En tal virtud, deber\xE1 de acreditar su identidad mediante identificaci\xF3n oficial vigente o en caso de presentar su solicitud a trav\xE9s de representante, a trav\xE9s de instrumento p\xFAblico \xF3 poder notarial.
			Nos comprometemos a darle respuesta en un plazo m\xE1ximo de 20 d\xEDas h\xE1biles contados a partir del d\xEDa en que recibimos su solicitud, misma que pondremos a su disposici\xF3n en nuestro domicilio, previa acreditaci\xF3n de su identidad.  Si solicita acceso a sus datos personales, la reproducci\xF3n de \xE9stos se llevar\xE1 a cabo a trav\xE9s de copias simples, archivo electr\xF3nico, o bien, podr\xE1 consultarlos directamente en sitio. En caso de solicitar su derecho de rectificaci\xF3n, su solicitud deber\xE1 ir acompa\xF1ada de la documentaci\xF3n que ampare la procedencia de lo solicitado.
			Si desea dejar de recibir publicidad o promociones de nuestros servicios podr\xE1:";
		$pdf->MultiCell(186, $h, $txt);
		$pdf->Cell(132, $h, "        -  Presentar  su  solicitud  personalmente  en  nuestro  domicilio  dirigida  a  sociedad  denominada ");
		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(54, $h, '"LIFE & FITNESS MEXICO", SOCIEDAD.',0,1);
		$pdf->Cell(186, $h, "           AN\xD3NIMA DE CAPITAL VARIABLE.",0,1);
		$pdf->SetFont("Arial", "", 8);
		$txt = "        -  Enviar un correo electr\xF3nico a la siguiente direcci\xF3n electr\xF3nica: lifeandfitness.gdl@outlook.com.
        		-  Llamar a los n\xFAmeros telef\xF3nicos: ".$phone."
        	El presente aviso de privacidad puede sufrir modificaciones o actualizaciones, por lo cual, nos comprometemos a mantenerlo informado de tal situaci\xF3n a trav\xE9s de los siguientes medios:
        		-  Nuestra p\xE1gina de internet
        		-  Notificaci\xF3n personal a su correo electr\xF3nico.
        		-  En la primera comunicaci\xF3n que tengamos con usted despu\xE9s del cambio.
			Si Usted tiene alguna duda sobre el presente aviso de privacidad o nuestra pol\xEDtica de privacidad, puede dirigirla a:
        	-  La direcci\xF3n electr\xF3nica: lifeandfitness.gdl@outlook.com.";
		$pdf->MultiCell(186, $h, $txt);
		$pdf->Cell(95,$h, "        -  La  direcci\xF3n de correo postal  dirigida  a  la  sociedad  denominada");
		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(91,$h, '"LIFE  &  FITNESS  MEXICO",  SOCIEDAD  ANONIMA  DE  CAPITAL', 0, 1);
		$pdf->Cell(25,$h, "           VARIABLE");
		$pdf->SetFont("Arial", "", 8);

		$pdf->Cell(161,$h, "al siguiente domicilio: ".$address." ".$address_n." Col ".$neighborhood." CP: ".$cp." ".$city.", ".$state." ".$country.".", 0, 1);
		$pdf->SetFont("Arial", "", 8);
		$txt = "        -  A los tel\xE9fonos: ".$phone.".
			Asimismo,  ponemos  a  su  entera  disposici\xF3n  copias  del  presente  aviso  de  privacidad  en  nuestro  domicilio  y en nuestra p\xE1gina de internet.
			Si Usted considera que su derecho a la protecci\xF3n de datos personales ha sido vulnerado por alguna conducta de nuestros empleados o de nuestras actuaciones o respuestas, puede acudir ante el Instituto Federal de Acceso a la Informaci\xF3n y Protecci\xF3n de Datos (IFAI). Para mayor informaci\xF3n visite www.ifai.mx.";
		$pdf->MultiCell(186, $h, $txt);
		$pdf->Ln();
		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(186, $h, '"LIFE & FITNESS MEXICO", SOCIEDAD ANONIMA DE CAPITAL VARIABLE.', 0, 1, "C");
		$pdf->SetFont("Arial", "", 8);
		if ($tipo !=null || $tipo != ""){
			$pdf->Cell(76, $h, $tipo, 0, 0, "R");
		}else{
			if($gender=="H"){
				$pdf->Cell(76, "SE\xD1OR", $tipo, 0, 0, "R");
			}else if ($gender=="M"){
				$pdf->Cell(76, $h, "SE\xD1ORA", 0, 0, "R");
			}else{
				$pdf->Cell(76, $h, "SE\xD1OR/A", 0, 0, "R");
			}
		}
		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(110, $h, $name, 0, 1);
		$pdf->SetFont("Arial", "", 8);
		$pdf->Cell(186, $h, $info["DOMICILIO"], 0, 1, "C");
		$pdf->Cell(186, $h, "Tels. ".$info["TELEFONOS"], 0, 1, "C");
		$pdf->Cell(186, $h, $info["WEBSITE"], 0, 1, "C");
		$pdf->Cell(186, $h, "Ultima actualizaci\xF3n ".$info["ACTUALIZACION"], 0, 1, "C");
		$pdf->Ln($h*2);
		$txt = "Firmo de conformidad, d\xE1ndome por enterado y explicado del contenido del presente Aviso de Privacidad.



			Nombre y firma del Titular.";
		$pdf->SetX(78);
		$pdf->MultiCell(62, $h-1, $txt, 1, "C");
		$pdf->Output("Life & Fitness - Aviso de Privacidad.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Cancelacion Cargo Automatico
	 */
	function pdf_cancelacion($info=NULL)
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$this->add_page_header($pdf, "left", (isset($info["FECHA"])?$info["FECHA"]:time()));
		foreach (array("NOMBRE","MEMBRESIA","TARJETA","MONTO","INICIO") as $field)
		{
			$info[$field] = isset($info[$field])?utf8_decode($info[$field]):"";
		}
		$pdf->SetLineWidth(0.4);
		$pdf->SetDrawColor(0,0,0);
		$pdf->Ln(8);
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(186,5,"CARTA DE CANCELACI\xD3N DE SERVICIO",0,1,"C");

		$pdf->SetFont("Arial", "BU", 11);
		$pdf->Cell(186,5,"CARGO AUTOM\xC1TICO A TARJETA",0,1,"C");
		$pdf->Ln(12);

		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(14,5,"");
		$pdf->Cell(172,5,"DATOS DEL SOCIO:",0,1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "", 11);
		$pdf->Cell(14,5,"");
		$pdf->Cell(35,5,"Nombre:");
		$pdf->Cell(120,5,$info["NOMBRE"],"B");
		$pdf->Cell(17,5,"",0,1);
		$pdf->Ln(10);

		$pdf->Cell(14,5,"");
		$pdf->Cell(35,5,"Membres\xEDa No.:");
		$pdf->Cell(120,5,$info["MEMBRESIA"],"B");
		$pdf->Cell(17,5,"",0,1);
		$pdf->Ln(10);

		$pdf->Cell(14,5,"");
		$pdf->Cell(35,5,"N\xFAmero de tarjeta:");
		$pdf->Cell(120,5,$info["TARJETA"],"B");
		$pdf->Cell(17,5,"",0,1);
		$pdf->Ln(10);

		$pdf->Cell(14,5,"");
		$pdf->Cell(35,5,"Monto:");
		$pdf->Cell(120,5,$info["MONTO"],"B");
		$pdf->Cell(17,5,"",0,1);
		$pdf->Ln(10);

		$pdf->Cell(14,5,"");
		$pdf->Cell(35,5,"Fecha de inicio:");
		$pdf->Cell(120,5,$info["INICIO"],"B");
		$pdf->Cell(17,5,"",0,1);
		$pdf->Ln(10);

		$pdf->Cell(14,5,"");
		$pdf->MultiCell(155,5,"Por este conducto, solicito a Life & Fitness Mexico S.A de C.V suspenda definitivamente los cargos autom\xE1ticos que se realizaban a la mencionada tarjeta por concepto del cargo del mantenimiento mensual de dicha membres\xEDa.");
		$pdf->Ln(22);

		$pdf->Cell(186,5,"____________________________________________",0,1,"C");
		$pdf->Cell(186,5,"Nombre y firma del tarjetahabiente",0,1,"C");
		$pdf->Ln(25);

		$pdf->Cell(186,5,"____________________________________________",0,1,"C");
		$pdf->Cell(186,5,"Nombre y sello de quien recibe",0,1,"C");

		$pdf->SetLineWidth(0.1);
		$pdf->Output("Life & Fitness - Cancelacion Cargo Automatico.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Cargo Automatico
	 */
	function pdf_cargo($info=NULL)
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);

		$pdf->AddPage("P", "Letter");
		$pdf->SetLineWidth(0.2);
		$path = realpath(dirname(__FILE__)."/../../assets/images");
		$img1 = $path.DIRECTORY_SEPARATOR."esprezza.png";
		$x = 130;
		$y = 10;
		$xf = 15;
		$yf = 25;
		$align = "L";
		$pdf->Image($img1,$x,$y,66);

		foreach (array("FECHA"=>"","AFILIACION"=>"7523195","NOMBRE_COMERCIAL"=>"Life & Fitness","TARJETA"=>"","RAZON_SOCIAL"=>"Life & Fitness Mexico S.A. de C.V.","NOMBRE"=>"","CONTRATO"=>"","CONCEPTO"=>"","MONTO"=>"","PERIORICIDAD"=>"") as $field=>$default)
		{
			$info[$field] = isset($info[$field])?utf8_decode($info[$field]):$default;
		}

		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont("Arial","B",14);
		$pdf->Cell(186,5,"Carta Autorizaci\xF3n",0,1);
		$pdf->Cell(186,5,"v\xEDa Cargos Recurrentes",0,1);
		$pdf->Ln();
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(186,5,"LIFE & FITNESS MEXICO S.A. DE C.V.",0,1);
		$pdf->Cell(186,5,$address." ".$address_n." Col".$neighborhood." CP: ".$cp,0,1);
		$pdf->Ln(8);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->SetLineWidth(0.8);
		$pdf->SetDrawColor(215,227,255);
		$pdf->Line($x, $y, $x+186, $y);

		$pdf->SetLineWidth(0.1);
		$pdf->SetDrawColor(0,0,0);
		$pdf->Ln();
		$pdf->SetFont("Arial","B",7);
		$pdf->Cell(35,5,"Lugar y Fecha: ".$city." ".$state);
		$pdf->Cell(58,5,$info["FECHA"],"B");

		$pdf->SetFont("Arial","B",9);
		$pdf->Cell(93,5,"DATOS DEL NEGOCIO AFILIADO",0,1);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->Text($x, $y+6, "DATOS DEL TARJETAHABIENTE");
		$pdf->SetFont("Arial","",10);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->SetFont("Arial","B",8);
		$pdf->Cell(93,5,"");
		$pdf->Cell(30,5,"N\xFAmero de Afiliaci\xF3n: ");
		$pdf->Cell(63,5,$info["AFILIACION"],0,1);//"7523195"

		$pdf->Cell(93,5,"");
		$pdf->Cell(27,5,"Nombre Comercial: ");
		$pdf->Cell(66,5,$info["NOMBRE_COMERCIAL"],0,1); //"Life & Fitness"

		$pdf->SetFont("Arial","B",8);
		$pdf->Cell(28,5,"N\xFAmero de Tarjeta:");
		$pdf->Cell(65,5,$info["TARJETA"],"B");
		$pdf->Cell(20,5,"Raz\xF3n Social: ");
		$pdf->Cell(73,5,$info["RAZON_SOCIAL"],0,1); // "Life & Fitness Mexico S.A. de C.V."

		$pdf->Cell(28,5,"Nombre completo:");
		$pdf->Cell(158,5,$info["NOMBRE"],"B",1);
		$pdf->Ln();

		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->SetLineWidth(0.8);
		$pdf->SetDrawColor(215,227,255);
		$pdf->Line($x, $y, $x+186, $y);

		$pdf->Ln();
		$pdf->SetLineWidth(0.1);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetFont("Arial","",8);
		$txt = "Por la presente autorizo a Banco Mercantil del Norte, S.A. Instituci\xF3n de Banca M\xFAltiple, Grupo Financiero Banorte, para que, con base en el/los contratos de adhesi\xF3n para la apertura de cr\xE9dito en cuenta corriente o para dep\xF3sitos en cuenta corriente seg\xFAn corresponda, que tengo celebrado y del cual se me otorg\xF3 la Tarjeta arriba descrita, se sirvan pagar por mi cuenta a nombre de Life & Fitness Mexico S.A. de C.V. los cargos por los conceptos, montos y periodicidad a continuaci\xF3n detallados, oblig\xE1ndome a informar en tiempo y forma en su caso el cambio de n\xFAmero de Tarjeta asignado por el Banco Emisor, que por reposici\xF3n, robo o extrav\xEDo, as\xED como la cancelaci\xF3n de la misma por cualquier otro motivo.

			El Negocio Afiliado se\xF1alado en el encabezado, se obliga y es responsable de cumplir con:
    		(i)  La informaci\xF3n generada correcta y oportuna de los cargos al Tarjetahabiente.
    		(ii) De la calidad y entrega  de los productos y servicios  ofrecidos, liberando  a  Banco  Mercantil del Norte, Instituci\xF3n de Banda M\xFAltiple, Grupo\n         Financiero   Banorte,   o  a  cualquier   instituci\xF3n   afiliada  a  VISA  o  a  MasterCard  de  toda  reclamaci\xF3n   que  se  genere  por  parte  del\n         Tarjetahabiente.

			El Tarjetahabiente podr\xE1 revocar esta Carta Autorizaci\xF3n mediante comunicado por escrito con al menos quince d\xEDas naturales de anticipaci\xF3n que deber\xE1 recibir el Negocio Afiliado, el cual anotar\xE1 la fecha de su recepci\xF3n con la firma y nombre de quien recibe por parte del Negocio Afiliado. En este caso el Negocio Afiliado deber\xE1 informar al Tarjetahabiente la fecha en que dejar\xE1 de surtir efecto la presente carta de autorizaci\xF3n.";

		$pdf->MultiCell(186, 3.8, $txt);
		$pdf->Ln(10);

		$pdf->SetFont("Arial","B",8);
		$pdf->Cell(186,5,"NO. CONTRATO",0,1,"C");
		$pdf->Cell(68,5,"");
		$pdf->Cell(50,5,$info["CONTRATO"],"B");
		$pdf->Cell(68,5,"",0,1);
		$pdf->Ln(10);

		$pdf->Cell(60,5,"CONCEPTO");
		$pdf->Cell(3,5,"");
		$pdf->Cell(60,5,"MONTO (M.N.)",0,0,"C");
		$pdf->Cell(3,5,"");
		$pdf->Cell(60,5,"PERIORICIDAD",0,1,"C");

		$pdf->Cell(60,4,$info["CONCEPTO"],"B");
		$pdf->Cell(3,4,"");
		$pdf->Cell(60,4,$info["MONTO"],"B");
		$pdf->Cell(3,4,"");
		$pdf->Cell(60,4,$info["PERIORICIDAD"],"B",1);
		$pdf->Ln(4);

		$pdf->SetFont("Arial","",8);
		$pdf->Cell(186,6,"La periodicidad y el monto pueden ser variables o fijos y deber\xE1 especificarse claramente para el Tarjetahabiente.",0,1);

		$pdf->SetFont("Arial","B",8);
		$pdf->Cell(186,6,"Invariablemente deber\xE1 de efectuarse la validaci\xF3n visual de las firmas de la tarjeta bancaria contra la identificaci\xF3n oficial.",0,1);
		$pdf->Ln(5);

		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->SetLineWidth(0.8);
		$pdf->SetDrawColor(215,227,255);
		$pdf->Line($x, $y, $x+186, $y);

		$pdf->Ln();
		$pdf->SetLineWidth(0.1);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetFont("Arial","B",9);

		$pdf->Ln(34);
		$pdf->Cell(10,5,"");
		$pdf->Cell(70,5,"Acepto el Servicio Cargo Recurrente","T",0,"C");
		$pdf->Cell(36,5,"");
		$pdf->Cell(70,5,"Firma del responsable y sello del Negocio","T",1,"C");

		$pdf->Cell(10,5,"");
		$pdf->Cell(70,5,"Nombre y firma del Tarjetahabiente",0,0,"C");
		$pdf->Cell(106,5,"",0,1);

		$pdf->Cell(10,5,"");
		$pdf->Cell(70,5,"(Obligatorio)",0,0,"C");
		$pdf->Cell(106,5,"",0,1);

		$pdf->Output("Life & Fitness - Cargo Automatico.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Carta Baja Conformidad Baja de Membresia
	 */
	function pdf_baja($info=NULL)
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$this->add_page_header($pdf, "left", time());
		foreach (array("FECHA"=>"___________________________","NOMBRE"=>"_________________________________________________________","MEMBRESIA_TITULAR"=>"", "MEMBRESIA_SOCIO"=>"________________","MOTIVO"=>"____________________________________________________________________________", "TITULAR"=>"") as $field=>$default)
		{
			$info[$field] = isset($info[$field])?utf8_decode($info[$field]):$default;
		}
		$aux = Ant_Accounts_Model::get_info_sucursal($info['ant_accounts_id']);

        $address = $aux[0]['fiscal_address'];
        $address_n = $aux[0]['fiscal_address_ext_num'];
        $neighborhood = $aux[0]['neighborhood'];
        $city = $aux[0]['fiscal_city'];
        $state = $aux[0]['fiscal_address_state'];
        $phone = $aux[0]['fiscal_phone'];

		$pdf->SetFont("Arial","B",11);
		$pdf->Cell(12,4,"");
		$pdf->Cell(162,4,"CARTA CONFORMIDAD BAJA DE MEMBRES\xCDA",0,1,"C");
		$pdf->Ln();

		$pdf->Cell(12,4,"");
		$pdf->Cell(162,4,"DATOS DEL SOCIO:",0,1);
		$pdf->Ln();

		$pdf->SetFont("Arial","",9);
		$pdf->Cell(12,4,"");
		$pdf->Cell(36,4,"Nombre del socio titular:");
		$pdf->Cell(124,4,$info["TITULAR"],"B",1);
		$pdf->Ln();

		$pdf->Cell(12,4,"");
		$pdf->Cell(25,5,"Membres\xEDa No.:");
		$pdf->Cell(50,5,$info["MEMBRESIA_TITULAR"],"B");
		$pdf->Cell(85,5,$info["MEMBRESIA_TITULAR"],0,1);
		$pdf->Ln();

		$pdf->Cell(12,4,"");
		$txt = "Por este conducto, acuerdo que desde el d\xEDa #FECHA# el socio (a) de nombre #NOMBRE# queda dado de baja de la membres\xEDa No. #MEMBRESIA# por el siguiente motivo:
			#MOTIVO#
			de tal forma que entregar\xE1 su credencial y no tendr\xE1 acceso al club ni a sus servicios desde la fecha se\xF1alada.

			En  dado  caso  de  querer  regresar  a  ser socio de Life & Fitness a su membres\xEDa original existen tres opciones:";
		$txt = str_replace(array("#FECHA#","#NOMBRE#","#MEMBRESIA#","#MOTIVO#"), array($info["FECHA"],$info["NOMBRE"],$info["MEMBRESIA_SOCIO"],$info["MOTIVO"]), $txt);
		$pdf->MultiCell(162, 5, $txt);

		$pdf->Cell(12,4,"");
		$pdf->Cell(162,4,"    1.- Realizar  el  pago  de  los  meses  de  adeudo y el mes correspondiente a su mantenimiento al momento de");
		$pdf->Cell(12,4,"",0,1);
		$pdf->Cell(12,4,"");
		$pdf->Cell(162,4,"         hacer el tr\xE1mite.");
		$pdf->Cell(12,4,"",0,1);

		$pdf->Cell(12,4,"");
		$pdf->Cell(162,4,"    2.- En  caso  de  tener  tres  o  m\xE1s  meses  sin  pago registrado, el interesado  puede realizar  una reactivaci\xF3n");
		$pdf->Cell(12,4,"",0,1);
		$pdf->Cell(12,4,"");
		$pdf->Cell(162,4,"         pagando  el  33%  del  costo  de  la  membres\xEDa  al  momento  del  tr\xE1mite  y  el  mes  correspondiente de su");
		$pdf->Cell(12,4,"",0,1);
		$pdf->Cell(12,4,"");
		$pdf->Cell(162,4,"         mantenimiento.");
		$pdf->Cell(12,4,"",0,1);

		$pdf->Cell(12,4,"");
		$pdf->Cell(162,4,"    3.- Adquirir  una  nueva  membres\xEDa  ajust\xE1ndose  a los requerimientos  al  momento  de  la  nueva  inscripci\xF3n.");
		$pdf->Cell(12,4,"",0,1);

		$pdf->Ln(40);
		$pdf->Cell(62,4,"");
		$pdf->Cell(62,4,"","B",0,"C");
		$pdf->Cell(42,4,"",0,1);
		$pdf->Cell(62,4,"");
		$pdf->Cell(62,4,"Nombre y firma de conformidad socio titular",0,0,"C");
		$pdf->Cell(42,4,"",0,1);

		$pdf->Ln(25);
		$pdf->Cell(62,4,"");
		$pdf->Cell(62,4,"","B",0,"C");
		$pdf->Cell(42,4,"",0,1);
		$pdf->Cell(62,4,"");
		$pdf->Cell(62,4,"Nombre y firma de conformidad socio que se da de baja",0,0,"C");
		$pdf->Cell(42,4,"",0,1);

		$pdf->Ln(25);
		$pdf->Cell(62,4,"");
		$pdf->Cell(62,4,"","B",0,"C");
		$pdf->Cell(42,4,"",0,1);
		$pdf->Cell(62,4,"");
		$pdf->Cell(62,4,"Nombre y sello de quien recibe",0,0,"C");
		$pdf->Cell(42,4,"",0,1);

		$pdf->SetFont("Arial","",10);
		$pdf->SetTextColor(200,200,200);
		$pdf->Text(40,266,$address." ".$address_n." Col ".$neighborhood." ".$city.", ".$state."      ".$phone);

		$pdf->Output("Life & Fitness - Carta Baja Conformidad Baja Membresia.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Carta Horario Basico Conformidad
	 */
	function pdf_horario($info=NULL)
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$this->add_page_header($pdf, "left", time());
		$info["NOMBRE"] = isset($info["NOMBRE"])?utf8_decode($info["NOMBRE"]):"";
		$info["MEMBRESIA"] = isset($info["MEMBRESIA"])?utf8_decode($info["MEMBRESIA"]):"";
		$info["MULTA"] = isset($info["MULTA"])?utf8_decode($info["MULTA"]):"100.00";

		$pdf->Ln();
		$pdf->SetFont("Arial","B",11);

		$pdf->Cell(16, 7, "");
		$pdf->Cell(154, 7, "CARTA CONFORMIDAD MEMBRES\xCDA HORARIO B\xC1SICO", 0, 1, "C");
		$pdf->Ln();

		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(16, 6, "");
		$pdf->Cell(154, 6, "DATOS DEL SOCIO:", 0, 1);
		$pdf->Ln(4);

		$pdf->SetFont("Arial","",10);
		$pdf->Cell(16, 6, "");
		$pdf->Cell(30, 6, "Nombre del socio:");
		$pdf->Cell(124, 6, $info["NOMBRE"], "B", 1);

		$pdf->Cell(16, 6, "");
		$pdf->Cell(27, 6, "Membres\xEDa No.:");
		$pdf->Cell(50, 6, $info["MEMBRESIA"], "B", 1);
		$pdf->Ln(16);

		$pdf->Cell(16, 6, "");
		$pdf->MultiCell(154, 4, "Por este conducto, me comprometo a respetar los horarios establecidos en la membres\xEDa horario b\xE1sico:");
		$pdf->Ln(8);

		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(16, 4, "");
		$pdf->Cell(154, 4, "Lunes a Viernes: de 10:00 a las 18:00",0, 1,"C");
		$pdf->Cell(16, 4, "");
		$pdf->Cell(154, 4, "S\xE1bados: 08:00 a las 18:00",0, 1,"C");
		$pdf->Cell(16, 4, "");
		$pdf->Cell(154, 4, "Domingos: 09:00 a 16:00",0, 1,"C");
		$pdf->Ln(8);

		$pdf->SetFont("Arial","",10);
		$pdf->Cell(16, 4, "");
		$pdf->Cell(154, 4, "De lo contrario, al registrar entrada o salida del club en horarios fuera de lo  establecido, se  har\xE1",0,1);

		$pdf->Cell(16, 4, "");
		$pdf->Cell(6, 4, "un ");
		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(42, 4, "cargo de $ ".$info["MULTA"]." pesos ");
		$pdf->SetFont("Arial","",10);
		$pdf->Cell(106, 4, "a la  cuenta de dicha  membres\xEDa, el  cual tendr\xE1 que ser liquidado",0,1);
		$pdf->Cell(16, 4, "");
		$pdf->Cell(154, 4, "para poder tener acceso al club en sus siguientes visitas.",0,1);
		$pdf->Ln(30);

		$pdf->Cell(46, 4, "");
		$pdf->Cell(94, 4, "","B");
		$pdf->Cell(46, 4, "",0,1);

		$pdf->Cell(46, 4, "");
		$pdf->Cell(94, 4, "Nombre y firma del titular de la membres\xEDa",0,0,"C");
		$pdf->Cell(46, 4, "",0,1);
		$pdf->Ln(30);

		$pdf->Cell(46, 4, "");
		$pdf->Cell(94, 4, "", "B");
		$pdf->Cell(46, 4, "",0,1);

		$pdf->Cell(46, 4, "");
		$pdf->Cell(94, 4, "Nombre y sello de quien recibe",0,0,"C");
		$pdf->Cell(46, 4, "",0,1);

		$pdf->Output("Life & Fitness - Carta Horario Basico Conformidad.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Carta Responsiva Menor de Edad
	 */
	function pdf_responsiva($info=NULL)
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$this->add_page_header($pdf, "left", time());
		foreach (array("SOCIO"=>"","MEMBRESIA"=>"","RESPONSABLE"=>"_____________________________________","DIRECCION"=>"_______________________________________","MENOR"=>"_____________________________________","EDAD"=>"_____") as $field=>$default)
		{
			$info[$field] = isset($info[$field])?utf8_decode($info[$field]):$default;
		}
		$pdf->SetFont("Arial","B",11);
		$pdf->Cell(16,5,"");
		$pdf->Cell(154,5,"CARTA RESPONSIVA MENORES DE EDAD",0,1,"C");
		$pdf->Ln();

		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(16,5,"");
		$pdf->Cell(154,5,"DATOS:",0,1);
		$pdf->Ln();

		$pdf->SetFont("Arial","",9);
		$pdf->Cell(16,5,"");
		$pdf->Cell(30,5,"Nombre del socio:");
		$pdf->Cell(124,5,$info["SOCIO"],"B",1);
		$pdf->Ln(3);

		$pdf->Cell(16,5,"");
		$pdf->Cell(26,5,"Membres\xEDa No.:");
		$pdf->Cell(58,5,$info["MEMBRESIA"],"B");
		$pdf->Cell(78,5,"",0,1);
		$pdf->Ln(12);

		$txt = "Por este conducto, hago constar que yo el Sr. (a) #RESPONSABLE# con direcci\xF3n en #DIRECCION# como padre o responsable del menor con nombre #MENOR# de #EDAD# a\xF1os de edad, autorizo al menor para hacer uso de las instalaciones de Life & Fitenss por lo que asumo toda la responsabilidad ante cualquier suceso que pudiese presentarse y deslindo a Life & Fitness M\xE9xico SA de CV de toda responsabilidad.






                                         ____________________________________________
                                                       Nombre y firma del responsable








                                         ____________________________________________
                                                        Nombre y sello de quien recibe";
		$txt = str_replace(array("#RESPONSABLE#","#DIRECCION#","#MENOR#","#EDAD#"), array($info["RESPONSABLE"],$info["DIRECCION"],$info["MENOR"],$info["EDAD"]), $txt);
		$pdf->Cell(16,5,"");
		$pdf->MultiCell(154, 7, $txt);
		$pdf->Output("Life & Fitness - Carta Responsiva Menor de Edad.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Cesion de Derechos
	 */
	function pdf_cesion($info=NULL)
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$this->add_page_header($pdf, "left", time());

		foreach (array("DIA"=>"___", "MES"=>"____________", "ANIO"=>"20___", "SOCIO"=>"","MEMBRESIA"=>"________","NOMBRE"=>"____________________________________________________","MOTIVO"=>"_____________________________________________________________________________________") as $field=>$default)
		{
			$info[$field] = isset($info[$field])?utf8_decode($info[$field]):$default;
		}

		$pdf->SetFont("Arial","B",11);
		$pdf->Cell(16,5,"");
		$pdf->Cell(154,5,"CARTA CESI\xD3N DE DERECHOS DE MEMBRES\xCDA",0,1,"C");
		$pdf->Ln(10);

		$pdf->SetFont("Arial","B",10);
		$pdf->Cell(16,5,"");
		$pdf->Cell(154,5,"DATOS DEL SOCIO:",0,1);
		$pdf->Ln(4);

		$pdf->SetFont("Arial","",9);
		$pdf->Cell(16,5,"");
		$pdf->Cell(36,5,"Nombre del socio titular:");
		$pdf->Cell(118,5,$info["SOCIO"],"B",1);
		$pdf->Ln(12);

		$txt = "Autorizo  al club  deportivo  Life & Fitness  M\xE9xico S.A de C.V,  realizar el cambio de titular a la membres\xEDa No. #MEMBRESIA#  cediendo  todos  los  derechos  correspondientes  de  dicha  membres\xEDa,  con  el fin de que #NOMBRE# sea el nuevo Titular de la Membres\xEDa Life & Fitness con todos los derechos y obligaciones, dando cumplimiento a los t\xE9rminos y condiciones vigentes desde el d\xEDa #DIA# de #MES# del a\xF1o #ANIO# por el motivo siguiente:
			#MOTIVO#.


                                            ____________________________________________
                                                    Nombre y firma del titular de la membres\xEDa



                                            ____________________________________________
                                                  Nombre y firma del nuevo titular de la membres\xEDa




                                            ____________________________________________
                                                     Nombre y firma del titular de quien recibe";
		$txt = str_replace(array("#MEMBRESIA#","#NOMBRE#","#DIA#","#MES#","#ANIO#","#MOTIVO#"), array($info["MEMBRESIA"],$info["NOMBRE"],$info["DIA"],$info["MES"],$info["ANIO"],$info["MOTIVO"]), $txt);
		$pdf->Cell(16,8,"");
		$pdf->MultiCell(154, 8, $txt);
		$pdf->Output("Life & Fitness - Cesion de Derechos.pdf", "D");
		exit;
	}

	/**
	 * Documentos Internos: Documento Contrato
	 */
	function pdf_contrato_old($info=array())
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		$this->add_page_header($pdf, "center");
		foreach (array("TITULAR"=>"- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -", "DOMICILIO"=>"", "TIPOMEMBRESIA"=>"", "PERIODO"=>"______________________________", "INSCRIPCION"=>"", "MANTENIMIENTO"=>"", "ADICIONALES"=>"", "PENALIDAD"=>"", "PORCIENTO"=>"", "DIA"=>"__________", "MES"=>"____________________________________________", "ANIO"=>"______________________") as $field=>$default)
		{
			$info[$field] = isset($info[$field])?utf8_decode($info[$field]):$default;
			if (is_string($info[$field])){
				$info[$field] = strtoupper($info[$field]);
			}
		}
		$aux = Ant_Accounts_Model::get_info_sucursal($info['ant_accounts_id']);
        $name = strtoupper($aux[0]['nombre_apoderado']);
        $gender = $aux[0]['sexo_apoderado'];
        $tipo = utf8_decode($aux[0]['tipo_apoderado']);
        $address = strtoupper($aux[0]['fiscal_address']);
        $address_n = strtoupper($aux[0]['fiscal_address_ext_num']);
        $neighborhood = strtoupper($aux[0]['neighborhood']);
        $city = strtoupper($aux[0]['fiscal_city']);
        $municipality= strtoupper($aux[0]['fiscal_address_municipality']);
        $state = strtoupper($aux[0]['fiscal_address_state']);
		$h = 2.2;
		$pdf->SetFont("Arial","",5);
		$txt = "\t\tCONTRATO QUE EN LA CIUDAD DE ".strtoupper($city).", ".strtoupper($state).", CELEBRAN POR UNA PARTE ";
		if ($tipo !=null || $tipo != ""){
			if($gender=="H"){
				$txt .="EL ".strtoupper($tipo)." ".$name.", EN SU CAR\xC1CTER DE APODERADO";
			}else if ($gender=="M"){
				$txt .="LA ".strtoupper($tipo)." ".$name.", EN SU CAR\xC1CTER DE APODERADA";
			}else if ($gender==null || $gender==""){
				$txt .="EL/LA ".strtoupper($tipo)." ".$name.", EN SU CAR\xC1CTER DE APODERADO/A";
			}
		}else{
			if($gender=="H"){
				$txt .="EL SE\xD1OR ".$name.", EN SU CAR\xC1CTER DE APODERADO";
			}else if ($gender=="M"){
				$txt .="LA SE\xD1ORA ".$name.", EN SU CAR\xC1CTER DE APODERADA";
			}else if ($gender==null || $gender==""){
				$txt .="EL/LA SE\xD1OR/A".$name.", EN SU CAR\xC1CTER DE APODERADO/A";
			}
		}
		$txt.=" LEGAL DE LA SOCIEDAD DENOMINADA \"Esprezza\", SOCIEDAD AN\xd3NIMA DE CAPITAL VARIABLE, A QUIEN EN LO SUCESIVO SE LE DENOMINAR\xC1 \"EL PRESTADOR\", Y POR OTRA PARTE: {$info["TITULAR"]} \nPOR SU PROPIO DERECHO, Y A QUIEN EN LO SUCESIVO SE LE DENOMINAR\xC1 \"USUARIO TITULAR\", CONTRATO QUE LOS COMPARECIENTES SUJETAN DE CONFORMIDAD A LAS SIGUIENTES DECLARACIONES Y CL\xC1USULAS:";
		$pdf->Cell(5,$h,"");
		$pdf->MultiCell(176, $h, $txt);
		$pdf->Ln();
		$pdf->SetLineWidth(0.5);
		//$pdf->SetDrawColor(231,76,82);
		//$pdf->RoundedRect(15, $pdf->GetY(), 186, 8, 2);
		$pdf->Line(20, $pdf->GetY(), 196, $pdf->GetY());
		$pdf->Ln(3);
		$pdf->SetLineWidth(0.1);
		$pdf->SetDrawColor(0,0,0);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(176,$h,"D E C L A R A C I O N E S:",0,1,"C");
		$pdf->Ln(6);
		$txt = "I. DECLARA ";
		if ($tipo !=null || $tipo != ""){
			if($gender=="H"){
				$txt .="EL ".strtoupper($tipo)." ".$name.", EN SU CAR\xC1CTER DE APODERADO";
			}else if ($gender=="M"){
				$txt .="LA ".strtoupper($tipo)." ".$name.", EN SU CAR\xC1CTER DE APODERADA";
			}else if ($gender==null || $gender==""){
				$txt .="EL/LA ".strtoupper($tipo)." ".$name.", EN SU CAR\xC1CTER DE APODERADO/A";
			}
		}else{
			if($gender=="H"){
				$txt .="EL SE\xD1OR ".$name.", EN SU CAR\xC1CTER DE APODERADO";
			}else if ($gender=="M"){
				$txt .="LA SE\xD1ORA ".$name.", EN SU CAR\xC1CTER DE APODERADA";
			}else if ($gender==null || $gender==""){
				$txt .="EL/LA SE\xD1OR/A".$name.", EN SU CAR\xC1CTER DE APODERADO/A";
			}
		}
		//         //
		$txt.=" LEGAL DE LA SOCIEDAD DENOMINADA \"DEXFIT\", SOCIEDAD AN\xD3NIMA DE CAPITAL VARIABLE, BAJO PROTESTA DE DECIR VERDAD, Y SABEDOR DE LAS CONSECUENCIAS LEGALES DE CONDUCIRSE EN CONTRARIO, LO SIGUIENTE:

			 A)  QUE  ES  UNA PERSONA MORAL LEGALMENTE CONSTITUIDA CONFORME A LA LEGISLACION MEXICANA, MEDIANTE ESCRITURA P\xDABLICA 1732 (DIECISIETE MIL TREINTA Y DOS), OTORGADA   ANTE\n         LA  FE DEL LICENCIADO  CARLOS  HIJAR  ESCARE\xD1O,   NOTARIO P\xDABLICO 10 (DIEZ),  EN LA MUNICIPALIDAD DE  ZAPOPAN,  JALISCO;   CUYO PRIMER TESTIMONIO SE ENCUENTRA DEBIDAMENTE\n         INSCRITO EN EL REGISTRO P\xDABLICO DE LA PROPIEDAD Y DE GUADALAJARA, JALISCO.\n    B)  QUE SU REPRESENTADA TIENE SU DOMICILIO FISCAL EN LA FINCA MARCADA CON EL N\xDAMERO 746, DE LA CALLE AV. PATRIA, Y  CON  REGISTRO  FEDERAL  DE  CONTRIBUYENTES\n         DEX170103BU0;  SIENDO  LEG\xCDTIMA  POSEEDORA  DE  LAS  INSTALACIONES  DEPORTIVAS DENOMINADAS \"DEXFIT\", UBICADAS EN LA CALLE \n         ".strtoupper($address)." #".$address_n." Col ".strtoupper($neighborhood). ", ". strtoupper($municipality).", ".strtoupper($state).".\n    C)  QUE SU REPRESENTADA TIENE LA CAPACIDAD JUR\xCDDICA NECESARIA PARA EL CUMPLIMIENTO DE LAS OBLIGACIONES ESTABLECIDAS EN EL PRESENTE ACUERDO DE VOLUNTADES.

			II. DECLARA EL(LA) SE\xD1OR(A) {$info["TITULAR"]}, BAJO PROTESTA DE DECIR VERDAD, Y SABEDOR(A) DE LAS CONSECUENCIAS LEGALES DE CONDUCIRSE EN CONTRARIO, LO SIGUIENTE:

			A)  QUE ES UNA PERSONA F\xCDSICA DE NACIONALIDAD MEXICANA, MAYOR DE EDAD, CON LA CAPACIDAD JUR\xCDDICA NECESARIA PARA LA CELEBRACI\xD3N DEL PRESENTE CONTRATO, MANIFESTANDO\n          TENER SU DOMICILIO EN:";
		$pdf->Cell(5,$h,"");
		$pdf->MultiCell(176, $h, $txt);
		$pdf->Ln();

		$pdf->Ln(5);

		$pdf->SetFont("Arial","B",7);
		$pdf->Cell(5,$h,"");
		$pdf->MultiCell(175, $h, $info["DOMICILIO"]);
		$pdf->Ln(5);
		$pdf->SetLineWidth(0.5);
		$pdf->Line(20, $pdf->GetY(), 196, $pdf->GetY());
		$pdf->SetLineWidth(0.1);
		$pdf->Ln(5);

		$pdf->SetFont("Arial","",5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(176,$h,"    B)  QUE ES SU VOLUNTAD INSCRIBIRSE COMO USUARIO TITULAR DE UNA MEMBRESIA TIPO:",0,1);
		$pdf->Ln(5);

		$pdf->SetFont("Arial","B",7);
		$pdf->Cell(40,$h,$info["TIPOMEMBRESIA"],0,1,"C");//$pdf->Text(20, 124, $info["TIPOMEMBRESIA"]);
		$pdf->SetLineWidth(0.1);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetFont("Arial","",5);
		$pdf->Ln(5);

		$pdf->SetLineWidth(0.5);
		$pdf->Line(20, $pdf->GetY(), 196, $pdf->GetY());
		$pdf->SetLineWidth(0.1);
		$pdf->Ln(6);

		// ".strtoupper($address)." #".$address_n." Col ".strtoupper($neighborhood).",

		$txt = utf8_decode("TENIENDO  COMO  GIMNASIO  BASE  LAS  INSTALACIONES  DENOMINADAS \"DEXFIT\",  CON   DOMICILIO  EN  LA AV. DE LAS AMÉRICAS, COUNTRY CLUB, IDENTIFICADO CON EL NÚMERO 1254, EN  LA  MUNICIPALIDAD  DE  ".strtoupper($municipality).",  ".strtoupper($state)."; QUIEN TIENE AMPLIO CONOCIMIENTO DE LOS  TÉRMINOS,  DERECHOS Y OBLIGACIONES ESTABLECIDOS EN EL PRESENTE ACUERDO DE VOLUNTADES.
			C)  QUE A LA FIRMA DEL PRESENTE CONTRATO CUBRE LA CUOTA DE INSCRIPCIÓN CORRESPONDIENTE, ELIGIENDO PAGAR LA CUOTA DE MANTENIMIENTO EN FORMA:\n          {$info["PERIODO"]}.
			D)  QUE CONOCE Y COMPRENDE EL REGLAMENTO INTERNO DEL GIMNASIO, MISMO QUE CONTIENE LAS NORMAS A SEGUIR DENTRO DE LAS INSTALACIONES UBICADAS EN EL DOMICILIO DESCRITO\n         EN LA  DECLARACIÓN  LETRA  \"B\"  QUE  ANTECEDE; REGLAMENTO  QUE  FORMA  PARTE  DEL  PRESENTE  CONTRATO,  MISMO  QUE  SE  OBLIGA A CUMPLIR Y HACER CUMPLIR POR SUS\n         USUARIOS DEPENDIENTES.

			III. DECLARAN LOS COMPARECIENTES QUE TIENEN CAPACIDAD LEGAL PARA CELEBRAR EL PRESENTE CONTRATO Y LO REALIZAN POR SU LIBRE Y ESPONTÁNEA VOLUNTAD SUJETÁNDOLO A LO DISPUESTO EN LOS ARTÍCULOS 1980 AL 1983, 1987, 1988, 1989, 1990, 1995 AL 1999, 2005, 2007, 2015 AL 2019, 2023, 2024, 2037, 2045, 2046, 2052, 2053, 2141, 2143, 2143BIS 2144 DEL CÓDIGO CIVIL DEL ESTADO DE JALISCO Y LO QUE EN ÉSTE INSTRUMENTO SE PACTA, EXCLUSIVAMENTE.

			EXPUESTO LO ANTERIOR LOS COMPARECIENTES OTORGAN LAS SIGUIENTES");

		$pdf->Cell(5,$h,"");
		$pdf->MultiCell(176, $h, $txt);
		$pdf->Ln();

		$pdf->Cell(5,$h,"");
		$pdf->Cell(176,$h,"C L A U S U L A S:",0,1,"C");
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(24, $h, "PRIMERA. DEFINICIONES.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(154, $h, utf8_decode("PARA EFECTOS DE LA INTERPRETACIÓN DEL PRESENTE CONTRATO, DEBERÁ ENTENDERSE POR CADA UNO DE LOS CONCEPTOS EXPRESADOS EN EL MISMO, LO SIGUIENTE:"), 0, 1);
		$pdf->Ln();

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "1. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(11, $h, "GIMNASIO.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(163, $h, utf8_decode("EL ESTABLECIMIENTO UBICADO EN LA AV. DE LAS AMÉRICAS, COUNTRY CLUB IDENTIFICADO CON EL NÚMERO 1254,"),0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, utf8_decode("EN LA MUNICIPALIDAD DE ".strtoupper($municipality).", ".strtoupper($state).", LUGAR EN DONDE SE ENCUENTRA EL CENTRO DE ACONDICIONAMIENTO AL CUAL TENDRÁN ACCESO LOS USUARIOS, E IDENTIFICADO"),0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, utf8_decode("COMO GIMNASIO BASE. EN DICHO GIMNASIO BASE,  EL USUARIO DEBERÁ LLEVAR A CABO LA REALIZACIÓN DE CUALQUIER TRÁMITE RELATIVO A SU SUSCRIPCIÓN."),0,1);
		$pdf->Ln();

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "2. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(18, $h, "USUARIO TITULAR.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(153, $h, utf8_decode("ES  LA  PERSONA  FÍSICA, MAYOR DE EDAD, O PERSONA MORAL, QUE SUSCRIBE EL  CONTRATO Y DE  QUIEN  SE  RECIBE  EL  PAGO  DE  LAS  CUOTAS DE SUSCRIPCIÓN"),0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "Y MANTENIMIENTO.",0,1);
		$pdf->Cell(188, $h, "",0,1);
		/*
		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "3. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(24, $h, "USUARIO DEPENDIENTE.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(147, $h, utf8_decode("ES LA PERSONA FÍSICA MAYOR DE 16 DIECISÉIS AÑOS QUE SIN SER EL SUSCRIPTOR DEL CONTRATO, ES REGISTRADO COMO USUARIO INDIVIDUAL O COMO PARTE DE"),0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, utf8_decode("UNA MEMBRESÍA DE PAREJA, FAMILIAR O FAMILIAR PLUS."),0,1);
		$pdf->Cell(188, $h, "",0,1);
		*/
		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "3. ", 0, 0, "R");
		$pdf->Cell(188, $h, "",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(12, $h, "USUARIOS.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(159, $h, utf8_decode("SON LAS PERSONAS FÍSICAS QUE SON TITULARES DE UNA SUSCRIPCIÓN"),0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "",0,1);

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "4. ", 0, 0, "R");
		$pdf->Cell(188, $h, "",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(24, $h, utf8_decode("CUOTA DE INSCRIPCIÓN."),"B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(147, $h, utf8_decode("ES LA CANTIDAD LIQUIDA QUE DEBE PAGAR EL USUARIO TITULAR COMO CONTRAPRESTACIÓN DE LA SUSCRIPCIÓN EN EL TIPO, CLASE Y MODALIDAD QUE ELIJA."),0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "",0,1);
		$pdf->Ln();

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "5. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(28, $h, "CUOTA DE MANTENIMIENTO.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(143, $h, "ES EL CANTIDAD LIQUIDA QUE DEBE PAGAR EL USUARIO TITULAR COMO CONTRAPRESTACI\xD3N POR EL ACCESO.  ESTA  CUOTA  ES  ANUAL",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "PUDIENDO EN ALGUNOS CASOS OTORGARSE PLAZOS MENSUALES Y SEMESTRALES PARA SU PAGO",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "",0,1);
		$pdf->Ln();

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "6. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(15, $h, "REGLAMENTO.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(156, $h, "ES  EL  CONJUNTO  DE  NORMAS  ESPEC\xCDFICAS  QUE  REGULAN  LOS  T\xC9RMINOS  Y  CONDICIONES  DE  LA  PRESTACI\xD3N  DEL SERVICIO  POR PARTE  DE \"EL PRESTADOR\",",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "Y EL COMPORTAMIENTO DE LOS USUARIOS.",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "",0,1);
		$pdf->Ln();

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "7. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(11, $h, "INVITADO.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(160, $h, "ES  LA  PERSONA  F\xCDSICA  QUE  ACOMPA\xD1A  AL  USUARIO,  TITULAR  O  DEPENDIENTE,  ACUDIENDO  EN  FORMA  ESPOR\xC1DICA  Y  EVENTUAL  AL  GIMNASIO,  PARA HACER USO",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "DE  LAS  INSTALACIONES   DEL   MISMO;  PREVIO PAGO DE LA CUOTA POR INVITACI\xD3N QUE AL EFECTO DETERMINE  \"EL PRESTADOR\",  QUIEN SOLO PODR\xC1 HACER USO DE LAS",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "INSTALACIONES DE ACUERDO A LA CLASE, TIPO Y MODALIDAD DEL PAQUETE DE SERVICIOS CONTRATADO POR EL USUARIO  TITULAR",0,1);
		$pdf->Ln();

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "8. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(13, $h, "INSCRIPCI\xD3N","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(158, $h, "ES EL T\xCDTULO DE VIGENCIA DEFINIDA DE MANTENIMIENTO ANUAL, QUE OTORGA AL SUSCRIPTOR DEL CONTRATO EL DERECHO DE HACER USO DE LAS INSTALACIONES DEL",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "GYMNASIO COMO CONTRAPRESTACI\xD3N DEL PAGO DE LAS CUOTAS DE MANTENIMIENTO.",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "",0,1);

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "9. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(34, $h, "CLASES DE PAQUETES DE SERVICIO","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(146, $h, "ES  LA  CLASIFICACI\xD3N  DE  LOS  SERVICIOS CON  BASE  EN  EL  ACCESO,  USO  Y  HORARIO  DE  LAS  INSTALACIONES  INCLUIDAS EN EL PAGO DE",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "LAS CUOTAS DE INSCRIPCI\xD3N Y MANTENIMIENTO.",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "",0,1);

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "10. ", 0, 0, "R");
		$pdf->Cell(188, $h, "",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(24, $h, "SERVICIOS ADICIONALES.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(147, $h, " SON AQUELLOS SERVICIOS QUE  SE PRESTAN EN EL GIMNASIO, CUYO  USO  NO  SE ENCUENTRA INCLUIDO DENTRO DE LA  CLASE DE PAQUETES  DE  SERVICIOS",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(147, $h, "ELEGIDO, POR EL USUARIO",0,1);

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "11. ", 0, 0, "R");
		$pdf->Cell(188, $h, "",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(22, $h, "POBLACI\xD3N ESPECIAL.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(149, $h, "SON  AQUELLOS USUARIOS QUE  PADEZCAN  ALGUNA ENFERMEDAD CUYOS S\xCDNTOMAS Y RIESGOS DE SALUD SE PUEDAN AGRAVAR  POR  LA  ACTIVIDAD F\xCDSICA",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, " VIGOROSA O LA PR\xC1CTICA DE ALG\xDAN DEPORTE.",0,1);

		$this->add_page_footer($pdf,$address,$address_n,$neighborhood);

		$this->add_page_header($pdf,"center");
		/*
		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "11. ", 0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(23, $h, "TIPOS DE MEMBRES\xCDAS.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(148, $h, "ES LA CLASIFICACI\xD3N DE LAS MEMBRES\xCDAS CON BASE EN EL N\xDAMERO DE PERSONAS QUE BAJO EL AMPARO DE \xC9STA, TENDR\xC1N ACCESO AL CLUB DE ACUERDO A LA",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "CLASE DE LA MISMA.  TIPOS QUE ATIENDEN A LA CLASIFICACI\xD3N SIGUIENTE:",0,1);

		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(32, $h, "A)  MEMBRES\xCDA TIPO INDIVIDUAL.-");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(139, $h, "COMPRENDE EL ACCESO Y USO DE LAS INSTALACIONES DEL CLUB, RESPECTO A UNA SOLA PERSONA, MAYOR DE 16 A\xD1OS. SI EL SUSCRIPTOR NO ES MAYOR",0,1);
		$pdf->Cell(20, $h, "");
		$pdf->Cell(168, $h, "DE 18 A\xD1OS TENDR\xC1 QUE FIRMAR SU TUTOR O CURADOR EN SU REPRESENTACI\xD3N.",0,1);

		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(30, $h, "B)  MEMBRES\xCDA TIPO FAMILIAR.-");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(141, $h, "COMPRENDE EL ACCESO Y USO DE LAS INSTALACIONES DEL CLUB,  RESPECTO  A  UNA  FAMILIA  CON  UN  M\xCDNIMO  DE  TRES  MIEMBROS, TODOS MAYORES DE",0,1);
		$pdf->Cell(20, $h, "");
		$pdf->Cell(168, $h, "16 A\xD1OS; EL SUSCRIPTOR DEBER\xC1 TENER UNA EDAD M\xCDNIMA DE 18 A\xD1OS. POR PARENTESCO FAMILIAR DEBER\xC1 ENTENDERSE HASTA EL 4º GRADO.",0,1);

		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(35, $h, "C)  MEMBRES\xCDA TIPO EMPRESARIAL.-");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(136, $h, "COMPRENDE  EL  ACCESO  Y  USO  DE  LAS  INSTALACIONES  DEL  CLUB, RESPECTO A UNA PERSONA MORAL CUYOS DERECHOS Y OBLIGACIONES SER\xC1N",0,1);
		$pdf->Cell(20, $h, "");
		$pdf->Cell(168, $h, "DEFINIDOS EN CONTRATO ANEXO.",0,1);

		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(29, $h, "D)  MEMBRES\xCDA TIPO GRUPAL.-");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(142, $h, "COMPRENDE EL ACCESO Y USO DE LAS INSTALACIONES  DEL CLUB, RESPECTO A UN GRUPO DE PERSONAS EN  DONDE UNO DE ELLOS  SER\xC1 EL RESPONSABLE",0,1);
		$pdf->Cell(20, $h, "");
		$pdf->Cell(168, $h, "ANTE EL CLUB, RESPECTO DEL CUMPLIMIENTO  DE LAS OBLIGACIONES  CONTENIDAS EN EL  PRESENTE ACUERDO DE  VOLUNTADES; EL SUSCRIPTOR TENDR\xC1 QUE TENER UNA EDAD M\xCDNIMA",0,1);
		$pdf->Cell(20, $h, "");
		$pdf->Cell(168, $h, "DE 18 A\xD1OS.",0,1);
		$pdf->Ln();
		*/

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "12. ", 0, 0, "R");
		$pdf->Cell(188, $h, "",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(17, $h, "VISITA EFECTIVA.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(154, $h, "ES EL INGRESO DE AQUEL USUARIO A LAS INSTALACIONES DEL GIMNASIO, QUE LO REALICE EN FORMA EFECTIVA.",0,1);
		$pdf->Ln();

		$pdf->Cell(10,$h,"");
		$pdf->SetFont("Arial", "B", 11);
		$pdf->Cell(7, 6.6, "13. ", 0, 0, "R");
		$pdf->Cell(188, $h, "",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(33, $h, "RESCISI\xD3N, CANCELACI\xD3N Y BAJA.","B");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(138, $h, "SE  UTILIZAN COMO  SIN\xD3NIMOS, Y  SIGNIFICA  LA TERMINACI\xD3N  ANTICIPADA  DE  LA VIGENCIA DE LA PRESTACI\xD3N  DE  SERVICIOS POR  UNA CAUSA",0,1);
		$pdf->Cell(17, $h, "");
		$pdf->Cell(171, $h, "IMPUTABLE AL USUARIO",0,1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(20, $h, "SEGUNDA. OBJETO.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(54, $h, "EN VIRTUD DE LA CELEBRACI\xd3N DEL PRESENTE CONTRATO");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18, $h, '"EL PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(89, $h, "PONE  A  DISPOSICI\xD3N  DEL  USUARIO TITULAR Y USUARIOS DEPENDIENTES QUE ESTE DESIGNE, EN", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "LOS T\xC9RMINOS Y CONDICIONES QUE CORRESPONDAN A LA CLASE Y TIPO DE PAQUETE DE SERVICIOS ELEGIDO POR EL USUARIO TITULAR, EL ACCESO A LAS INSTALACIONES Y A LOS SERVICIOS QUE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "PRESTA EL GIMNASIO, Y EN QUE SE ENCUENTREN INCLUIDOS EN LA CLASE Y TIPO DE SERVICIOS ESPECIFICADOS EN LAS DECLARACIONES QUE ANTECEDEN.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(31, $h, "TERCERA. CONTRAPRESTACI\xD3N.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(150, $h, "EL  CUMPLIMIENTO  DE  LAS  CONTRAPRESTACIONES  ESTABLECIDAS  EN  EL  PRESENTE  ACUERDO  DE VOLUNTADES, PODR\xC1 SER REALIZADO MEDIANTE EL PAGO (I) EN", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(151, $h, "EFECTIVO, (II) CHEQUE CERTIFICADO, (III) CARGO A LA TARJETA DE CR\xC9DITO O D\xC9BITO, \xD3 (IV) TRANSFERENCIA ELECTR\xD3NICA DE FONDOS; CONTRAPRESTACIONES QUE, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(11, $h, " SE OBLIGA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(20, $h, "A PAGAR A FAVOR DE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18, $h, '"EL PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(143, $h, "POR LOS CONCEPTOS SIGUIENTES:", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5,$h,"");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(4,$h,"A).",0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(39, $h, "CUOTA POR CONCEPTO DE INSCRIPCI\xD3N,", 0, 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(114, $h, "CUYA CLASE, TIPO Y  MODALIDAD  HAN  QUEDADO  SE\xD1ALADAS  EN  LAS  DECLARACIONES  DEL  PRESENTE ACUERDO DE VOLUNTADES; CUOTA GENERADA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(103, $h, "EN FORMA ANUAL, CUYO PAGO PODR\xC1 CUMPLIRSE EN  FORMA MENSUAL,  SEMESTRAL O ANUAL,  A ELECCI\xD3N DEL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(20, $h, '"USUARIO TITULAR",', 0, 0 , 'L');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(56, $h, "EN LAS MODALIDADES ESTABLECIDAS  DENTRO  DEL PROEMIO", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "DE LA PRESENTE CL\xC1USULA.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5,$h,"");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(4,$h,"B).",0, 0, "R");
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(42, $h, "CUOTA POR CONCEPTO DE MANTENIMIENTO,", 0, 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(135, $h, "CUYA   CLASE,   TIPO   Y   MODALIDAD  HAN  QUEDADO   SE\xD1ALADAS  EN  LAS   DECLARACIONES  DEL  PRESENTE   ACUERDO  DE  VOLUNTADES;   CUOTA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(110, $h, "GENERADA EN FORMA ANUAL, CUYO PAGO PODR\xC1 CUMPLIRSE EN FORMA MENSUAL, SEMESTRAL O ANUAL, A ELECCI\xD3N DEL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(20, $h, '"USUARIO TITULAR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(62, $h, " EN  LAS  MODALIDADES  ESTABLECIDAS  DENTRO  DEL ", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "PROEMIO DE LA PRESENTE CL\xC1USULA.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(48, $h, "CUOTAS  DE  INSCRIPCI\xD3N  Y DE MANTENIMIENTO,");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(45, $h, "QUE SEGUIR\xC1N SIENDO GENERADAS, NO OBSTANTE,");
		//$pdf->SetFont("Arial", "B", 5);
		//$pdf->Cell(19, $h, 'USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(100, $h, "TENGAN INASISTENCIA O FALTA DE USO DE LAS INSTALACIONES DEL  GIMNASIO; EN TAL VIRTUD, ",0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(167, $h, "DICHAS  RAZONES  NO SER\xC1N CAUSA JUSTIFICADA DE NO PAGO,  POR LO QUE EL PAGO MENSUAL, SEMESTRAL O ANUAL DE  LA  CUOTA  DE MANTENIMIENTO DEBER\xC1 SER  TOTAL  Y  OPORTUNAMENTE" ,0 , 1);
		$pdf->Cell(5, $h, "");
		//$pdf->Cell(65, $h, " ", 0, 1);
		$pdf->Cell(25, $h, "CUBIERTA. EN ESTE CASO EL ");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(20, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(59, $h, "SE COMPROMETE A PAGAR LOS INCREMENTOS QUE SE REGISTREN");
		//$pdf->Cell(5, $h, "");
		$pdf->Cell(112, $h, " EN  LAS  CUOTAS DE INSCRIPCI\xD3N Y DE MANTENIMIENTO, RESPECTO DE LOS MESES", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(38, $h, "RESTANTES DE VIGENCIA DE SU MEMBRES\xCDA.");
		$pdf->Ln(5);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(29, $h, "EN  EL  SUPUESTO  DE  QUE,  EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(133, $h, "OPTE  POR  DAR  CUMPLIMIENTO  A SU  OBLIGACI\xD3N  MEDIANTE  EL PAGO CON CHEQUE, Y EL MISMO NO SEA CUBIERTO POR CAUSAS IMPUTABLES AL", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(11, $h, "LIBRADOR,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(153, $h, "SE  RESERVA EL DERECHO  DE REALIZAR EL COBRO  ADICIONAL DEL 20% VEINTE  POR CIENTO  SOBRE LA CANTIDAD  ESTABLECIDA EN EL DOCUMENTO, POR CONCEPTO DE", 0, 1);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "DA\xD1OS Y PERJUICIOS, DE ACUERDO A LO ESTIPULADO EN EL ARTICULO 193  CIENTO NOVENTA Y TRES DE LA  LEY  GENERAL  DE  TITULOS  Y  OPERACIONES DE CREDITO; CANTIDAD A LA QUE SE SUMAR\xC1N", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "LOS  CARGOS  QUE  POR  CHEQUE  DEVUELTO  SEAN  IMPUESTOS  POR LAS INSTITUCIONES  DE  CR\xC9DITO.  EN TAL VIRTUD, EL PAGO POSTERIOR A DICHA DEVOLUCI\xD3N SER\xC1 \xDANICA Y EXCLUSIVAMENTE EN", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "EFECTIVO.", 0, 1);
		$pdf->Ln(2);
	    $pdf->Cell(5, $h, "");
		//$pdf->Cell(5, $h, "");
		$pdf->Cell(186, $h, "LAS   CANTIDADES   CORRESPONDIENTES  A  LAS  CUOTAS  QUE  POR   CONCEPTO   DE   INSCRIPCI\xD3N   Y   DE MANTENIMIENTO ESTABLECIDAS EN LA PRESENTE CL\xC1USULA, SER\xC1N ESTABLECIDAS  EN  LA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->SetFont("Arial", "U", 5);
		$pdf->Cell(18, $h, "CL\xC1USULA S\xC9PTIMA", 0, 0);
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(100, $h, ", DEL PRESENTE ACUERDO DE VOLUNTADES.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(52, $h, "CUARTA. INCREMENTO DE CUOTAS DE MANTENIMIENTO.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(3, $h, "EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(108, $h, " SE OBLIGA A PAGAR LA CUOTA DE MANTENIMIENTO, DE CONFORMIDAD A LO ESTABLECIDO EN EL PRESENTE CONTRATO,", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(163, $h, "LA  CUAL  INCREMENTAR\xC1  EN  UN  PORTAJE  EN  FORMA  ANUAL, DE CONFORMIDAD A LO ESTABLECIDO POR EL \xCDNDICE INFLACIONARIO NACIONAL, MISMO QUE SER\xC1 NOTIFICADO POR");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18, $h, '"EL   PRESTADOR"', 0, 1);
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, 'DENTRO DE LAS INSTALACIONES DEL PROPIO "GIMNASIO" CON UN MES DE ANTICIPACI\xD3N AL VENCIMIENTO DEL PRESENTE ACUERDO DE VOLUNTADES.', 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(30, $h, "QUINTA. INTER\xC9S MORATORIO.- ", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(100, $h, "LA FALTA DE PAGO OPORTUNO DE LA CUOTA DE MANTENIMIENTO, TENDR\xC1 COMO CONSECUENCIA: (I) NEGAR AL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		//$pdf->Cell(32, $h, " ", 0, 1);
		//$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, ",  Y  (II) EL COBRO DE  UN INTER\xC9S", 0, 1);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(181, $h, "MORATORIO DEL 10% SOBRE SALDO VENCIDO, TOMANDO COMO BASE LA TASA DE INTER\xC9S INTERBANCARIA DE EQUILIBRIO, M\xC1S OCHO PUNTOS PORCENTUALES.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(40, $h, "SEXTA. PAGO DE SERVICIOS ADICIONALES.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(141, $h, "PARA  TENER  DERECHO  AL USO DE LOS SERVICIOS  ADICIONALES,  NO INCLUIDOS EN LA CLASE DEL PAQUETE DE SERVICIOS   ELEGIDA, EL USUARIO TITULAR ", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(70, $h, "DEBERA  PAGAR   LAS  CANTIDADES  QUE  PARA  DICHO  SERVICIO  DETERMINE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(84, $h, "   SUJETANDOSE  EN  TODO  MOMENTO  A  LA  DISPONIBILIDAD  DEL  MISMO Y A LOS  TERMINOS Y", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "CONDICIONES FIJADAS EN EL  REGLAMENTO; EN CASO DE CANCELAR POSTERIORMENTE LOS SERVICIOS SOLICITADOS, LOS USUARIOS NO TENDRAN DERECHO A REMBOLSO ALGUNO.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(27, $h, "S\xC9PTIMA. FINANCIAMIENTO.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(154, $h, "PARA  DAR  CUMPLIMIENTO A LAS CONTRAPRESTACIONES  ESTABLECIDAS  EN LA CL\xC1USULA  TERCERA  DEL  PRESENTE  CONTRATO, EN EL SUPUESTO DE QUE SE OPTE POR", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(46, $h, "PAGAR   LAS  MISMAS  EN FORMA  MENSUAL,  EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(26, $h, "PODR\xC1 HACER  USO  DE UN");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(23, $h, '"CONTRATO DE MUTUO"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(55, $h, "  QUE  SER\xC1   CONCEDIDO  POR  LA  SOCIEDAD DENOMINADA  ");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(10, $h, '"DEXFIT",', 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(41, $h, "SOCIEDAD AN\xD3NIMA DE CAPITAL VARIABLE;");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(140, $h, "FINANCIAMIENTO QUE SE REALIZAR\xC1 PARA CUBRIR LOS CONCEPTOS SIGUIENTES:", 0, 1);
		$pdf->Cell(11, $h, "A) ", 0, 0, "R");
		$pdf->Cell(71, $h, "PARA EL PAGO DE CUOTA DE INSCRIPCI\xD3N, CORRESPONDE LA CANTIDAD DE");
		$pdf->Cell(98, $h, $info["INSCRIPCION"], "B", 1);
		$pdf->Ln();
		$pdf->Cell(11, $h, "B) ", 0, 0, "R");
		$pdf->Cell(74, $h, "PARA EL PAGO DE CUOTAS DE MANTENIMIENTO, CORRESPONDE LA CANTIDAD DE");
		$pdf->Cell(96, $h, $info["MANTENIMIENTO"], "B", 1);
		$pdf->Ln();
		$pdf->Cell(11, $h, "C) ", 0, 0, "R");
		$pdf->Cell(70, $h, "PARA EL PAGO DE SERVICIOS ADICIONALES, CORRESPONDE LA CANTIDAD DE");
		$pdf->Cell(100, $h, $info["ADICIONALES"], "B", 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(3, $h, "EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(115, $h, "SE  OBLIGA  A  REALIZAR LOS  PAGOS  DEL  CONTRATO  DE   MUTUO   DE  M\xC9RITO, A FAVOR  DE   LA   SOCIEDAD   DENOMINADA ");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(40, $h, '"DEXFIT" ,  SOCIEDAD '."AN\xD3NIMA  DE  CAPITAL", 0, 1);

		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(53, $h, "VARIABLE, A QUIEN EN LOS SUCESIVO SE LE DENOMINARA");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(16, $h, '"LA SOCIEDAD",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(23, $h, "EN EL T\xC9RMINO QUE, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(4, $h, "Y", 0, 0, "C");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(15, $h, '"LA SOCIEDAD"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(32, $h, " DETERMINEN.  EN TAL VIRTUD, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"', 0, 1);
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "FIRMAR\xC1  DOCUMENTOS  DE  NATURALEZA  MERCANTIL,  LOS  CUALES  CONTENDR\xC1N  LOS  ELEMENTOS  NECESARIOS  PARA  REALIZARLOS  EJECUTIVOS  EN CASO DE INCUMPLIMIENTO, COMPRENDIENDO", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "DENTRO DE LOS MISMOS, LOS INTERESES DE FINANCIAMIENTO Y MORATORIOS, AS\xCD COMO FECHA DE VENCIMIENTO Y FIRMA DE AVAL.  LAS PARTES  ACUERDAN QUE DICHOS DOCUMENTOS SER\xC1N AJENOS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "AL CUMPLIMIENTO DEL PRESENTE CONTRATO, POR LO QUE SE CONSIDERAR\xC1N AUT\xD3NOMOS, PARA REALIZAR SU TRANSMISI\xD3N A FAVOR DE TERCEROS Y COBRO DE LOS MISMOS EN CASO NECESARIO.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(27, $h, "EN EL SUPUESTO DE QUE, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(135, $h, "OPTE  POR  PAGAR  LAS OBLIGACIONES  ESTABLECIDAS  EN LA  CL\xC1USULA TERCERA  QUE PRECEDE, EN  FORMA ANUAL Y EN UNA S\xD3LA EXHIBICI\xD3N, LA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(24, $h, "SOCIEDAD  DENOMINADA");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(50, $h, '"DEXFIT", SOCIEDAD '."AN\xD3NIMA DE CAPITAL VARIABLE,");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(92, $h, "ELABORAR\xC1  UN  RECIBO  DE DICHO  PAGO  A  SU FAVOR, INDICANDO LA CANTIDAD, FORMA, Y FECHA DE DICHO  ACTO.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(22, $h, "OCTAVA. AUSENCIAS.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(27, $h, "EN EL SUPUESTO DE QUE, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(190, $h, "LLEGASE  A AUSENTARSE EN EL USO DE LOS SERVICIOS OFERTADOS EN EL PRESENTE  ACUERDO  DE VOLUNTADES, SER\xC1", 0, 1);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(185, $h, "ACREEDOR A UNA SANCI\xD3N ECON\xD3MICA DE ACUERDO A LAS DISPOSICIONES SIGUIENTES:", 0, 1);

		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(4, $h, "A).");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(177, $h, "SI  SE OPT\xD3 POR  PAGAR  EL MANTENIMIENTO EN FORMA  MENSUAL, Y LOS USUARIOS DE LA MISMA SE AUSENTAN DE 3 A 6 MESES, TENDR\xC1N  QUE  CUBRIR  EL  IMPORTE  CORRESPONDIENTE AL VALOR ", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(177, $h, "QUE SE TENGA EN ESE MOMENTO POR CONCEPTO DE REACTIVACI\xD3N. ", 0, 1);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(58, $h, "APARTIR DE 6 MESES DE AUSENCIA SIN AVISO POR ESCRITO, EL ");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(181, $h, " PIERDE TODA LA ANTIG\xDCEDAD DE PRECIO POR LO CUAL A LA HORA DE REACTIVARSE TENDR\xD3 QUE SER  SOBRE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "PRECIOS ACTUALES.", 0, 1);

		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(4, $h, "B).");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(70, $h, "SI SE OPT\xD3 POR PAGAR LA MEMBRES\xCDA EN  FORMA SEMESTRAL O ANUAL, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(102, $h, "DEBER\xC1  DE  OTORGAR  AVISO  POR ESCRITO AL GIMNASIO, INFORMANDO EL  TIEMPO Y MOTIVO ", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(79, $h, "DE SU AUSENCIA  (LA CUAL NO PODR\xC1 SER MAYOR A 3 TRES MESES). EN TAL VIRTUD, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(102, $h, "TENDR\xC1 DERECHO A RECUPERAR LOS MESES NO UTILIZADOS POR AUSENCIA.", 0, 1);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "LOS  PAGOS  ESTABLECIDOS  EN  LA  PRESENTE  CL\xC1USULA, TENDR\xC1N  COMO  BASE,  EL COSTO DE LA INSCRIPCI\xD3N O PAGO MENSUAL DE LA MEMBRES\xCDA QUE SE TENGA AL MOMENTO DE EFECTUARSE LA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "AUSCENCIA.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(34, $h, "NOVENA. CESI\xD3N DE MEMBRES\xCDAS.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(3.5, $h, "EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18.5, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(125, $h, " TIENE LA FACULTAD DE CEDER O TRANSMITIR LOS DERECHOS DERIVADOS DE SU MEMBRES\xCDA; SIEMPRE Y CUANDO DICHO USUARIO TENGA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "CUBIERTAS  LAS  CUOTAS  ESTABLECIDAS  EN LA CL\xC1USULA TERCERA DEL PRESENTE ACUERDO DE VOLUNTADES, DURANTE LA TOTALIDAD DEL PERIODO CONTRATADO EN CURSO EN QUE GOCE DE SU", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "PAQUETE DE SERVICIOS", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(42, $h, "D\xC9CIMA. CAMBIO DE PAQUETE DE SERVICIOS.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(139, $h, "LA CLASE,  TIPO  Y  MODALIDAD  DEL PAQUETE DE SERVICIOS ELEGIDA  AL SUSCRIBIR EL PRESENTE CONTRATO, PODR\xC1 SER MOTIVO DE CAMBIO, SIENDO", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(57, $h, "NECESARIO PARA LA PROCEDENCIA DE DICHO ACTO, QUE EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(118, $h, "CUMPLA  CON LAS NORMAS SE\xD1ALADAS EN EL PRESENTE ACUERDO DE VOLUNTADES Y LOS ESTABLECIDOS EN LOS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "REGLAMENTOS ANEXOS EL PRESENTE CONTRATO.", 0, 1);

				$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(27, $h, "D\xC9CIMA PRIMERA. ACCESO.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(60, $h, "PARA  TENER ACCESO  A  LAS INSTALACIONES DEL GIMANASIO, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(82, $h, "O SUS DEPENDIENTES  DEBER\xC1N REGISTRAR SU ENTRADA Y SALIDA DEL MISMO,", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(75, $h, " A TRAVES DEL SISTEMA O LA APLICACI\xD3N PUESTOS  EN  FUNCIONAMIENTO POR ");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18, $h, '"EL PRESTADOR";');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(19, $h, "EN  TAL  VIRTUD,  EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(86, $h, " SE COMPROMETE A OTORGAR TODAS LAS FACILIDADES", 0 , 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(27, $h, "QUE AL EFECTO REQUIERA EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(14, $h, '"PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(81, $h, "PARA  EL REGISTRO Y CAPTURA DE SUS DATOS.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(18, $h, "COMO DATOS DEL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(144, $h, "O SUS DEPENDIENTES, DEBER\xC1 ENTENDERSE: NOMBRE, DIRECCION, TELEFONOS, DIRECCION ELECTR\xD3NICA, CURP, COPIA  DE  IDENTIFICACI\xD3N  OFICIAL;  MISMOS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(77, $h, "QUE DEBER\xC1N OTORGARSE AL PERSONAL DE VENTAS DE LA SOCIEDAD DENOMINADA");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(104, $h, '"DEXFIT", SOCIEDAD '."AN\xD3NIMA".' DE CAPITAL VARIABLE.', 0,1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(40, $h, "D\xC9CIMA SEGUNDA. DEL USUARIO TITULAR.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(3, $h, "EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(20, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(118, $h, "SER\xC1  EL  UNICO  FACULTADO  PARA  LLEVAR  A  CABO LA TRAMITACION DE CAMBIOS DE CLASE, TIPO O MODALIDAD DE SERVICIOS,", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "AUSENCIAS  TEMPORALES,  CANCELACIONES,  TERMINACIONES,  VOLUNTARIAS  Y  CUALQUIER  OTRO  TRAMITE  RELATIVO  AL PAQUETE DE SERVICIOS  CONTRATADO.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(36, $h, "D\xC9CIMA TERCERA. DE LOS INVITADOS.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(91, $h, "EN BENEFICIO DE LOS USUARIOS Y PARA EVITAR  LA  SATURACI\xD3N EN EL USO DE LA INSTALACIONES,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(38, $h, "SE RESERVA EL DERECHO DE LIMITAR EL", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(123, $h, "N\xDAMERO  DE INVITADOS  POR DIA O EN HORARIOS DETERMINADOS; EN TODOS LOS CASOS EL INVITADO DEBER\xC1 ESTAR EN COMPA\xD1\xCDA DEL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(39, $h, "O  USUARIO  DEPENDIENTE  QUE  LE  HAYA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "INVITADO OBLIG\xC1NDOSE A CUMPLIR Y A RESPETAR EL REGLAMENTO DEL GIMNASIO.", 0, 1);
		$pdf->Ln();

		$this->add_page_footer($pdf,$address,$address_n,$neighborhood);

		$this->add_page_header($pdf, "center");
		$pdf->SetLineWidth(0.1);

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(68, $h, "D\xC9CIMA CUARTA. RESPONSABILIDAD POR EL USO DE LAS INSTALACIONES.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(3, $h, "EL ");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(20, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(84, $h, "SE  COMPROMETE  Y OBLIGAN A USAR Y HACER QUE SUS INVITADOS UTILICEN LAS INSTALACIONES,", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(40, $h, "EQUIPOS Y DEM\xC1S  BIENES  PROPIEDAD DEL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(15, $h, '"PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(181, $h, "DE  ACUERDO  A  SU  NATURALEZA,  SIGUIENDO  EN  TODO  MOMENTO  LAS  INSTRUCCIONES  DE SU USO QUE CONSTEN EN LOS MISMOS O EN ", 0, 1);///////
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "QUE  LES SEAN SE\xD1ALADAS POR EL PERSONAL DEL GIMANASIO; EN TAL VIRTUD, EL USO DE TODAS LAS INSTALACIONES, EQUIPOS Y DEM\xC1S, LO REALIZARAN BAJO SU \xDANICA Y EXCLUSIVA RESPONSABILIDAD.", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(63, $h, "EN EL SUPUESTO DE QUE SE LES DE MAL USO A DICHOS BIENES, LOS");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(22, $h, '"USUARIOS TITULARES"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(72, $h, "  Y  SUS  DEPENDIENTES  SER\xC1N  RESPONSABLES   DE  LOS DA\xD1OS  Y PERJUICIOS  QUE SUFRIEREN   EN ", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(65, $h, "SUS PERSONAS O PROVOCADOS A TERCERAS PERSONAS, LIBERANDO A");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(81, $h, "DE CUALQUIER RESPONSABILIDAD POR LOS DA\xD1OS Y PERJUICIOS OCASIONADOS.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(46, $h, "D\xC9CIMA QUINTA. SEGURO DE RESPONSABILIDAD.-", "B", 0, "R");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18, $h, '"EL   PRESTADOR');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(118, $h, "TIENE  CONTRATADO Y EN TODO MOMENTO  MANTENDRA VIGENTE, UN SEGURO DE ACCIDENTES PERSONALES COLECTIVO, EL CUAL", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(63, $h, "RESPONDER\xC1  POR  ACCIDENTES  PERSONALES  SUFRIDOS  POR  LOS");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(22, $h, '"USUARIOS TITULARES"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(96, $h, "Y  SUS  DEPENDIENTES,  ASI  COMO  VISITANTES.  SEGURO  REPORTADO  Y  ACEPTADO  POR  LA  SOCIEDAD", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(17, $h, "CONOCIDA  COMO");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(21, $h, '"SEGUROS INBURSA",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(143, $h, "MISMO QUE ABARCAR\xC1 LOS ACCIDENTES PERSONALES SUFRIDOS EN EL CORRECTO USO DE LAS INSTALACIONES Y EQUIPOS.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(52, $h, "D\xC9CIMA SEXTA. MANIFESTACI\xD3N DE ESTADO DE SALUD.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(37.5, $h, "A LA FIRMA DEL PRESENTE CONTRATO EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18.5, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(71, $h, "MANIFIESTA BAJO PROTESTA DE DECIR VERDAD, QUE TANTO \xC9L MISMO, COMO", 0, 1);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(70, $h, "SUS INVITADOS, GOZAN DE BUEN ESTADO DE SALUD, POR  LO  QUE LIBERA A");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL  PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(180, $h, "DE CUALQUIER RESPONSABILIDAD DERIVADA DE LA AFECTACION A SU SALUD QUE  POR  EL  USO  DE  LAS  ", 0, 1);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "INSTALACIONES  Y  EQUIPO  SE  GENERE.", 0, 1);
		//$pdf->Cell(5, $h, "");
		//$pdf->Cell(142, $h, "ALBERCA, ADEM\xC1S DE PERMITIR, \xC9L SUS DEPENDIENTES E INVITADOS,  UNA  REVISI\xD3N  DE  SU  ESTADO  DE  SALUD  E  HIGIENE   ( CADA  VEZ  QUE  LO  SOLICITE");
		//$pdf->SetFont("Arial", "B", 5);
		//$pdf->Cell(16, $h, '"EL PRESTADOR"');
		//$pdf->SetFont("Arial", "", 5);
		//$pdf->Cell(23, $h, ")  PARA  QUE  SE  TENGA", 0, 1);
		//$pdf->Cell(5, $h, "");
		//$pdf->Cell(181, $h, "ACCESO  A  LA  ALBERCA.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(71, $h, "D\xC9CIMA S\xC9PTIMA. EVENTOS ESPECIALES, ORGANIZACI\xD3N Y MANTENIMIENTO.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(5, $h, "LOS");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(23, $h, '"USUARIOS TITULARES",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(44, $h, " E  INVITADOS  EXPRESAMENTE  ACEPTAN  QUE,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(8, $h, "PODR\xC1 LLEVAR A CABO  ", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "LA  ORGANIZACI\xD3N  DE  TORNEOS  Y  EVENTOS  DENTRO DE LAS INSTALACIONES DEL GIMNASIO, PUDIENDO RESTRINGIR EN FORMA PARCIAL  Y  TEMPORAL EL ACCESO QUE ALGUNAS AREAS  Y  EQUIPOS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(33, $h, " DEL   GIMNASIO.  EN  TAL  VIRTUD,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(130, $h, "SE RESERVA EL DERECHO DE ESTABLECER M\xC9TODOS Y PROCEDIMINETOS PARA LA UTILIZACI\xD3N DE LAS \xC1REAS QUE POR SU NATURALEZA TIENEN", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(97, $h, "CAPACIDAD  PARA  UN  N\xDAMERO  L\xCDMITADO  DE  USUARIOS;  SIN GENERAR RESPONSABILIDAD ALGUNA PARA");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(67, $h, "EL  HECHO DE  QUE  ALG\xDAN  USUARIO  NO  PUEDA  HACER   USO   DE   LAS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "INSTALACIONES O EQUIPO, PRECISAMENTE EN EL MOMENTO EN QUE AS\xCD LO REQUIERA.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->MultiCell(181, $h, "LAS RESTRICCIONES EN EL USO DE \xC1REAS Y EQUIPOS SUJETOS A MANTENIMIENTO O EVENTOS, NO OTORGA DERECHO ALGUNO DE REDUCCI\xD3N, REEMBOLSO O BONIFICACIONES DE CUOTAS DE INSCRIPCI\xD3N O MANTENIMIENTO.");
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(46, $h, "D\xC9CIMA OCTAVA. DA\xD1OS Y P\xC9RDIDAS DE BIENES.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(52, $h, "POR NINGUN MOTIVO Y BAJO NINGUNA CIRCUNSTANCIA,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(66, $h, "SE  HACE  RESPONSABLE  DE DA\xD1OS O P\xC9RDIDAS DE BIENES O VALORES", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(19, $h, "PROPIEDAD DE LOS");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(23, $h, '"USUARIOS TITULARES",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(139, $h, " O INVITADOS.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(43, $h, "D\xC9CIMA NOVENA. D\xCDAS Y HORAS DE SERVICIO.-", "", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(140.5, $h, "EL GIMNASIO Y SUS INSTALACIONES ESTAR\xC1N ABIERTOS EN LOS D\xCDAS Y EN EL HORARIO  ESTABLECIDOS  DE  ACUERDO  AL CALENDARIO APROBADO POR", 0, 1);
		$pdf->Cell(5,$h,"");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(181, $h, "Y QUE SER\xC1  ENTREGADO  AL  USUARIO  TITULAR AL MOMENTO   DE  LA  CONTRATACION; RESPECTO A LOS HORARIOS DE APERTURA Y CIERRE DE LAS  INSTALACIONES, LOS MISMOS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "ESTAR\xC1N DEBIDAMENTE SE\xD1ALADOS EN EL ACCESO AL GIMANASIO.", 0, 1);
		$pdf->Ln();
		// AQUI VOY
		$pdf->Cell(5, $h, "");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(164, $h, "SE RESERVA EL DERECHO DE CERRAR LAS INSTALACIONES GENERALES O \xC1REAS ESPEC\xCDFICAS O RESTRINGIR LOS HORARIOS EN D\xCDAS FESTIVOS Y EN LOS D\xCDAS QUE EL MANTENIMIENTO", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "DE  INSTALACIONES  LOS  REQUIERA,  PARA LO CUAL DEBER\xC1 DAR AVISO POR LO MENOS CON 24 VEINTICUATRO HORAS  DE ANTICIPACION,  MEDIANTE  ESCRITOS QUE SE COLOQUEN EN LUGARES VISIBLES", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(30, $h, "DEL GIMNASIO. DE IGUAL FORMA,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(137, $h, "SE  RESERVA EL DERECHO DE  CERRAR LAS  INSTALACIONES DEL  GIMNASIO  POR  CAUSA  DE  FUERZA  MAYOR  O  CASO  FORTUITO;  EN  NINGUNO DE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(95, $h, "LOS SUPUESTOS ESTABLECIDOS SE REMBOLSAR\xC1 CUOTA O PAGO ALGUNO REALIZADO POR PARTE DE LOS");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(23, $h, '"USUARIOS TITULARES",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(66, $h, "DEPENDIENTES O INVITADOS.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(38, $h, "VIG\xC9SIMA. D\xCDAS Y HORAS DE SERVICIO.-", "B", 0, "R");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(180, $h, "  SE   RESERVA  EL  DERECHO   DE   CONTRATAR   Y   DESIGNAR   A   LOS PROFESORES Y ENTRENADORES QUE IMPARTIR\xC1N  LAS   CLASES,  Y", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(50, $h, "PROPORCIONAR\xC1N ENTRENAMIENTO PERSONAL A LOS");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(23, $h, '"USUARIOS TITULARES",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(108, $h, "DEPENDIENTES O VISITANTES, PUDIENDO ESTOS ELEGIR  ENTRE AQUELLOS  DISPONIBLES EN EL GIMNASIO, CON LA \xDANICA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "LIMITANTE DEL ESPACIO EN LAS \xC1REAS Y EL TIEMPO DE LOS PROFESORES Y ENTRENADORES; EN TAL VIRTUD, LOS USUARIOS NO TENDR\xC1N DERECHO A INDEMNIZACI\xD3N O RECLAMACI\xD3N ALGUNA PARA EL", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "CASO DE QUE ESTOS PROFESORES O ENTRENADORES SEAN SUSTITUIDOS.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(164, $h, " SE RESERVA EL  DERECHO DE SE\xD1ALAR  LOS  HORARIOS  Y  DURACI\xD3N  DE LAS CLASES DE GRUPO IMPARTIDAS EN EL GIMNASIO; POR LO TANTO, LOS USUARIOS NO TENDR\xC1N DERECHO", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, " A INDEMNIZACI\xD3N O RECLAMACI\xD3N ALGUNA PARA EL CASO DE QUE DICHOS PROFESORES O ENTRENADORES SEAN SUSTITUIDOS.", 0, 1);
		$pdf->Ln();
		//
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(53, $h, "VIG\xC9SIMA PRIMERA. AUSENCIA TEMPORAL VOLUNTARIA.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(3.5, $h, "EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(48, $h, " PODR\xC1N, EN UN TIEMPO MAYOR A 60 SESENTA D\xCDAS");
		//$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "NATURALES, AUSENTARSE EN LAS INSTALACIONES DEL GIMNASIO,");
		$pdf->Cell(5,$h,"");
		$pdf->Cell(181, $h, " TENIENDO LA OPCI\xD3N DE RECUPERAR EL TIEMPO DE INASISTENCIA, SIEMPRE Y CUANDO CUMPLA CON LOS REQUISITOS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "SIGUIENTES:", 0, 1);
		$pdf->Cell(11, $h, "A) ", 0, 0, "R");
		$pdf->Cell(33, $h, "DEBER\xC1 DAR AVISO POR ESCRITO A");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(121, $h, "INDICANDO EL INICIO DEL PER\xCDODO DE AUSENCIA Y SU DURACI\xD3N.", 0, 1);
		$pdf->Cell(11, $h, "B) ", 0, 0, "R");
		$pdf->Cell(171, $h, "DURANTE EL PERIODO DE AUSENCIA QUE SE PRETENDE RECUPERAR, NO SE DEBEN TENER REGISTROS DE ASISTENCIA EN NINGUNO DE LOS GIMNASIOS", 0, 1);
		//$pdf->Cell(11, $h, "");
		//$pdf->Cell(171, $h, "Y EN NINGUNO DE LOS CLUBES, DEBIDO A QUE EL TR\xC1MITE DE M\xC9RITO ES POR MEMBRES\xCDA Y NO POR USUARIO.", 0, 1);
		$pdf->Cell(11, $h, "C) ", 0, 0, "R");
		$pdf->Cell(171, $h, "NO HABER TRAMITADO DENTRO DEL A\xD1O ANTERIOR, LA RECUPERACI\xD3N DE PERIODOS DE INASISTENCIA POR AUSENCIA VOLUNTARIA.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(54, $h, "SI  AL  FIRMAR EL PRESENTE ACUERDO DE VOLUNTADES, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(108, $h, "OPT\xD3 POR DAR CUMPLIMIENTO A LA OBLIGACI\xD3N DE PAGO ESTABLECIDA EN LA CL\xC1USULA TERCERA QUE ANTECEDE, EN", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(44, $h, "FORMA  MENSUAL,  AL  MOMENTO  DE REGRESAR");
		//$pdf->SetFont("Arial", "B", 5);
		//$pdf->Cell(5, $h, '"A",');
		//$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(18, $h, 'DE LA "AUSENCIA" ');
		$pdf->Cell(181, $h, "TENDR\xC1 QUE VERIFICAR CON EL GIMNASIO QUE NO SE TENGA NINGUN REGISTRO, PRORROG\xC1NDOSE EL PER\xCDODO DE VIGENCIA DE SU ", 0, 1);
		$pdf->Cell(5, $h, "");//
		$pdf->Cell(181, $h, "MEMBRES\xCDA EN IGUAL NUMERO DE MESES. EN TAL VIRTUD, REINICI\xC1NDOSE  EL PAGO NORMAL DE SU MENSUALIDAD DE CUOTA POR  MANTENIMIENTO AL CONCLUIR LA  PR\xD3RROGA.", 0, 1);
		//$pdf->Cell(5, $h, "");
		//$pdf->Cell(181, $h, "DE LOS USUARIOS DE LA MEMBRES\xCDA AL CLUB O AL T\xC9RMINO DEL PER\xCDODO DE AUSENCIA SE\xD1ALADO EL AVISO PRESENTADO POR ESCRITO, LO QUE OCURRA PRIMERO.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(54, $h, "SI  AL FIRMAR EL PRESENTE ACUERDO DE VOLUNTADES, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(108, $h, "OPT\xD3 POR DAR CUMPLIMIENTO A LA OBLIGACI\xD3N DE PAGO ESTABLECIDA EN LA CL\xC1USULA TERCERA QUE ANTECEDE, EN", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(84, $h, "UNA S\xD3LA EXHIBICI\xD3N, ES DECIR, EN FORMA ANUAL, AL MOMENTO DE REGRESAR DE LA " . '"AUSENCIA"');
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "TENDR\xC1 QUE VERIFICAR CON EL GIMNASIO QUE NO SE TENGA NINGUN Y SE PRORROGARA LA VIGENCIA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "DE SU ANUALIDAD EN EL MISMO NUMERO DE MESES.", 0, 1);
		/*
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(5, $h, '"A",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(54, $h, "DEBER\xC1  REALIZAR  EL PAGO DEL 33% TREINTA Y TRES POR", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "CIENTO  DE  LA  CUOTA  DE MANTENIMINETO QUE LE CORRESPONDE A SU TIPO DE MEMBRES\xCDA, POR CADA MES DE AUSENCIA Y SE PRORROGARA LA VIGENCIA DE SU ANUALIDAD EN EL MISMO NUMERO DE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "MESES.", 0, 1);
		*/
		$pdf->Ln();
		/*
		$pdf->Cell(5, $h, "");
		$pdf->Cell(140, $h, "SI EL USUARIO TITULAR NO REALIZA LOS PAGOS ADICIONALES ANTES DESCRITOS, EXTENDI\xC9NDOSE LA AUSENCIA POR M\xC1S DE 90 NOVENTA D\xCDAS NATURALES,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(24, $h, "TENDR\xC1 LA FACULTAD DE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(47, $h, "DAR DE BAJA DICHA MEMBRES\xCDA, EN CUYO CASO EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(115, $h, "PODR\xC1  REACTIVAR  LA  MISMA,  PREVIO  PAGO  DE  LA  CUOTA DE  REACTIVACI\xD3N VIGENTE AL MOMENTO DE HACER EL TR\xC1MITE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "CORRESPONDIENTE.", 0, 1);
		$pdf->Ln();
		*/
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(45.5, $h, "VIG\xC9SIMA SEGUNDA. TERMINACI\xD3N VOLUNTARIA.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(3, $h, "EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(113.5, $h, "ES  EL  \xDANICO  FACULTADO PARA REALIZAR LA RESCISI\xD3N DEL PRESENTE CONTRATO EN FORMA VOLUNTARIA, PARA  LO  CUAL,", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(33, $h, "DEBER\xC1 DAR AVISO POR ESCRITO A");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(131, $h, "POR LO MENOS CON 30 TREINTA DIAS NATURALES DE ANTICIPACI\xD3N, HACIENDO DEVOLUCION Y ENTREGA DE LOS BIENES QUE SON PROPIEDAD DE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(164, $h, "Y QUE LE FUER\xC1N ENTREGADOS PARA QUE LOS USUARIOS DE LA MEMBRES\xCDA TUVIER\xC1N ACCESO E HICIER\xC1N USO DE LAS INSTALACIONES Y EQUIPOS.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(108, $h, "SI AL FIRMARSE EL CONTRATO SE OPT\xD3 POR EL PAGO DE CONTADO DE LA CUOTA DE MANTENIMIENTO EN FORMA ANUAL,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(56, $h, "REALIZAR\xC1 EL REEMBOLSO, \xDANICAMENTE, DE LAS CUOTAS DE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "MANTENIMIENTO  QUE  PROPORCIONALMENTE  CORRESPONDAN AL PER\xCDODO NO UTILIZADO, PREVIA DEDUCCI\xD3N DE UN 20% VEINTE POR CIENTO SOBRE LA CANTIDAD NO DEVENGADA, POR CONCEPTO DE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "GASTOS ADMINISTRATIVOS; POR NING\xDAN MOTIVO SE HAR\xC1 REEMBOLSO DE CUOTAS DE INSCRIPCI\xD3N.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(46, $h, "VIG\xC9SIMA TERCERA. SUSPENCI\xD3N DE USUARIOS.-", "B", 0, "R");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(180, $h, "SE  RESERVA  EL  DERECHO  DE  SUSPENDER  EN  FORMA  TEMPORAL  O DEFINITIVA, MEDIANTE LA CANCELACI\xD3N DEL PAQUETE DE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(23, $h, " SERVICIOS, A CUALQUIER");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(136, $h, "QUE INCUMPLA CON LAS  OBLIGACIONES  ASUMIDAS EN EL CONTRATO, O QUE VIOLE LO ESTABLECIDO EN EL REGLAMENTO DEL GIMNASIO; EN EL CASO DE", 0, 1);
		//$pdf->SetFont("Arial", "B", 5);
		//$pdf->Cell(14, $h, '"MEMBRES\xCDAS', 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(25, $h, '"SUSCRIPCIONES PREMIUM"');
		//$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(168, $h, " LAS SANCIONES  PREVISTAS  EN  EL  PRESENTE CONTRATO SE  HACEN  EXTENSIVAS A LAS  CONDUCTAS REALIZADAS EN CUALQUIERA  DE LOS GIMNASIOS  A  LOS  QUE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "INGRESEN LOS USUARIOS INFRACTORES.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(124, $h, "EN CASO DE QUE ALG\xDAN USUARIO INCURRA EN UNA VIOLACI\xD3N DEL CONTRATO O DE SU REGLAMENTO QUE SEA CONSIDERADA NO GRAVE,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(40, $h, "LE   REALIZAR\xC1  UNA   AMONESTACI\xD3N  POR", 0, 1);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "ESCRITO  EN  LA  CUAL  CONSTE  LA  VIOLACI\xD3N  COMETIDA, EN  DICHA  AMONESTACI\xD3N  SE  LE  APERCIBIR\xC1 DE QUE EN CASO DE REINCIDENCIA, PODR\xC1 SER SUSPENDIDO TEMPORALMENTE O PODR\xC1 SER", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "CANCELADA SU SUSCRIPCI\xD3N; EN CASO DE QUE LA FALTA SEA  CONSIDERADA  GRAVE, EL AVISO ESCRITO SER\xC1 EL DE SUSPENSI\xD3N, YA SEA, TEMPORALMENTE, CANCELACI\xD3N Y/O BAJA DE LA SUSCRIPCI\xD3N.", 0, 1);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "EN  FORMA  ENUNCIATIVA Y NO LIMITATIVA, SE ENUMERAN  LAS  SIGUIENTES CAUSAS  DE AMONESTACI\xD3N, LAS CUALES DAR\xC1N LUGAR A LA SUSPENSI\xD3N TEMPORAL O A LA CANCELACI\xD3N DEFINITIVA DE LA", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "SUSCRIPCI\xD3N:", 0, 1);
		$pdf->Ln();

		$pdf->Cell(11, $h, "1)", 0, 0, "R");
		$pdf->Cell(171, $h, "INCUMPLIR CON CUALQUIER OBLIGACI\xD3N ASUMIDA EN EL CONTRATO O EN EL REGLAMENTO.", 0, 1);
		$pdf->Cell(11, $h, "2)", 0, 0, "R");
		$pdf->Cell(171, $h, "OMITIR EL PAGO OPORTUNO DE CUALQUIER CUOTA O CARGO DEL PAQUETE DE SERVICIOS CONTRATADO.", 0, 1);
		$pdf->Cell(11, $h, "3)", 0, 0, "R");
		$pdf->Cell(171, $h, "NO DAR LAS FACILIDADES REQUERIDAS PARA SU REGISTRO COMO USUARIO DE GIMNASIO.", 0, 1);
		$pdf->Cell(11, $h, "4)", 0, 0, "R");
		$pdf->Cell(171, $h, "EVADIR EN FORMA ALGUNA LOS SISTEMAS DE REGISTRO DE INGRESO Y EGRESO DE LAS INSTALACIONES DEL GIMANASIO.", 0, 1);
		$pdf->Cell(11, $h, "5)", 0, 0, "R");
		$pdf->Cell(135, $h, "OTORGAR O RECIBIR ENTRENAMIENTOS PERSONALES ENTRE USUARIOS, ES DECIR, CON INDEPENDENCIA DE LOS ENTRENADORES RECONOCIDOS POR");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(36, $h, '"EL PRESTADOR".', 0, 1);
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(11, $h, "6)", 0, 0, "R");
		$pdf->Cell(132, $h, "COMERCIALIZAR CUALQUIER TIPO DE PRODUCTOS EN EL INTERIOR DEL GIMNASIO, O EN EL AREA DE ESTACIONAMIENTO, CUANDO SEA PROPIEDAD DE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(42, $h, '"EL PRESTADOR".', 0, 1);
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(11, $h, "7)", 0, 0, "R");
		$pdf->Cell(32, $h, "NO HACER DEL CONOCIMIENTO DE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(122, $h, "EL  PADECIMIENTO   DE  ALGUNA  ENFERMEDAD   INFECCIOSA,  CONTAGIOSA  O  QUE  POR  SU  NATURALEZA  PUEDA  CAUSAR  DA\xD1OS  O", 0, 1);
		$pdf->Cell(11, $h, "");
		$pdf->Cell(171, $h, "PERJUICIOS A TERCEROS, CONTINUANDO CON EL USO DE LAS INTALACIONES Y EQUIPOS DEL GIMNASIO.", 0, 1);
		$pdf->Cell(11, $h, "8)", 0, 0, "R");
		$pdf->Cell(171, $h, "VIOLAR LAS REGLAS DE USO Y ACCESO A CADA UNA DE LAS DIFERENTES INSTALACIONES DEL GIMNASIO.", 0, 1);
		$pdf->Cell(11, $h, "9)", 0, 0, "R");
		$pdf->Cell(171, $h, "INTRODUCIR AL GIMNASIO COMIDA, VASOS, BOTELLAS O CUALQUIER OBJETO DE VIDRIO.", 0, 1);
		$pdf->Cell(11, $h, "10)", 0, 0, "R");
		$pdf->Cell(171, $h, "FUMAR FUERA DE LAS \xC1REAS PERMITIDAS PARA ELLO.", 0, 1);
		$pdf->Cell(11, $h, "11)", 0, 0, "R");
		$pdf->Cell(171, $h, "CIRCULAR EN \xC1REAS GENERALES DEL GIMNASIO SIN ZAPATOS, SIN CAMISETA O EN FORMA INAPROPIADA PARA LLEVAR A CABO LA ACTIVIDAD A LA QUE EST\xC1N DESTINADAS LAS \xC1REAS DEL GIMNASIO.", 0, 1);
		$pdf->Cell(11, $h, "12)", 0, 0, "R");
		$pdf->Cell(171, $h, "VIOLAR LA REGLAMENTACI\xD3N RELATIVA AL INGRESO Y ESTANCIA DE NI\xD1OS Y PERSONAS CON CAPACIDADES DIFERENTES Y POBLACI\xD3N ESPECIAL.", 0, 1);
		$pdf->Cell(11, $h, "13)", 0, 0, "R");
		$pdf->Cell(171, $h, "VIOLAR LOS HORARIOS DE SALIDA O ESTANCIA EN EL GIMNASIO.", 0, 1);
		$pdf->Ln();

		$this->add_page_footer($pdf,$address,$address_n,$neighborhood);
		// here
		$this->add_page_header($pdf, "center");

		$pdf->SetFont("Arial", "", 5);


		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "EN FORMA ENUNCIATIVA Y NO LIMITATIVA SE ENUMERAN LAS SIGUIENTES CAUSAS DE SUSPENSI\xD3N TEMPORAL POR 30 TREINTA D\xCDAS NATURALES:", 0, 1);
		$pdf->Ln();

		$pdf->Cell(11, $h, "1)", 0, 0, "R");
		$pdf->Cell(65, $h, "AGREDIR FISICA O VERBALMENTE A OTRO USUARIO O  AL  PERSONAL  DE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(106, $h, '"EL PRESTADOR".', 0, 1);
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(11, $h, "2)", 0, 0, "R");
		$pdf->Cell(171, $h, "PARTICIPAR COMO SUJETO PROVOCADOR O ACTIVO, EN UNA RI\xD1A EN EL INTERIOR DE LAS INSTALACIONES DEL GIMNASIO.", 0, 1);
		$pdf->Cell(11, $h, "3)", 0, 0, "R");
		$pdf->Cell(171, $h, "COMETER ALGUNA CONDUCTA QUE PUEDA SER TIPIFICADA COMO DELITO.", 0, 1);
		$pdf->Cell(11, $h, "4)", 0, 0, "R");
		$pdf->Cell(171, $h, "REALIZAR ACTOS CONTRARIOS A LA MORAL, A LAS BUENAS CONSTUMBRES O INAPROPIADOS AL INTERIOR DEL GIMNASIO.", 0, 1);
		$pdf->Cell(11, $h, "5)", 0, 0, "R");
		$pdf->Cell(171, $h, "PORTAR ARMAS DE CUALQUIER TIPO DENTRO DEL GIMNASIO.", 0, 1);
		$pdf->Cell(11, $h, "6)", 0, 0, "R");
		$pdf->Cell(171, $h, "ASISTIR BAJO EL EFECTO O USAR DENTRO DEL GIMNASIO BEBIDAS ALCOH\xD3LICAS, TOXICAS, ENERVANTES O DROGAS DE CUALQUIER TIPO.", 0, 1);
		$pdf->Cell(11, $h, "7)", 0, 0, "R");
		$pdf->Cell(171, $h, "INTRODUCIR, DISTRIBUIR, COMERCIALIZAR, PORTAR, Y CUALQUIER OTRO TIPO DE ACCI\xD3N, DENTRO DE LAS INTALACIONES DEL GIMNASIO, BEBIDAS ALCOH\xD3LICAS, TOXICAS, ENERVANTES O DROGAS", 0, 1);
		$pdf->Cell(11, $h, "");
		$pdf->Cell(171, $h, "DE CUALQUIER TIPO.", 0, 1);
		$pdf->Cell(11, $h, "8)", 0, 0, "R");
		$pdf->Cell(171, $h, "PROPORCIONAR DATOS FALSOS O INEXACTOS AL REALIZAR CUALQUIER TR\xC1MITE EN EL GIMNASIO.", 0, 1);
		$pdf->Cell(11, $h, "9)", 0, 0, "R");
		$pdf->Cell(171, $h, "REINCIDIR EN LA VIOLACI\xD3N DEL HORARIO DE SALIDA O ESTANCIA EN EL GIMNASIO.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(36, $h, "EN CUALQUIER CASO DE SUSPENSI\xD3N,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(128, $h, "DEBER\xC1  DAR  AVISO  POR  ESCRITO  EN  EL  CUAL SE ESPECIFIQUE LA CAUSA DE LA SUSPENSI\xD3N; Y, TRAT\xC1NDOSE DE SUSPENSI\xD3N TEMPORAL", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "DEBER\xC1  ASENTAR  EL  PER\xCDODO  DE  LA  MISMA", 0, 1);
		//$pdf->Cell(5, $h, "");
		//$pdf->Cell(171, $h, "VIOLACI\xD3N,  PERMITI\xC9NDOSE  EL  ACCESO  A  LOS  DEM\xC1S  USUARIOS  DE  DICHA  MEMBRES\xCDA,  A  NO SER QUE EL USUARIO  QUE HUBIERE INCURRIDO EN LA CONDUCTA SANCIONABLE FUERA EL");
		//$pdf->SetFont("Arial", "B", 5);
		//$pdf->Cell(10, $h, '"USUARIO', 0, 1);
		//$pdf->Cell(5, $h, "");
		//$pdf->Cell(10, $h, 'TITULAR",');
		//$pdf->SetFont("Arial", "", 5);
		//$pdf->Cell(171, $h, "EN TAL SUPUESTO, LA SUSPENSI\xD3N AFECTAR\xC1 A LA TOTALIDAD DE BENEFICIARIOS DE DICHA MEMBRES\xCDA.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->MultiCell(181, $h, "EL AVISO POR ESCRITO SE REALIZAR\xC1 MEDIANTE ENTREGA EN EL DOMICILIO REGISTRADO A NOMBRE DEL USUARIO TITULAR O EN LAS INSTALACIONES DEL GIMNASIO, SI ES QUE \xC9STE ES LOCALIZADO EN LAS MISMAS; TAMBI\xC9N PODR\xC9  REALIZARSE V\xCDA CORREO  ELECTR\xD3NICO O FAX.");
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(50, $h, "EN CASO DE SUSPENSI\xD3N TEMPORAL O CANCELACI\xD3N,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(114, $h, "NO REALIZAR\xC1 REEMBOLSO ALGUNO POR CONCEPTO DE CUOTAS DE INSCRIPCI\xD3N O MANTENIMIENTO.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "EL  USUARIO  QUE  HAYA  SIDO  SUSPENDIDO  DEFINITIVAMENTE  O  AL QUE SE LE CANCELE  SU  MEMBRES\xCDA, NO PODR\xC1 SER  REGISTRADO  COMO  USUARIO  TITULAR EN NINGUNA DE LAS INSTALACIONES", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(12, $h, "QUE MANEJA");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(154, $h, '"EL PRESTADOR".', 0, 1);
		$pdf->Ln();
		//
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(75, $h, "VIG\xC9SIMA CUARTA. DE LA TERMINACI\xD3N ANTICIPADA Y RESCISI\xD3N DEL CONTRATO.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(87, $h, "CON INDEPENDIENCIA DE LAS CAUSAS Y SANCIONES ESTABLECIDAS EN LA CL\xC1USULA ANTERIOR,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(16, $h, '"EL PRESTADOR"', 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(3, $h, "SE");
		$pdf->Cell(74, $h, "RESERVA EL DERECHO DE RESCINDIR O DAR POR TERMINADO EL CONTRATO SI EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(88, $h, "INCURRE EN ALGUN INCUMPLIMIENTO  EN  SUS OBLIGACIONES DERIVADAS DEL PRESENTE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "ACUERDO DE VOLUNTADES, DEL REGLAMENTO INTERNO DEL GIMNASIO O DE LAS NORMAS DE USO DE LAS INSTALACIONES.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(41, $h, "EN CASO DE INCUMPLIMIENTO POR PARTE DE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(22, $h, '"EL USUARIO TITULAR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(37, $h, "RECISI\xD3N DEL CONTRATO POR PARTE DE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(17, $h, '"EL PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(64, $h, "O CANCELACI\xD3N  DEL  CONTRATO POR LAS FALTAS CONTENIDAS EN LA", 0, 1);

		$pdf->Cell(5, $h, "");
		$pdf->Cell(29, $h, "CL\xC1USULA VIG\xC9SIMA TERCERA,");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(22, $h, '"EL USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(62, $h, "SE OBLIGA A CUBRIR UNA PENA CONVENCIONAL DE LA CANTIDAD DE");
		$pdf->Cell(68, $h, $info["PENALIDAD"], "B", 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(18, $h, "CORRESPONDE AL");
		$pdf->Cell(6, $h, $info["PORCIENTO"], "B");
		$pdf->Cell(157, $h, "% DEL COSTO TOTAL DE UNA ANUALIDAD DE CUOTAS DE MANTENIMIENTO.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(68.5, $h, "VIG\xC9SIMA QUINTA. DEL MANTENIMIENTO Y REACTIVACI\xD3N DE MEMBRES\xCDAS.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(84.5, $h, "AL  T\xC9RMINO   DEL  PER\xCDODO   CORRESPONDIENTE   AL  MANTENIMIENTO  ANUAL   PAGADO,  EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(9, $h, "DEBER\xC1", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(161, $h, "CONTINUAR  CON  LOS  PAGOS  DE  LAS  NUEVAS  CUOTAS  DE  MANTENIMIENTO  QUE  SE  ENCUENTRAN  VIGENTES, SI ES QUE DESEA PRORROGAR SU CONTRATO POR UN A\xD1O M\xC1S;");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(16, $h, '"EL PRESTADOR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(3, $h, "SE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(99, $h, "RESERVA  EL  DERECHO DE MODIFICAR LOS TIPOS, CLASES Y MODALIDADES DE SUSCRIPCIONES, POR LO CUAL EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(63, $h, "NO  TENDR\xC1  DERECHO  A  QUE  SE  MANTENGA SU SUSCRIPCI\xD3N EN EL", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(65, $h, "MISMO TIPO, CLASE Y MODALIDAD, SI ES QUE LA MISMA YA NO EXISTE. EL ");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(97, $h, "TENDR\xC1 EL DERECHO DE DAR POR TERMINADO SU CONTRATO, SI NO DESEA MANTENER LA VIGENCIA DE SU", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "SUSCRIPCI\xD3N EN ALGUNO DE LOS NUEVOS TIPOS, CLASES Y MODALIDAES VIGENTES.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "EN AQUELLOS CASOS DONDE LA MEMBRES\xCDA HAYA SIDO DADA DE BAJA, POR  CUALQUIER CAUSA DISTINTA DE LAS SE\xD1ALADAS COMO VIOLACIONES GRAVES EN LA CL\xC1USULA VIG\xC9SIMA TERCERA DE ESTE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(14, $h, "CONTRATO, EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(19, $h, '"USUARIO TITULAR"');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(102, $h, "PODR\xC1 TRAMITAR LA REACTIVACI\xD3N DE LA MISMA, MEDIANTE EL PAG\xD3 DE LA CUOTA QUE AL EFECTO ESTABLEZCA");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(46, $h, '"EL PRESTADOR".', 0, 1);
		$pdf->Ln();
		/*
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(33, $h, "VIG\xC9SIMA SEXTA. ENCABEZADOS.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(148, $h, "PARA  EL  CUMPLIMIENTO  DE  LAS  OBLIGACIONES  DERIVADAS  DEL  PRESENTE  ACUERDO  DE  VOLUNTADES  Y  EL REGLAMENTO INTERNO DEL CLUB, LOS USUARIOS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(35, $h, "DEPENDIENTES DESIGNADOS  POR  EL");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(20, $h, '"USUARIO TITULAR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(54, $h, "SE CONSTITUYEN EN OBLIGADOS SOLIDARIOS A  FAVOR  DE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(18, $h, '"EL PRESTADOR",');
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(54, $h, "SUBSISTIENDO  DICHAS  OBLIGACI\xD3NES,  HASTA  SU  TOTAL", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "CUMPLIMIENTO. EN  TAL  VIRTUD, SER\xC1N  OBLIGADOS  SOLIDARIOS,  TANTO DEL PAGO DE LAS CUOTAS DE INSCRIPCI\xD3N Y MANTENIMIENTO, AS\xCD COMO DE LA RESPONSABILIDAD DERIVADA DEL MAL USO DE", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(43, $h, "LAS INSTALACIONES Y EQUIPOS PROPIEDAD DE");
		$pdf->SetFont("Arial", "B", 5);
		$pdf->Cell(138, $h, '"EL PRESTADOR".', 0, 1);
		$pdf->Ln();
		*/
		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(34, $h, "VIG\xC9SIMA SEXTA. ENCABEZADOS.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(147, $h, "LOS  ENCABEZADOS  DE  LAS  CL\xC1USULAS  TIENEN  LA  EXCLUSIVA  FINALIDAD  DE  FACILITAR  SU  LECTURA  Y NO TENDR\xC1N EL EFECTO DE MODIFICAR O AFECTAR EL", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "CONTENIDO, T\xC9RMINOS Y CONDICIONES DE LAS MISMAS.", 0, 1);
		$pdf->Ln();

		$pdf->SetFont("Arial", "BU", 5);
		$pdf->Cell(5,$h,"");
		$pdf->Cell(49, $h, "VIG\xC9SIMA S\xCPTIMA. JURISDICCI\xD3N Y COMPETENCIA.-", "B", 0, "R");
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(133, $h, "CONVIENEN  EXPRESAMENTE  LAS  PARTES,  QUE  PARA  LA  INTERPRETACI\xD3N  Y  CUMPLIMIENTO  DEL  PRESENTE  CONTRATO SE SOMETER\xC1N A LOS", 0, 1);
		$pdf->Cell(5, $h, "");
		$pdf->Cell(181, $h, "TRIBUNALES DEL PRIMER PARTIDO JUDICIAL EN EL ESTADO DE JALISCO, RENUNCIANDO AL FUERO DE CUALQUIER OTRO DOMICILIO PRESENTE O FUTURO QUE LLEGARE A CORRESPONDERLES.", 0, 1);
		$pdf->Ln();

		$pdf->Cell(5, $h, "");
		$pdf->MultiCell(181, $h, "LEIDO EL PRESENTE ACUERDO DE VOLUNTADES, LO FIRMAN LOS COMPARECIENTES, EN LA CIUDAD DE ".strtoupper($city).", ".strtoupper($state).", A LOS ".$info["DIA"]." D\xCDAS DEL  MES DE ".$info["MES"]." DEL A\xD1O ".$info["ANIO"].".");
		$pdf->Ln($h*5);

		$pdf->Cell(65, $h, "");
		$pdf->Cell(61, $h, "", "B", 1, "C");
		$pdf->Ln(0.7);
		$pdf->Cell(65, $h, "");
		$pdf->SetFont("Arial", "B", 5);
		if ($tipo !=null || $tipo != ""){
			if($gender=="H"){
				$pdf->Cell(61, $h, strtoupper($tipo)." ".strtoupper($name).",", 0, 1, "C");
				$pdf->Cell(65, $h, "");
				$pdf->Cell(61, $h, 'APODERADO LEGAL DE "DEXFIT",', 0, 1, "C");
			}else if ($gender=="M"){
				$pdf->Cell(61, $h, strtoupper($tipo)." ".strtoupper($name).",", 0, 1, "C");
				$pdf->Cell(65, $h, "");
				$pdf->Cell(61, $h, 'APODERADA LEGAL DE "DEXFIT",', 0, 1, "C");
			}else{
				$pdf->Cell(61, $h, strtoupper($tipo)." ".strtoupper($name).",", 0, 1, "C");
				$pdf->Cell(65, $h, "");
				$pdf->Cell(61, $h, 'APODERADO/A LEGAL DE "DEXFIT",', 0, 1, "C");
			}
		}else{
			if($gender=="H"){
				$pdf->Cell(61, $h, "SE\xD1OR ".strtoupper($name).",", 0, 1, "C");
				$pdf->Cell(65, $h, "");
				$pdf->Cell(61, $h, 'APODERADO LEGAL DE "DEXFIT",', 0, 1, "C");
			}else if ($gender=="M"){
				$pdf->Cell(61, $h, "SE\xD1ORA ".strtoupper($name).",", 0, 1, "C");
				$pdf->Cell(65, $h, "");
				$pdf->Cell(61, $h, 'APODERADA LEGAL DE "DEXFIT",', 0, 1, "C");
			}else{
				$pdf->Cell(61, $h, "SE\xD1OR/A ".strtoupper($name).",", 0, 1, "C");
				$pdf->Cell(65, $h, "");
				$pdf->Cell(61, $h, 'APODERADO/A LEGAL DE "DEXFIT",', 0, 1, "C");
			}
		}
		$pdf->Cell(65, $h, "");
		$pdf->Cell(61, $h, "SOCIEDAD AN\xD3NIMA DE CAPITAL VARIABLE.", 0, 1, "C");
		$pdf->Ln($h*4);

		$pdf->Cell(65, $h, "");
		$pdf->Cell(7, $h, "SR(A).");
		$pdf->Cell(56, $h, $info["TITULAR"], "B");
		$pdf->Cell(2, $h, ".", 0, 1);

		$this->add_page_footer($pdf,$address,$address_n,$neighborhood);

		$pdf->Output("Dexfit - Contrato.pdf", "D");
		exit;
	}

	function pdf_contrato($info=array()){
		//die(print_r($info));
		$member_checks = array(
			'Basic'	=> '',
			'Light' => '',
			'Premium' => '',
			'Full Experience' => ''
		);

		$costo_checks = array(
			'Basic'	=> '       ',
			'Light' => '       ',
			'Premium' => '       ',
			'Full Experience' => '       '
		);

		foreach ($member_checks as $k => $v) {
			if ($k == $info['TIPOMEMBRESIA']){
				$member_checks[$k] = 'X';

				if ($info['PERIODO'] == 'Semestral' || $info['PERIODO'] == 'Anual'){
					$costo_checks[$k] = $info['quote_anualidad'];
				}
				else{
					$costo_checks[$k] = $info['quote_mensualidad'];
				}
			}
		}

		$type_checks = array(
			'M'   => '',
			'MD'  => '',
			'MDA' => '',
			'S'   => '',
			'A'   => '',
			'O'   => ''
		);

		if ($info['PERIODO'] == 'Mensual'){
			$type_checks['M'] = 'X';
		}
		elseif ($info['PERIODO'] == 'Mensual Domiciliado Libre') {
			$type_checks['MD'] = 'X';
		}
		elseif ($info['PERIODO'] == 'Mensual Domiciliado 1 Año') {
			$type_checks['MDA'] = 'X';
		}
		elseif ($info['PERIODO'] == 'Semestral') {
			$type_checks['S'] = 'X';
		}
		elseif ($info['PERIODO'] == 'Anual') {
			$type_checks['A'] = 'X';
		}
		else{
			$type_checks['O'] = 'X';
		}

		date_default_timezone_set("America/Mexico_City");
		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm'); //Orientacion-milimetros-size(A4)
		$this->contract_atributes($pdf, 'Contrato');
		$pdf->AddPage('P', array(216.3, 279.8)); //Orientacion,tamaño, rotacion
		$pdf->SetMargins(10, 10, 5); //izquierda,arriba,derecha
		$pdf->SetFont('Arial', 'B', 12);
		$path = realpath(dirname(__FILE__) . '/../../assets/images');
		$img1 = $path . DIRECTORY_SEPARATOR . 'logo_dexfit.PNG';
		$pdf->Image($img1, 100, 0, 0);
		$pdf->SetXY(24, 20);
		$pdf->SetFillColor(255, 192, 0);
		$pdf->SetXY(20, 3);
		$pdf->Cell(0, 32, utf8_decode('"CARÁTULA DE CONTRATO"'), 0, 1, 'C');
		$pdf->SetXY(24, 35);
		$pdf->SetFillColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY(10, 25);
		$pdf->Cell(15, 5, utf8_decode('No. de Socio: '), 0, 0, 'C');
		$pdf->SetXY(35, 25);
		$pdf->SetFont('Arial', 'BU');
		$pdf->Cell(15, 5, utf8_decode(str_pad($info['FOLIO'], 25, " ", STR_PAD_BOTH)), 0, 0, 'C');
		$pdf->SetXY(75, 25);
		$pdf->SetFont('Arial', 'B');
		$pdf->Cell(15, 5, utf8_decode('Fecha de Firma del Contrato: '), 0, 0, 'C');
		$pdf->SetXY(115, 25);
		$pdf->SetFont('Arial', 'BU');
		$pdf->Cell(15, 5, utf8_decode(str_pad($info['FECHA'], 25, " ", STR_PAD_BOTH)), 0, 0, 'C');
		//$pdf->SetXY(150, 25);
		//$pdf->SetFont('Arial', 'B');
		//$pdf->Cell(15, 5, utf8_decode('No. de Contrato: '), 0, 0, 'C');
		//$pdf->SetXY(175, 25);
		//$pdf->SetFont('Arial', 'BU');
		//$pdf->Cell(15, 5, utf8_decode(str_pad($info['FOLIO'], 25, " ", STR_PAD_BOTH)), 0, 1, 'C');
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetXY(63, 30);
		$pdf->Cell(15, 5, utf8_decode('I. GENERALES DEL USUARIO TITULAR, PAQUETE CONTRATADO Y MODALIDAD DE PAGO. '), 0, 1, 'C');
		$pdf->SetXY(10, 38);
		$pdf->SetFillColor(64,64,64);
		$pdf->SetTextColor(238, 180, 5);
		$pdf->Cell(0, 5, utf8_decode('INFORMACIÓN Y DATOS PERSONALES'), 1, 1, 'C',true);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(10, 43);
		$pdf->Cell(67, 5, utf8_decode('Nombre (s): '.$info['first_name']), 1, 0, 'L',true);
		$pdf->Cell(67, 5, utf8_decode('Apellidos: '."{$info['last_name']} {$info['second_last_name']}"), 1, 0, 'L',true);
		$pdf->Cell(67, 5, utf8_decode('Fecha de nacimiento: '.$info['birthday']), 1, 1, 'L',true);
		$pdf->Cell(0, 10, utf8_decode('Identificación:'), 1, 0, 'L',true);
		$pdf->SetXY(32, 50);
		$pdf->Cell(5, 5, utf8_decode(''), 1, 0, 'L',true);
		$pdf->SetXY(37, 50);
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(5, 5, utf8_decode('IFE/INE'), 0, 0, 'L',true);
		$pdf->SetXY(50, 50);
		$pdf->Cell(5, 5, utf8_decode(''), 1, 0, 'L',true);
		$pdf->SetXY(55, 50);
		$pdf->Cell(5, 5, utf8_decode('Pasaporte'), 0, 0, 'L',true);
		$pdf->SetXY(70, 50);
		$pdf->Cell(5, 5, utf8_decode(''), 1, 0, 'L',true);
		$pdf->SetXY(75, 50);
		$pdf->Cell(5, 5, utf8_decode('Licencia de conducir'), 0, 1, 'L',true);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetXY(10, 58);
		$pdf->Cell(0, 8, utf8_decode('Domicilio: '.$info['DOMICILIO']), 1, 1, 'L',true);
		$pdf->Cell(120, 8, utf8_decode('Correo electrónico (e-mail): '.$info['email']), 1, 0, 'L',true);
		$pdf->Cell(81, 8, utf8_decode('Teléfono de contacto: '.$info['client_number']), 1, 1, 'L',true);
		$pdf->SetFillColor(64,64,64);
		$pdf->SetTextColor(238, 180, 5);
		$pdf->Cell(0, 5, utf8_decode('TIPO DE PAQUETE Y MODALIDAD DE PAGO'), 1, 1, 'C',true);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 10, utf8_decode('Tipo de Paquete contratado:'), 1, 1, 'L',true);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetXY(50, 81);
		$pdf->Cell(5, 5, utf8_decode($member_checks['Basic']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode("Basic ($ {$costo_checks['Basic']} )"), 0, 1, 'L',true);

		$pdf->SetXY(85, 81);
		$pdf->Cell(5, 5, utf8_decode($member_checks['Light']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode("Light ($ {$costo_checks['Light']} )"), 0, 1, 'L',true);

		$pdf->SetXY(115, 81);
		$pdf->Cell(5, 5, utf8_decode($member_checks['Premium']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode("Premium ($ {$costo_checks['Premium']} )"), 0, 1, 'L',true);

		$pdf->SetXY(155, 81);
		$pdf->Cell(5, 5, utf8_decode($member_checks['Full Experience']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode("Full Experience ($ {$costo_checks['Full Experience']} )"), 0, 1, 'L',true);

		$pdf->SetXY(10, 88);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 10, utf8_decode('Modalidad de pago'), 1, 0, 'L',true);

		$pdf->SetFont('Arial', '', 7);
		$pdf->SetXY(40, 90);
		$pdf->Cell(5, 5, utf8_decode($type_checks['M']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode('Mensual al contado'), 0, 1, 'L',true);

		$pdf->SetXY(70, 90);
		$pdf->Cell(5, 5, utf8_decode($type_checks['MD']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode('Mensual domiciliado libre'), 0, 1, 'L',true);

		$pdf->SetXY(107, 90);
		$pdf->Cell(5, 5, utf8_decode($type_checks['S']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode('Semestral'), 0, 1, 'L',true);

		$pdf->SetXY(125, 90);
		$pdf->Cell(5, 5, utf8_decode($type_checks['MDA']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode('Mensual domiciliado a 12 meses'), 0, 1, 'L',true);

		$pdf->SetXY(170, 90);
		$pdf->Cell(5, 5, utf8_decode($type_checks['A']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode('Anual'), 0, 1, 'L',true);

		$pdf->SetXY(185, 90);
		$pdf->Cell(5, 5, utf8_decode($type_checks['O']), 1, 0, 'L',true);
		$pdf->Cell(10, 5, utf8_decode('Otro'), 0, 1, 'L',true);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetXY(10, 98);
		$pdf->Cell(67, 8, utf8_decode('Cantidad a Pagar: $ '.$info['quote_total']), 1, 0, 'L',true);
		$pdf->Cell(67, 8, utf8_decode('Monto de Inscripción: $'.$info['INSCRIPCION']), 1, 0, 'L',true);
		$pdf->Cell(67, 8, utf8_decode('Fecha de pago:'), 1, 0, 'L',true);
		$pdf->SetXY(10, 106);
		$pdf->SetFillColor(64,64,64);
		$pdf->SetTextColor(238, 180, 5);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, utf8_decode('INFORMACIÓN DE LA SUCURSAL DE INSCRIPCIÓN'), 1, 0, 'C',true);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(10, 111);
		$pdf->Cell(0, 10, utf8_decode('Domicilio: '.$info['DOMICILIO_FISCAL']), 1, 1, 'L');
		$pdf->Cell(0, 5, utf8_decode('Telefonos de contacto: '.$info['TELEFONOS']), 1, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 128);
		$pdf->MultiCell(0, 3,utf8_decode('CONTRATO QUE CELEBRAN POR UNA PARTE LA SOCIEDAD DENOMINADA "TRAINING INNOVATION", S.A.P.I. DE C.V., (EN LO SUCESIVO "DEXFIT"), Y POR LA OTRA, EL (LA) SEÑOR (A) CUYO (S) NOMBRE(S) Y APELLIDO(S) SE ENCUENTRAN MENCIONADOS DENTRO DEL CONTENIDO DE LA "CARÁTULA DE CONTRATO" INCISO I., POR SU PROPIO DERECHO (EN LO SUCESIVO "EL USUARIO TITULAR"); SUJETÁNDOSE AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS:'), 0, 'J',FALSE);
		$pdf->SetXY(10, 140);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, utf8_decode('D E C L A R A C I O N E S'), 0, 1, 'C');
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(0, 5, utf8_decode('I. Declara "DEXFIT", lo siguiente:'), 0, 1, 'L');
		$pdf->Cell(0, 3, utf8_decode(' a) Que es una sociedad mercantil, legalmente constituida conforme a la legislación mexicana aplicable.'), 0, 1, 'L');
		$pdf->Cell(0, 3, utf8_decode(' b) Que se encuentra debidamente registrada ante el registro federal de contribuyentes.'), 0, 1, 'L');
		$pdf->Cell(0, 3, utf8_decode(' c) Que cuenta con la legítima posesión (uso y disfrute) de las instalaciones de gimnasios denominadas "DEXFIT".'), 0, 1, 'L');
		$pdf->Cell(0, 3, utf8_decode(' d) Que cuenta exclusivamente con los derechos de propiedad industrial respecto de la marca, logotipos, avisos comerciales y/o cualquier otro derivado.'), 0, 1, 'L');
		$pdf->Cell(0, 3, utf8_decode(' e) Que cuenta con la capacidad jurídica y económica necesaria para el cumplimiento de las obligaciones establecidas en el presente Acuerdo de voluntades.'), 0, 1, 'L');

		$pdf->SetXY(10, 168);
		$pdf->Cell(0, 5, utf8_decode('II. Declara "EL USUARIO TITULAR", por su propio derecho, lo siguiente:'), 0, 1, 'L');
		$pdf->Cell(0, 3, utf8_decode(' a) Que es una persona física de nacionalidad mexicana, con la capacidad suficiente para llevar a cabo la celebración del presente Contrato.'), 0, 1, 'L');
		$pdf->MultiCell(0, 3,utf8_decode(' b) Que sus datos e información personal, así como tipo de paquetes de servicios, modalidad de pagos, información para domiciliación de pagos, en su caso,         y/o cualquier otro dato e información necesaria para su suscripcion a cualesquiera de las sucursales de gimnasios "DEXFIT", se encuentran mencionados         dentro del contenido de la "Carátula del Contrato" Inciso I.,  que adjunto al presente Contrato forma parte integrante del mismo '), 0, 'J',FALSE);
		$pdf->Cell(0, 3, utf8_decode(' c) Que las características respectivas de lo que incluye cada paquete le ha sido debidamente informada por parte de "DEXFIT".'), 0, 1, 'L');
		$pdf->MultiCell(0, 3, utf8_decode(' d) Que previo a recabar sus datos personales "DEXFIT" puso a su disposición e hizo de su conocimiento el correspondiente Aviso de Privacidad para el                correcto uso y protección de los mismos'), 0, 'J');
		$pdf->MultiCell(0, 3, utf8_decode(' e) Que "DEXFIT" ha hecho de su conocimiento el reglamento interno de las instalaciones de gimnasios "DEXFIT", que contiene las normas a seguir dentro de       cualesquiera de las instalaciones de los gimnasios "DEXFIT" y que adjunto dentro del "Anexo A", formará parte integrante del presente Contrato.'), 0, 'J');

		$pdf->SetXY(10, 203);
		$pdf->Cell(0, 5, utf8_decode('Hechas las declaraciones que anteceden, ratifican su voluntad de suscribir el presente Contrato, conforme a las siguientes cláusulas:'), 0, 1, 'L');
		$pdf->SetXY(10, 208);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 3, utf8_decode('C L Á U S U L A S'), 0, 1, 'C');
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(10, 3, utf8_decode('Primera.- Definiciones.'), 0, 0, 'L');
		$pdf->SetXY(10, 211);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                    Para efectos de la interpretación del presente Contrato, la palabra Gimnasio (s) deberá entenderse (singular o pluralmente) como el (los) establecimiento (s) en los que se encuentren ubicadas las sucursales/centros de acondicionamiento físico de "DEXFIT" y la palabra usuario (s) se entenderá como, cualquier persona física que se encuentre inscrita a cualesquiera de las sucursales de gimnasios de "DEXFIT".'), 0, 'J');

		$pdf->SetXY(10, 222);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Segunda.-Objeto'), 0, 1, 'L');
		$pdf->SetXY(10, 222);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                          "DEXFIT" pone a disposición de "EL USUARIO TITULAR", el acceso y uso de las instalaciones y los servicios que se prestan en las sucursales de gimnasios de "DEXFIT",conforme al tipo de paquete que éste último haya contratado.'), 0, 'J');

		$pdf->SetXY(10, 230);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Tercera.- Forma de Pagos.'), 0, 1, 'L');
		$pdf->SetXY(10, 230);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                            Los pagos de las cuotas correspondientes a la inscripción y mantenimiento por concepto de los servicios que prestan los gimnasios de "DEXFIT" a cargo de "EL USUARIO TITULAR" podrán ser realizados mediante; (i) dinero en efectivo, (ii) cargo a la tarjeta de crédito o débito, (iii) domiciliación a la tarjeta de crédito o débito, conforme lo haya elegido éste último.

		"EL USUARIO TITULAR" acepta y reconoce, que las cuotas de inscripción y de mantenimiento seguirán siendo generadas, no obstante, haya de por medio inasistencia o falta de uso de las instalaciones del (los) gimnasio (s) "DEXFIT"; toda vez que, dichas razones no serán causa justificada de no pago, por lo que el pago correspondiente a la cuota de mantenimiento deberá ser total y oportunamente cubierto.'), 0, 'J');

		$pdf->AddPage('P', array(216.3, 279.8)); //Orientacion,tamaño, rotacion
		$pdf->SetMargins(10, 10, 5); //izquierda,arriba,derecha
		$pdf->SetFont('Arial', 'B', 12);
		$path = realpath(dirname(__FILE__) . '/../../assets/images');
		$img1 = $path . DIRECTORY_SEPARATOR . 'logo_dexfit.PNG';
		$pdf->Image($img1, 100, 0, 0);
		$pdf->SetXY(10, 15);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Cuarta.- Incremento en cuotas.'), 0, 1, 'L');
		$pdf->SetXY(10, 15);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                   "EL USUARIO TITULAR" acepta y reconoce que la cuota de inscripción y mantenimiento incrementarán en forma anual. Así mismo se compromete a pagar los incrementos que se registren, respecto de los meses restantes de vigencia de su membresía. "DEXFIT" notificará a "EL USUARIO TITULAR" dichos incrementos, al interior de las instalaciones de cualesquiera de los gimnasios "DEXFIT" y optativamente mediante correo electrónico, con un mes de anticipación a la fecha en que se aplicará el mismo.'), 0, 'J');

		$pdf->SetXY(10, 28);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Quinta. Interés moratorio.'), 0, 1, 'L');
		$pdf->SetXY(10, 28);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                         La falta de pago oportuno de la cuota de mantenimiento, tendrá como consecuencia: (i) negar el acceso a los usuarios a cualesquiera de las sucursales de gimnasios "DEXFIT", y (ii) el cobro de un interés moratorio del 10% sobre saldo vencido, tomando como base la tasa de interés interbancaria de equilibrio y/o (iii) la cancelación del contrato en caso de que "EL USUARIO TITULAR", acumule los 2 meses de atraso/adeudo.'), 0, 'J');

		$pdf->SetXY(10, 38);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Sexta.- No impedimentos médicos o físicos.'), 0, 1, 'L');
		$pdf->SetXY(10, 38);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                  "EL USUARIO TITULAR", acepta que está en buena condición física y que no tiene problemas de salud que le impidan utilizar las instalaciones de gimnasio de "DEXFIT", acepta que "DEXFIT" y sus representantes o empleados de cualquier sucursal de "DEXFIT" no están obligados a otorgarle asesoría médica en relación a su estado de salud.

		"EL USUARIO TITULAR" reconoce que ha sido informado por parte de "DEXFIT" que dentro de las sucursales de gimnasios de "DEXFIT" se ofrece un servicio de valoración nutricional, por una única ocasión, que incluye una breve entrevista acerca de su historial médico y físico y con base a éste se determine por parte de "DEXFIT" o sus empleados, si creen que "EL USUARIO TITULAR" debería consultar a un médico antes de participar en cualquier programa de ejercicio o si será necesario que presente un certificado médico oficial para realizar cualquier tipo de actividad física y hacer uso de las instalaciones de las sucursales de gimnasios "DEXFIT".

		"EL USUARIO TITULAR" acepta que en caso de que su condición física o limitaciones físicas ahora o en un futuro llegasen a limitar el uso de las instalaciones de gimnasios "DEXFIT", se limitará de igual forma su derecho de usar las instalaciones de las sucursales de gimnasios. Sin embargo, si "EL USUARIO TITULAR" manifestando expresamente que bajo su propio riesgo y con certificado médico oficial, desea continuar haciendo uso de las instalaciones de gimnasios, cualquier deterioro o incidente médico que pudiese sufrir, será su total responsabilidad, deslindando a "DEXFIT" por cualquier daño o deterioro que haya sufrido.'), 0, 'J');

		$pdf->SetXY(10, 85);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Séptima.- De los invitados.'), 0, 1, 'L');
		$pdf->SetXY(10, 85);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                               En beneficio de "EL USUARIO TITULAR" y para evitar la saturación en el uso de la instalaciones de gimnasios, "DEXFIT" se reserva el derecho de limitar el número de invitados por dia o en horarios determinados; en todos los casos el invitado deberá estar en compañía de "EL USUARIO TITULAR" o usuario dependiente que le haya invitado obligándose a cumplir y a respetar el reglamento y políticas del gimnasio.'), 0, 'J');

		$pdf->SetXY(10, 95);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Octava.- Responsabilidad por el uso de las Instalaciones.'), 0, 1, 'L');
		$pdf->SetXY(10, 95);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                                         "EL USUARIO TITULAR", se compromete y obliga a usar y hacer que sus invitados, en su caso, utilicen las instalaciones, equipos y demás bienes propiedad de "DEXFIT", de acuerdo a su naturaleza, siguiendo en todo momento las instrucciones de uso que consten en los mismos o que les sean señaladas por el personal de cualesquiera de las sucursales de gimnasio; en tal virtud, el uso de todas las instalaciones, equipos y demás, lo realizarán bajo su única y exclusiva responsabilidad. En el supuesto de que se les dé mal uso a dichos bienes, "EL USUARIO TITULAR" será el único responsable de los daños y perjuicios causados al equipo/bienes, infraestructura de las instalaciones o provocados a terceras personas, ya sea por sí, o por medio de sus invitados.'), 0, 'J');

		$pdf->SetXY(10, 115);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode("                                                                                        'DEXFIT' tendrá contratado y en todo momento mantendrá vigente, un seguro de accidentes personales colectivo, el cual responderá por accidentes personales sufridos por \"EL USUARIO TITULAR\" y/o visitantes, siempre y cuando se hayan ocasionado por una causa imputable a \"DEXFIT\". En caso de que el accidente haya sido ocasionado por el mal uso de los equipos/bienes, instalaciones o impedimentos de salud o físicos (conforme a lo establecido en la cláusula Sexta del presente Contrato), \"DEXFIT\" no será responsable y no estará obligado a responder respecto de los daños que haya sufrido \"EL USUARIO TITULAR\" y/o visitantes."), 0, 'J');
		$pdf->SetXY(10, 115);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->MultiCell(0, 3, utf8_decode('Novena.- Seguro de responsabilidad por accidentes. '), 0, 'J');

		$pdf->SetXY(10, 133);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima.- De la restricción al acceso y uso de áreas de los gimnasios.'), 0, 1, 'L');
		$pdf->SetXY(10, 133);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                                                               "EL USUARIO TITULAR" acepta y reconoce expresamente que "DEXFIT" podrá restringir en forma parcial y temporal el acceso de algunas áreas y equipos del gimnasio, con motivo de eventos especiales, organizaciones de torneos y/o mantenimiento, reparación o remodelación de instalaciones o equipos, por lo que "DEXFIT" se reserva el derecho de establecer métodos y procedimientos para la utilización de las áreas que por éstos motivos tengan una capacidad limitada para cierto número de usuarios. Las restricciones referidas en la presente cláusula, no otorga derecho alguno de reducción, reembolso o bonificaciones de cuotas de inscripción o mantenimiento en favor de "EL USUARIO TITULAR" y el hecho de que algún usuario no pueda hacer uso de las instalaciones o equipo, precisamente en el momento en que así lo requiera, no generará ninguna clase de responsabilidad para "DEXFIT".'), 0, 'J');

		$pdf->SetXY(10, 155);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima Primera.- Daños y pérdidas de bienes '), 0, 1, 'L');
		$pdf->SetXY(10, 155);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                          .Por ningún motivo y bajo ninguna circunstancia, "DEXFIT" se hace responsable de daños, pérdidas o robo de bienes o valores propiedad de "EL USUARIO TITULAR", USUARIOS o invitados, dentro o alrededor de cualesquiera de las instalaciones de gimnasios de "DEXFIT".'), 0, 'J');

		$pdf->SetXY(10, 165);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima Segunda.- Días y horas de servicio.'), 0, 1, 'L');
		$pdf->SetXY(10, 165);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                           Ambas Partes están de acuerdo en que las sucursales de gimnasios estarán abiertas en los días y horarios que dé a conocer "DEXFIT" a "EL USUARIO TITULAR", y que para el acceso a las instalaciones, se deberá tomar en cuenta el horario según el paquete de servicio contratado por "EL USUARIO TITULAR".'), 0, 'J');

		$pdf->SetXY(10, 175);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima Tercera.- Paquetes de servicios.'), 0, 1, 'L');
		$pdf->SetXY(10, 175);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                      "DEXFIT" se reserva el derecho de modificar los tipos, clases y modalidades de paquetes de servicios, por lo cual "EL USUARIO TITULAR" no tendrá derecho a que se mantenga su suscripción en el mismo tipo, clase y modalidad, si es que la misma ya no existe. Si "EL USUARIO TITULAR" no desea mantener la vigencia de su suscripción en alguno de los nuevos tipos y modalidades vigentes, tendrá el derecho de dar por terminado su Contrato.'), 0, 'J');

		$pdf->SetXY(10, 188);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima cuarta.- Terminación anticipada del Contrato.'), 0, 1, 'L');
		$pdf->SetXY(10, 188);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                                 "EL USUARIO TITULAR" será el único que podrá dar por terminado el presente contrato, dando aviso mediante correo electrónico enviado a la sucursal en la cual haya realizado su inscripción, con por lo menos 30 días naturales de anticipación, haciendo devolución y entrega de los equipos/bienes que tenga en su posesión, en su caso, y que sean propiedad de "DEXFIT", en un término de no mayor a 20 días naturales posteriores al envío del correo dando aviso de la terminación del Contrato. Así mismo, en caso de dar por terminación el presente Contrato, "EL USUARIO TITULAR", deberá tomar en cuenta los siguientes supuestos:'), 0, 'J');
		$pdf->SetXY(10, 205);
		$pdf->Cell(0, 3, utf8_decode('  a) En caso de haber pagado mediante modalidad anual o semestral, no habrá rembolso.'), 0, 1, 'L');
		$pdf->MultiCell(0, 3, utf8_decode('  b) En caso de pago domiciliado libre, "EL USUARIO TITULAR" deberá notificar a "DEXFIT", su deseo de llevar a cabo la terminación, con 30 días de                    anticipación para cancelar el cargo, sin pago por concepto de penalización.'), 0, 'L');
		$pdf->MultiCell(0, 3, utf8_decode('  c) En caso de domiciliado a 12 meses, "EL USUARIO TITULAR" deberá notificar a "DEXFIT", su deseo de llevar a cabo la terminación con 30 días de                   anticipación para cancelar el cargo y se hará un cargo por concepto de penalización del 20% por el periodo restante al Contrato, en su caso.'), 0, 'L');

		$pdf->SetXY(10, 222);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima quinta.- Recisión de contrato.'), 0, 1, 'L');
		$pdf->SetXY(10, 222);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                               El incumplimiento por parte de "EL USUARIO TITULAR" a lo establecido en el presente Contrato, reglamentos, políticas o normas que rijan dentro de las instalaciones de cualesquiera de las sucursales de gimnasios, dará derecho a "DEXFIT" a rescindir el presente documento, obligándose "EL USUARIO TITULAR" a cubrir la cantidad equivalente al 20% del costo total de una anualidad de cuotas de mantenimiento, por concepto de pena convencional.'), 0, 'J');

		$pdf->SetXY(10, 237);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima sexta.- Del mantenimiento y reactivación de suscripciones.'), 0, 1, 'L');
		$pdf->SetXY(10, 237);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                                                     Al término del período correspondiente al mantenimiento pagado, en caso de que "EL USUARIO TITULAR", desee prorrogar su contrato un año más, deberá continuar con los pagos de las nuevas cuotas de mantenimiento que se encuentren vigentes en ese momento.'), 0, 'J');

		$pdf->AddPage('P', array(216.3, 279.8)); //Orientacion,tamaño, rotacion
		$pdf->SetMargins(10, 10, 5); //izquierda,arriba,derecha
		$pdf->SetFont('Arial', 'B', 12);
		$path = realpath(dirname(__FILE__) . '/../../assets/images');
		$img1 = $path . DIRECTORY_SEPARATOR . 'logo_dexfit.PNG';
		$pdf->Image($img1, 100, 0, 0);
		$pdf->SetXY(10, 15);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima séptima. Encabezados.-'), 0, 1, 'L');
		$pdf->SetXY(10, 15);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                   Los encabezados de las cláusulas tienen la exclusiva finalidad de facilitar su lectura y no tendrán el efecto de modificar o afectar el contenido, términos y condiciones de las mismas.'), 0, 'J');

		$pdf->SetXY(10, 25);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima octava.- Independencia del clausulado'), 0, 1, 'L');
		$pdf->SetXY(10, 25);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                             En caso de que una o más de las disposiciones contenidas en este Convenio sea, por cualquier razón, inválida, ilegal o no pueda ejercitarse en cualquier aspecto, tal invalidez o ilegalidad no afectará cualquier otra disposición aquí prevista y este Acuerdo será interpretado como si tal disposición inválida o ilegal nunca hubiera sido incluida.'), 0, 'J');

		$pdf->SetXY(10, 38);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(7, 3, utf8_decode('Décima novena. Jurisdicción y Competencia.-'), 0, 1, 'L');
		$pdf->SetXY(10, 38);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 3, utf8_decode('                                                                            Convienen expresamente las Partes, que para la interpretación y cumplimiento del presente Contrato se someterán a los tribunales del primer partido judicial en el estado de Jalisco, renunciando al fuero de cualquier otro domicilio presente o futuro que llegare a corresponderles.

		Leído el presente Acuerdo, entendiendo todos sus términos, las Partes lo firman de conformidad en la fecha referida en la Carátula del Contrato como "Fecha de Firma del Contrato".'), 0, 'J');

		$pdf->SetXY(40, 70);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(7, 3, utf8_decode('"EL USUARIO TITULAR"'), 0, 1, 'L');
		$pdf->SetXY(140, 70);
		$pdf->Cell(7, 3, utf8_decode('"DEXFIT"'), 0, 1, 'L');

		$pdf->SetXY(25, 85);
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(7, 3, utf8_decode('____________________________________'), 0, 1, 'L');
		$pdf->SetXY(47, 89);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(7, 3, utf8_decode('Nombre y Firma'), 0, 1, 'L');
		$pdf->SetXY(120, 85);
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(7, 3, utf8_decode('____________________________________'), 0, 1, 'L');
		$pdf->SetXY(117, 89);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(7, 3, utf8_decode('"TRAINING INNOVATION", S.A.P.I. DE C.V'), 0, 1, 'L');

		$pdf->AddPage('P', array(216.3, 279.8)); //Orientacion,tamaño, rotacion
		$pdf->SetMargins(10, 10, 5); //izquierda,arriba,derecha
		$pdf->SetFont('Arial', 'B', 12);
		$path = realpath(dirname(__FILE__) . '/../../assets/images');
		$img1 = $path . DIRECTORY_SEPARATOR . 'logo_dexfit.PNG';
		$pdf->Image($img1, 100, 0, 0);
		$pdf->SetXY(90, 15);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(30, 10, utf8_decode('"ANEXO A"'), 0, 1, 'C');
		$pdf->SetX(90);
		$pdf->Cell(40, 5, utf8_decode('-REGLAMENTO Y POLÍTICAS DE DEXFIT-'), 0, 1, 'C');
		$pdf->SetXY(10, 30);
		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(0, 4, utf8_decode('Estas políticas aplican para cualquiera de nuestros paquetes de servicios contratados y "EL USUARIO TITULAR" manifiesta que acatará libremente las políticas aquí manifestadas:'), 0, 'J');
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetXY(10, 40);
		$pdf->Cell(0, 5, utf8_decode('I. USO DE LAS INSTALACIONES.'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 45);
		$pdf->MultiCell(0, 4, utf8_decode('a) El acceso a las instalaciones se hará sin excepción, mediante registro de huella digital de los usuarios en los torniquetes de entrada.
		b) Todos los usuarios deberán portar ropa y calzado deportivo adecuado para la práctica de actividades físicas, motive por el cual no se permite estar sin camisa dentro de las instalaciones o con vestimenta distinta a la señalada.
		c) Quedan prohibidas mayas, lycras, tops con transparencias o de tela muy delgada que con la humedad sea transparente, camisas de resaque y calzado que no sea deportivo.
		d) Al terminar la actividad física el usuario deberá devolver el equipo utilizado a su lugar y limpiar su área de entrenamiento, así como aparatos utilizados antes de retirarse.
		e) Las mancuernas y discos deberán permanecer dentro del piso ahulado de la zona de peso libre en todo momento.
		f) Está prohibido azotar, arrojar y dar uso inadecuado a los equipos, accesorios y/o instalaciones. En caso de ocasionar daño a los equipos por uso indebido, la reparación o reposición de los mismos deberá ser cubierta por el usuario en su totalidad.
		g) No se permite comercializar ni ofrecer ningún tipo de producto o servicio, como entrenamientos personalizados, dentro de las instalaciones del gimnasio.
		h) Está prohibido hacer uso de las instalaciones del gimnasio para repartir volantes, folletos, piezas promocionales, cupones, demostración de mercancías y cualquier tipo de actividad lucrativa o publicidad personal, colectas o donativos para cualquier causa.
		i) Los usuarios deberán conducirse de manera respetuosa hacia el Staff de la unidad, usuarios e invitados, motivo por el cual se prohíben los gritos, uso de palabras altisonantes, así como cualquier agresión física o verbal dentro de las instalaciones.
		j) No está permitido ingerir alimentos en ninguna de las áreas de entrenamiento ni instalaciones del gimnasio.
		k) Los equipos deberán ser alternados entre los usuarios, especialmente en las horas de mayor afluencia evitando permanecer sentado en los mismos sin utilizarlos, descansando o utilizando el celular y no exceder de 30 minutos por cada equipo utilizado.
		l) No se permite el ingreso de mascotas a las instalaciones ni dejarlas en el área de recepción mientras se realizan las actividades físicas.
		m) En caso de que la afluencia de la unidad lo requiera, registrarse de acuerdo a las indicaciones del gerente.
		n) No se permite dejar objetos personales en las áreas de entrenamiento, cabinas o muebles del gimnasio; ya que en caso de extravío "DEXFIT" no será responsable por los mismos. '), 0, 'J');

		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetXY(10, 132);
		$pdf->Cell(0, 5, utf8_decode('II. SEGURIDAD.'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 137);
		$pdf->MultiCell(0, 4, utf8_decode('a) Está prohibido fumar o ingerir cualquier tipo de bebidas alcohólicas, drogas, estupefacientes o cualquier enervante dentro de las instalaciones.
		b) Se negará el acceso a usuarios que pretendan ingresar a las instalaciones con aliento alcohólico o que se presuma se encuentran bajo la influencia de drogas.
		c) Ningún usuario podrá ingresar a las instalaciones portando armas u objetos punzocortantes.
		d) Queda prohibido filmar o fotografiar con cámara o dispositivo, móvil o electrónico dentro de los vestidores, sanitarios, regaderas y demás espacios señalados por personal de "DEXFIT".
		e) En el resto de las instalaciones de "DEXFIT", queda parcialmente permitido al usuario tomar fotografías a sí mismo, siempre y cuando no sean fotografiados otros usuarios, personal de "DEXFIT" o marcas publicitadas; motivo por el cual el usuario deslinda a "DEXFIT" de cualquier violación a esta regla.
		f) Se prohíbe realizar actividades fuera de las actividades físicas propias del gimnasio que puedan dañar o poner en peligro a los usuarios y/o personal "DEXFIT", tales como correr, jugar entre usuarios o con los equipos.
		g) El usuario tendrá a su disposición el uso de lockers, debiendo utilizar en todo momento un candado de seguridad para resguardo de sus pertenencias.
		h) Será responsabilidad del usuario que todos los artículos de valor se resguarden. Por lo anterior "DEXFIT" no será responsable tampoco por el costo de trámites de reposición de tarjetas o documentos que el usuario no haya reguardado en las cajas de seguridad.
		i) En caso de robo, si el usuario requiere presentar a personal de "DEXFIT" el acta especial levantada ante Ministerio Público o autoridad competente, que acredite el delito cometido, deberá hacerlo a título personal.
		j) El staff tiene prohibido resguardar objetos personales en recepción como llaves de vehículos, incluyendo medios de transporte como bicicletas (aún plegables), patinetas, patines, deslizadores eléctricos y similares, así como accesorios de los mismos, motivo por el cual el usuario no deberá insistir.
		k) Los usuarios deberán retirar sus pertenencias de los lockers antes del cierre el gimnasio, toda vez que estas serán abiertas para limpieza. "DEXFIT" resguardará los objetos, por periodo de un mes, periodo en el cual seremos responsables de dichos bienes.
		l) Todos los usuarios manifiestan que su estado de salud es óptimo para realizar actividades físicas, eximiendo a "DEXFIT" de cualquier responsabilidad derivada de dicha manifestación en caso de ser falsa, asimismo, deberán realizar por su cuenta, de manera periódica las evaluaciones médicas necesarias para verificar que su salud se mantenga en buen estado. '), 0, 'J');

		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetXY(10, 228);
		$pdf->Cell(0, 5, utf8_decode('III. HIGIENE'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 231);
		$pdf->MultiCell(0, 4, utf8_decode('a) Queda estrictamente prohibido el uso o desecho de jeringas en cualquier área del gimnasio, así como suministrar de manera oral o inyectar a otros usuarios, en caso de necesitar aplicarse alguna sustancia por indicaciones médicas deberá suministrarse de manera previa al ingreso a las instalaciones.
		b) Los lavabos son de uso exclusivo para aseo de las manos y rasurado facial en caso de los hombres, queda prohibido depilarse o lavarse el cabello distintas partes del cuerpo en ellos.
		c) Sin excepción alguna el uso de las regaderas será individual.
		d) Será obligación del usuario procurar buenas prácticas de higiene dentro de las instalaciones.
		e) Será obligación del usuario utilizar una toalla limpia al realizar sus actividades físicas para limpiar el sudor en los aparatos. '), 0, 'J');

		$pdf->AddPage('P', array(216.3, 279.8)); //Orientacion,tamaño, rotacion
		$pdf->SetMargins(10, 10, 5); //izquierda,arriba,derecha
		$pdf->SetFont('Arial', 'B', 12);
		$path = realpath(dirname(__FILE__) . '/../../assets/images');
		$img1 = $path . DIRECTORY_SEPARATOR . 'logo_dexfit.PNG';
		$pdf->Image($img1, 100, 0, 0);
		$pdf->SetXY(10, 15);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, utf8_decode('IV. INVITADOS.'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 20);
		$pdf->MultiCell(0, 4, utf8_decode('a) Todo invitado deberá registrarse antes de ingresar a las instalaciones, indicando nombre, correo y firmando para su acceso.
		b) Los usuarios no podrán llevar como invitado a otro usuario que presente adeudos en su plan contratado.
		c) Los invitados sólo podrán ser menores de edad en caso de que el usuario titular sea el padre o tutor de dicho menor.
		d) No podrán ingresar a las instalaciones como invitados, usuarios que hayan sido dados de baja o ex empleados de "DEXFIT". '), 0, 'J');

		$pdf->SetXY(10, 38);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, utf8_decode('V. MENORES DE EDAD'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 43);
		$pdf->MultiCell(0, 4, utf8_decode('a) Los menores de edad a partir de 15 años podrán hacer uso de las instalaciones, presentando su acta de nacimiento o CURP al momento de inscribirse y hacerlo en compañía de un padre o tutor quien firmará el contrato y reglamento de dicho menor, obligándose al cumplimiento de todos los términos y condiciones de dichos documentos.
		b) Se prohíbe la entrada a las instalaciones a menores de 15 años a las instalaciones, ni permanecer en recepción o en los sillones de masaje sin la compañía de un adulto. '), 0, 'J');

		$pdf->SetXY(10, 65);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, utf8_decode('VI. HORARIOS'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 70);
		$pdf->MultiCell(0, 4, utf8_decode('a) Los horarios en que las unidades operan será lunes a viernes de las 06:00 a las 23:00 horas, sábados y domingos de las 8:00 a las 16:00 horas.; días festivos de 08:00 a 16:00 horas con excepción del 25 de diciembre, 1 de enero y 01 de julio de cada 6 años, únicos días en que permanecerá cerrada la unidad.
		b) "DEXFIT" podrá cerrar la unidad o modificar el horario por algún trabajo de reparación o remodelación o por alguna necesidad operativa en las instalaciones, notificando a los usuarios por medio de comunicados en recepción y redes sociales.
		c) El proceso de cierre de la unidad comenzará 30 minutos antes de la hora de cierre, reduciendo el número de luces encendidas y disminuyendo el volumen de la música. Diez minutos antes de cierre se restringe el acceso a las regaderas y cinco minutos antes se acciona la alarma, los usuarios deberán atender a este procedimiento sin excepción alguna y será su responsabilidad atender las indicaciones del staff y de este procedimiento programando su salida y retirándose de la unidad sin retrasar la hora de cierre.'), 0, 'J');

		$pdf->SetXY(10, 110);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, utf8_decode('VII. ENTRENADORES.'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 115);
		$pdf->MultiCell(0, 4, utf8_decode('a) "DEXFIT" ofrece el servició de profesores de Acondicionamiento Físico de manera gratuita, ellos son quienes determinaran el tiempo para la siguiente asesoría de entrenamiento.
		b) Es responsabilidad del usuario solicitar ayuda con un instructor si no está familiarizado con los equipos y accesorios para entrenar, deslindando expresamente el usuario a personal de "DEXFIT" por el desconocimiento o falta de pericia en el manejo de los aparatos ubicados en las instalaciones.
		c) Queda estrictamente prohibido para todos los usuarios brindar el servicio de entrenamientos personales o cualquier instrucción de entrenamiento a cualquier usuario o visitante dentro de las instalaciones, el incumplimiento de lo antes señalado será motivo de cancelación inmediata de su contrato sin responsabilidad alguna para "DEXFIT".
		d) Si algún usuario requiere de entrenamiento personalizado, podrá consultar en recepción la lista de entrenadores personales disponibles.
		e) Los usuarios no tendrán derecho a indemnización o reclamación alguna para el caso de que los entrenadores sean sustituidos.'), 0, 'J');

		$pdf->SetXY(10, 153);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, utf8_decode('VIII. CLASES DE SALÓN'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 158);
		$pdf->MultiCell(0, 4, utf8_decode('a) No se permite ingresar mancuernas, pesas o algún otro objeto o accesorio deportivo al salón de clases, a menos que el profesor que imparte la clase dé la instrucción específica al respecto.
		b) Las clases están sujetas a un cupo limitado, mismo que será anunciado en la entrada de cada salón, queda prohibido apartar el lugar o indicar que necesitan cierta cantidad de lugares, el acceso y cupo serán de acuerdo al arribo de cada usuario.
		c) Los horarios de las clases, así como los profesores están sujetos a cambios de acuerdo a las necesidades y disponibilidad de cada unidad por lo que de manera diaria se indicaran a la entrada del salón las actividades del día y los responsables de impartir las mismas (los usuarios no tendrán derecho a indemnización o reclamación alguna para el caso de que los profesores sean sustituidos).
		d) Está prohibido tomar fotografías o video del salón de clases o desarrollo de las mismas.
		e) Se deberá respetar el horario de inicio de la clase, queda prohibido el acceso una vez comenzada la clase de salón.
		f) Esta prohibido hacer uso del salón de manera individual mientras se esté impartiendo alguna clase.'), 0, 'J');

		$pdf->SetXY(10, 198);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, utf8_decode('IX. SANCIONES'), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 202);
		$pdf->MultiCell(0, 4, utf8_decode('a) En caso de incumplimiento a cualquiera de los puntos antes señalados, el usuario se hará acreedor a una amonestación por escrito, en caso de reincidencia su plan se rescindirá de
		manera inmediata, en el entendido de que la rescisión no implica que el usuario quede eximido de las cantidades adeudadas que deba cubrir al momento de la baja por concepto de pena convencional por cancelación anticipada, y en su caso anualidad o mensualidad, según sea el caso.
		b) En el supuesto de cometer una falta grave la baja se dará de manera inmediata sin amonestación previa, considerando como faltas graves las siguientes:
		- Agresión física o verbal en contra de otros usuarios, invitados y/o staff del gimnasio.
		- Cualquier tipo de comportamiento que altere el orden y/o la operación de la unidad.
		- Detectar en redes sociales fotografías o videos tomados en las instalaciones de cualquier unidad "DEXFIT".
		- Tener encuentros sexuales o conductas de acoso hacia otros usuarios del mismo o distinto sexo o personal del staff.
		- Ser sorprendido por usuarios o staff utilizando o desechando jeringas o ayudando a otro usuario a hacer uso de ellas.
		- Quejas de usuarios reportando faltas de respeto a su persona, privacidad o impedir el uso de los equipos o instalaciones.
		- Brindar entrenamientos personales a cualquier persona dentro de las instalaciones.
		c) Las Políticas del Reglamento son aplicables para todos los usuarios de los servicios que proporciona "DEXFIT" y están obligados al cumplimiento de estos en todo momento. '), 0, 'J');

		$pdf->AddPage('P', array(216.3, 279.8)); //Orientacion,tamaño, rotacion
		$pdf->SetMargins(10, 10, 5); //izquierda,arriba,derecha
		$pdf->SetFont('Arial', 'B', 12);
		$path = realpath(dirname(__FILE__) . '/../../assets/images');
		$img1 = $path . DIRECTORY_SEPARATOR . 'logo_dexfit.PNG';
		$pdf->Image($img1, 100, 0, 0);
		$pdf->SetXY(10, 15);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(10, 20);
		$pdf->MultiCell(0, 4, utf8_decode('Al inscribirse, el usuario consiente el uso de su imagen, así como la difusión de la misma por cualquier medio en materiales en los cuales "DEXFIT" promueva y divulgue sus servicios mediante publicidad, sin tener "DEXFIT" por esto ninguna responsabilidad hacia el usuario.'), 0, 'J');
		$pdf->SetXY(10, 30);
		$pdf->MultiCell(0, 4, utf8_decode('El usuario manifiesta que ha leído en su totalidad el contenido del presente documento y que es su voluntad obligarse en los términos y condiciones del mismo, sin que medie algún vicio de la voluntad, firmándolo en la fecha referida en la Carátula del Contrato como "Fecha de Firma del Contrato".'), 0, 'J');
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->SetXY(10, 45);
		$pdf->MultiCell(0, 4, utf8_decode('"EL USUARIO"'), 0, 'C');

		$pdf->SetXY(10, 65);
		$pdf->SetFont('Arial', '', 10);
		$pdf->MultiCell(0, 4, utf8_decode('_______________________________'), 0, 'C');

		$pdf->SetXY(80, 70);
		$pdf->MultiCell(50, 3, utf8_decode("Nombre completo: {$info['TITULAR']}
		No. de Socio: {$info['FOLIO']}"), 0, 'C');

		$pdf->Output();
	}
	/**
	 * Documentos Internos: Documento Modificaciones Membresia
	 */
	function pdf_modificaciones($info=array())
	{
		$pdf = new Pdf();
		$this->contract_atributes($pdf);
		foreach (array("FECHA"=>time(), "TITULAR"=>"", "MEMBRESIA"=>"", "MOTIVO"=>"_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", "CANTIDAD"=>"500", "TIPOMODIFICACION"=>"", "PORCIENTO"=>"33") as $field=>$default)
		{
			$info[$field] = isset($info[$field])?utf8_decode($info[$field]):$default;
		}
		$this->add_page_header($pdf, "right", $info["FECHA"]);

		$pdf->SetFont("Arial","B",10);
		$pdf->Text(16, 33, "MODIFICACI\xD3N DE MEMBRES\xCDA");
		$pdf->Text(16, 37, "DATOS DEL SOCIO TITULAR:");
		$pdf->Ln(10);
		$pdf->SetFont("Arial","",9);
		$pdf->Cell(14,5,"Nombre");
		$pdf->Cell(110,5,$info["TITULAR"],"B");
		$pdf->Cell(26,5,"Membres\xEDa No.:");
		$pdf->Cell(36,5,$info["MEMBRESIA"],"B",1);
		$pdf->Ln(8);

		$pdf->SetFont("Arial","",8);
		$pdf->Cell(90,5,"CAMBIO DE TIPO DE MEMBRES\xCDA",1);
		$pdf->Cell(6,5,($info["TIPOMODIFICACION"]=="MEMBRESIA"?"X":""),1,1);
		$pdf->Cell(90,5,"CAMBIO DE DEPENDIENTE",1);
		$pdf->Cell(6,5,($info["TIPOMODIFICACION"]=="DEPENDIENTE"?"X":""),1,1);
		$pdf->Cell(90,5,"BAJA DE SOCIO",1);
		$pdf->Cell(6,5,($info["TIPOMODIFICACION"]=="BAJA"?"X":""),1,1);
		$pdf->Cell(90,5,"REACTIVACI\xD3N",1);
		$pdf->Cell(6,5,($info["TIPOMODIFICACION"]=="REACTIVACION"?"X":""),1,1);
		$pdf->Cell(90,5,"REFERIDOS",1);
		$pdf->Cell(6,5,($info["TIPOMODIFICACION"]=="REFERIDO"?"X":""),1,1);
		$pdf->Ln(8);

		$pdf->SetFont("Arial","B",9);
		$pdf->Cell(186,4,"CAMBIO DE TIPO DE MEMBRESIA",0,1);
		$pdf->SetFont("Arial","",9);
		$pdf->MultiCell(186,4,"Autorizo al club deportivo Life & Fitness, realizar el cambio de tipo de membres\xEDa, ajust\xE1ndome a los costos de Mantenimiento e inscripci\xF3n correspondientes.");

		$pdf->SetFont("Arial","B",9);
		$pdf->Cell(186,4,"CAMBIO DE DEPENDIENTE",0,1);
		$pdf->SetFont("Arial","",9);
		$pdf->MultiCell(186,4,"Autorizo realizar el cambio de dependiente definitivamente por lo cual el dependiente anterior pierde todos los derechos como socio de Life & Fitness");

		$pdf->SetFont("Arial","B",9);
		$pdf->Cell(186,4,"BAJA DE SOCIO",0,1);
		$pdf->SetFont("Arial","",9);
		$pdf->MultiCell(186,4,"Por este conducto el socio titular aprueba que el o los socios mencionados en este documento quedan dados de baja por el siguiente motivo: ".$info["MOTIVO"]." de tal forma que entregar\xE1 su credencial y no tendr\xE1 acceso al club ni a sus servicios desde la fecha se\xF1alada. En dado caso de querer regresar a ser socio de Life & Fitness a su membres\xEDa deber\xE1 realizar el tr\xE1mite de reactivaci\xF3n o adquirir una nueva membres\xEDa ajust\xE1ndose a los requerimientos al momento de la nueva inscripci\xF3n.");

		$pdf->SetFont("Arial","B",9);
		$pdf->Cell(186,5,"REACTIVACI\xD3N",0,1);
		$pdf->SetFont("Arial","",9);
		$pdf->MultiCell(186,4,"Para poder reactivar, es necesario cubrir una cuota de reingreso (reactivaci\xF3n), que ser\xE1 el ".$info["PORCIENTO"]."% del costo de inscripci\xF3n actual por cada socio, m\xE1s el mes de mantenimiento que corresponda seg\xFAn el tipo de membres\xEDa. En caso de querer realizar el pago de sus pr\xF3ximos mantenimientos con cargo autom\xE1tico a su tarjeta, es necesario volver a hacer el documento correspondiente. Al realizar este tr\xE1mite y pago, la membres\xEDa vuelve a estar vigente");

		$pdf->SetFont("Arial","B",9);
		$pdf->Cell(186,5,"REFERIDOS",0,1);
		$pdf->SetFont("Arial","",9);
		$pdf->MultiCell(186,4,"Por este conducto, hago constar que refer\xED a la membres\xEDa No.".$info["MEMBRESIA"]." por lo cual me hago acreedor de $ ".$info["CANTIDAD"]." pesos en billetes promocionales Life & Fitness, v\xE1lidos para ser aplicados en el pr\xF3ximo pago de mantenimiento correspondiente a mi membres\xEDa, en caso de haber realizado el pago anual, estos ser\xE1n v\xE1lidos para el pago de visitas de invitados al club, servicio de locker fijo, o el pago de la siguiente anualidad. Estos billetes son promocionales, por lo cual no se dar\xE1 cambio en efectivo ni en billetes promocionales en caso de hacer el pago de una cantidad menos a los $ ".$info["CANTIDAD"].".");
		$pdf->Ln(40);
		$pdf->Cell(85,5,"Nombre y firma de conformidad socio titular","T",0,"C");
		$pdf->Cell(16,5,"");
		$pdf->Cell(85,5,"Nombre y sello de quien recibe","T",1,"C");
		$this->add_page_footer($pdf);

		$pdf->Output("Life & Fitness - Modificaciones Membresia.pdf", "D");
		exit;
	}

	function pdf_credencial($info = '')
	{
		$path = realpath(dirname(__FILE__).'/../../assets/images');
		$pathimages = realpath(dirname(__FILE__).'/../../assets');
		$pdf = new Pdf_code();
		$size = array(86, 56);
		$paper = 'A';
 		$pdf->SetMargins(0,0,0);
		$pdf->AddPage($paper, $size);
		$pdf->AliasNbPages();
		$pdf->SetTitle('Life and Fitness');
		$pdf->SetAuthor('ant.com.mx');
		$pdf->SetCreator('ant.com.mx');
		$pdf->SetSubject('Credencial');
		$pdf->SetKeywords('Gimnasio, Life, Fitness, Mexico, Swimming, Sport, club');
		$pdf->SetDisplayMode('default','single');
		$pdf->SetTextColor(0,0,0);

		$img1 = $info['foto']!=NULL&&$info['foto']!=''?(file_exists($pathimages.DIRECTORY_SEPARATOR.$info['foto'])?$pathimages.DIRECTORY_SEPARATOR.$info['foto']:$path.DIRECTORY_SEPARATOR.'no_foto.png'):$path.DIRECTORY_SEPARATOR.'no_foto.png';
		$imgwidth = 27;
		$imgheight = 27;
		$x = $info['foto']!=NULL&&$info['foto']!=''&&file_exists($pathimages.DIRECTORY_SEPARATOR.$info['foto'])?8:8;
		$y = 6;
		$pdf->Image($img1,$x,$y,$imgwidth,$imgheight);

		$img1 = $img1 = $info['logo']!=NULL&&$info['logo']!=''?(file_exists($pathimages.DIRECTORY_SEPARATOR.$info['logo'])?$pathimages.DIRECTORY_SEPARATOR.$info['logo']:$path.DIRECTORY_SEPARATOR.'esprezza.png'):$path.DIRECTORY_SEPARATOR.'esprezza.png';
		$x = 45;
		$y = 5;
		$imgwidth = 37;
		$imgheight = 12;
		$pdf->Image($img1,$x,$y,$imgwidth,$imgheight);

		$pdf->SetXY(38,21);
		$pdf->SetFont("Arial","",7);
		$pdf->Cell(6,3,"No. Socio:",0,1);
		$pdf->SetXY(38,24);
		$pdf->Cell(6,3,$info['folio'].$info['numero'],0,1);
		$pdf->SetXY(38,27);
		$pdf->Cell(6,3,"Nombre:",0,1);
		$pdf->SetXY(38,30);
		if(strlen($info['first_name']) > 31){
			$pdf->Text(39,32,utf8_decode($info['first_name']));
			if(strlen($info['last_name']." ".$info['second_last_name']) > 31){
				$pdf->Text(39,35,utf8_decode($info['last_name']));
				$pdf->Text(39,38,utf8_decode($info['second_last_name']));
			}else{
				$pdf->Text(39,35,utf8_decode($info['last_name']." ".$info['second_last_name']));
			}
		}else if(strlen($info['first_name']." ".$info['last_name']) > 31){
			$pdf->Text(39,32,utf8_decode($info['first_name']));
			$pdf->Text(39,35,utf8_decode($info['last_name']." ".$info['second_last_name']));
		}else if(strlen($info['contacto']) > 31){
			$pdf->Text(39,32,utf8_decode($info['first_name']." ".$info['last_name']));
			$pdf->Text(39,35,utf8_decode($info['second_last_name']));
		}else{
			$pdf->Text(39,32,utf8_decode($info['contacto']));
		}
		$pdf->SetFont("Arial","",6.3);
		$pdf->Text(9,40,utf8_decode($info['fiscal_address']." ".$info['fiscal_address_ext_num']." ".$info['fiscal_address_int_num']));
		$pdf->Text(9,43,utf8_decode($info['neighborhood']." ".$info['fiscal_address_zip']));
		$pdf->Text(9,46,utf8_decode($info['fiscal_address_municipality'].", ".$info['fiscal_address_state'].", ".$info['fiscal_country']));
		$pdf->Text(9,49,$info['fiscal_phone']);

		//$pdf->MultiCell(30,1.8,$domicilio,0);
		$pdf->setXY(8,37);
		$pdf->Text(8,54,"www.lifeandfitness.com.mx");

		$pdf->AddPage($paper, $size);

		$img1 = $path.DIRECTORY_SEPARATOR.'esprezza.png';
		$x = 50;
		$y = 5;
		$imgwidth = 25;
		$imgheight = 10;
		$pdf->Image($img1,$x,$y,$imgwidth,$imgheight);

		$pdf->SetFont("Arial","",5.5);
		$pdf->SetXY(8,16);
		$pdf->MultiCell(100,2,"No Transferible.\n Para mantener vigente la inscripci\xF3n es necesario estar al corriente en sus pagos. \n Para acceder es necesario la credencial.",0);

		$code=$info['folio'].$info['numero'];
		$pdf->Code128(40,29,$code,40,20);
		$pdf->SetFontSize(10);
		$pdf->Text(53,52,$code);

		/*$pdf->Cell(186,4,"CAMBIO DE TIPO DE MEMBRESIA",0,1);
		$pdf->SetFont("Arial","",9);
		$pdf->MultiCell(186,4,"Autorizo al club deportivo Life & Fitness, realizar el cambio de tipo de membres\xEDa, ajust\xE1ndome a los costos de Mantenimiento e inscripci\xF3n correspondientes.");*/


		$pdf->Output("Life & Fitness - Credencial.pdf", "D");
		exit;

	}

	/**
	 *
	 * @param string $type: string with the type of email to be sent.
	 * @param string $request: String with xml structure to define email.
	 * @param integer $status: [optional] Number of status for record.
	 * @param string $response: [optional] String or xml of response.
	 */
	function ant_send_email($type='', $request='', $status='0', $response='')
	{
		if ($type!='' && $request!='')
		{
			require_once(BASEPATH.'../orchestrator/orchestrator.php');
			$orch = new orchestrator();
			$orch->client = $this->tank_auth->get_user_ant_account();
			$orch->type = $type;
			$orch->status= $status;
			$orch->request = $request;
			$orch->response = $response;
			$orch->insertOrchestration();
		}
		return TRUE;
	}

    function get_text_color($back_color = 'FFFFFF') {
        $color = '000000';
        $back_color = str_replace(array('#'), '', $color);
        $maxdiv = (strlen($back_color) > 3) ? 2 : 1;
        list($r, $g, $b) = str_split($back_color, $maxdiv);
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        $a = hexdec(25);
        $s0 = ($r + $g + $b) / 3;
        if ($s0 > 127) {
            $color = '000000';
        } else {
            $color = 'FFFFFF';
        }
        return $color;
    }

	function device_connect(){
		$tablet_browser = 0;
		$mobile_browser = 0;
		$body_class = 'desktop';

		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$tablet_browser++;
			$body_class = "tablet";
		}

		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$mobile_browser++;
			$body_class = "mobile";
		}

		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
			$mobile_browser++;
			$body_class = "mobile";
		}

		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				'newt','noki','palm','pana','pant','phil','play','port','prox',
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				'wapr','webc','winw','winw','xda ','xda-');

		if (in_array($mobile_ua,$mobile_agents)) {
			$mobile_browser++;
		}

		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
			$mobile_browser++;
			//Check for tablets on opera mini alternative headers
			$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
				$tablet_browser++;
			}
		}
		if ($tablet_browser > 0) {
			return "is tablet";
		}
		else if ($mobile_browser > 0) {
			return "is mobil";
		}else {
			return 'is desktop';
		}
	}

}
