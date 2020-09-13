<?php 

class mailController extends Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){

		//$this->getLibrary('PHPMailer/class.phpmailer');
		//$this->getLibrary('PHPMailer/class.smtp');
		
		$this->getLibrary('PHPMailer-master/src/get_oauth_token');
		

		function F_EnviandoCorreoBienvenida() {
			$mail = new PHPMailer();
			/*$mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);*/
			//$mail->SMTPDebug = 2;
			$mail->IsSMTP();
			$mail->SMTPAuth = false;			
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPSecure = "ssl";
			$mail->Port = 465;
			//$mail->SMTPSecure = "tls";
			//$mail->Port = 587;
			
			$mail->Username = "ventas@a365.com.pe";
			$mail->Password = "digital365";
		
			$mail->From = "sara@a365.com.pe";
			$mail->FromName = "SISTEMA SARA 2.0";
			$mail->Subject = "CREACIÓN DE USUARIOS VICIDIAL Y APPE";

					
			$mail->MsgHTML('hola mundo');
			$mail->IsHTML(true);
		
			//$mail->AddAddress($email,'');
			$mail->AddAddress('frank.cg9@gmail.com','');
			//$mail->SMTPKeepAlive = true;  
			//$mail->Mailer = "smtp"; 
			
			/*$mail->AddAddress('nramirez@ecomdata.pe','');			
			$mail->AddAddress('flaura@a365.com.pe','');
			$mail->AddAddress('rgarzon@peruaa.com','');
			$mail->AddAddress('lgomez@peruaa.com','');
			$mail->AddAddress('soporte-magdalena@a365.com.pe','');
			$mail->AddAddress('dsantacruz@peruaa.com','');
			$mail->AddAddress('soporte-lince@a365.com.pe','');*/
			//$mail->AddAddress('crodriguez@peruaa.com','');
			//$mail->AddAddress('NAYALA@A365.COM.PE','');
			//$mail->AddAddress('JBORBOR@A365.COM.pe','');
			
		
			if(!$mail->Send()) {
				echo "Error: " . $mail->ErrorInfo .'<br>';
			} else {
				echo "Mensaje enviado correctamente <br>";
		
			}
			$mail->ClearAddresses();
		}
		F_EnviandoCorreoBienvenida();
		
	}
}

?>