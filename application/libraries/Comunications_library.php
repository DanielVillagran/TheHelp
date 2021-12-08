<?php

class Comunications_library
{
	public $from;
	public $to;
	public $Cc;
	public $Bcc;
	public $content;
	public $type;
	public $misc; 
	
	public function process()
	{
		return $this->sendEmailPulse();
		
	}
        public function getEmailPulse(){
            require_once "pulse.php";
            $pulse = new pulse();
             $pulse->info();

                var_dump($response);
        }
    public function sendEmailPulse(){
                 require_once "pulse.php";
                 $pulse = new pulse();
                    $aEmail = array(
                'html' => $this->content,
                'text' => 'Email message text',
                'encoding' => 'UTF-8',
                'subject' => 'Email message subject',
                'from' => array(
                    'name' => 'Sender Name',
                    'email' => 'sender@example.com',
                ),
                'to' => array(
                    array(
                        'name' => 'Recipient Name',
                        'email' => 'recipient1@example.com'
                    ),
                    array(
                        'email' => 'recipient2@example.com'
                    ),
                ),
                'bcc' => array(
                    array(
                        'name' => 'Recipient Name',
                        'email' => 'recipient3@example.com'
                    ),
                    array(
                        'email' => 'recipient4@example.com'
                    ),
                ),
            );
            $res = $pulse->send_email($aEmail);
            if ($res['error']){ // check if operation succeeds
                die('Error: ' . $res['text']);
            } else {
                // success
            }
        }
        public function sendEmailTurbo(){
            require_once "smtp-turbo/lib/TurboApiClient.php";
               $email = new Email();
                $email->setFrom("mmora@ant.com.mx");
                $email->setToList("mmora@ant.com.mx");
               // $email->setCcList("dd@domain.com,ee@domain.com");
                //$email->setBccList("ffi@domain.com,rr@domain.com");	
                $email->setSubject("holas");
                //email->setContent($this->content);
                $email->setHtmlContent($this->content);
                $email->addCustomHeader('X-FirstHeader', "value");
                $email->addCustomHeader('X-SecondHeader', "value");
                $email->addCustomHeader('X-Header-da-rimuovere', 'value');
                $email->removeCustomHeader('X-Header-da-rimuovere');



                $turboApiClient = new TurboApiClient("donotreply@ant.com.mx", "RfSt5fj4");


                $response = $turboApiClient->sendEmail($email);

                var_dump($response);
}
	private function sendEmail(){
            require_once('Swift/Swift-5.0.3/lib/swift_required.php');
		// Create the SMTP configuration
		$transport = Swift_SmtpTransport::newInstance('smtp-pulse.com', 465, "ssl");
		$transport->setUsername("mmora@ant.com.mx");
		$transport->setPassword("Mf8EFP7cLC");
		$mailer = Swift_Mailer::newInstance($transport);

		
		if($this->type == "invoice"){
			$message = $this->sendInvoiceEmail($mailer);
		
		}else{
			//$HTMLFile = file_get_contents($this->content);
			$message = Swift_Message::newInstance("General")
			->setFrom($this->from)
			->setTo($this->to)
			->setBody($this->content, 'text/html');
		}
		
		if(!empty($this->Cc)){
			$message->setCc($this->Cc);
		}
		if(!empty($this->Bcc)){
			$message->setBcc($this->Bcc);
		}
		
		$numSent = $mailer->send($message);
		var_dump($numSent);
		$result = (object) array(
              'status' => true,
              'message' => 'ok'
        );
        return $result;
		
	}
	
	public function sendInvoiceEmail($mailer){
		$HTMLFile = file_get_contents('/var/www/vhosts/alexandria.antfarm.mx/alx/source/orchestrator/emailTemplates/factuEmailTemplate.xml');
		$HTMLFile = $this->transformInvoiceXML($HTMLFile);
		$message = Swift_Message::newInstance("Factura")
		->setFrom($this->from)
		->setTo($this->to)
		->setBody($HTMLFile, 'text/html');
		if (isset($this->misc['files']) && !empty($this->misc['files']))
		{
			foreach ($this->misc['files'] as $id=>$file)
			{
				if (file_exists($file))
				{
					$message->attach(Swift_Attachment::fromPath($file));
				}
				elseif (100<strlen(trim($file)))
				{
					$attachment = Swift_Attachment::newInstance(base64_decode($file), uniqid('report').'.pdf', 'application/pdf');
					$message->attach($attachment);
				}
			}
		}
		return $message;

	}
	
	public function transformInvoiceXML($xml){
		
		$xml = str_replace("{##LOGO##}", $this->misc['logo'], $xml);
		$xml = str_replace("{##XML##}", $this->misc['xml'], $xml);
		$xml = str_replace("{##PDF##}", $this->misc['pdf'], $xml);
		return $xml;
		
	}
	
	public function createPDF(){
		
		require('facturacion/fpdf17/fpdf.php');
		
		$pdf=new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(40,10,'Hola Mundo!');
		return $pdf->Output("prueba","S");
	}
	
	
}

class SMSProcess
{
	public $number;
	public $message;

	public function process()
	{
		$message = "OK";
		$status = true;
		if(isset($this->number)){
			if(strlen($this->number)==10){
				$message = $this->sendSMS();
			}else{
				$message = "Formato incorrecto";
				$status = false;
			}
		}else{
			$message = "Numero Telefonico Blanco";
			$status = false;
		}
		$result = (object) array(
				'status' => $status,
				'message' => $message
	 	);
		return $result;
	}
	
	private function sendSMS(){
		$wsdl = "http://sms.mexhico.com/smsdcodserver.php?wsdl";
		$cliente = new soapclient($wsdl);
		
		$respuesta = $cliente->SendMessage('lcaguirre',
				'68c9b@a2',
				$this->number,
				$this->message);
		return $respuesta;
	}
}



?>