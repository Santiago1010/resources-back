<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('../resources/libraries/PHPMailer/src/Exception.php');
require_once('../resources/libraries/PHPMailer/src/PHPMailer.php');
require_once('../resources/libraries/PHPMailer/src/SMTP.php');

//Se traen las cápsulas.
//require '../controllers/capsules/emailClass.php';

//Se trae el modelo.
//require '../model/emailModel.php';

/**
 * Clase que controlará la gestión de envío de corres electrónicos.
 */
class emailController {

	private $nameTransmitter;
	private $emailTransmitter;
	private $subjectTransmitter;
	private $messageTransmitter;
	private $tokenTransmitter;

	private $mail;

	public function __construct($nameTransmitter, $emailTransmitter, $subjectTransmitter, $messageTransmitter, $tokenTransmitter = NULL) {
		$this->nameTransmitter = $nameTransmitter;
		$this->emailTransmitter = $emailTransmitter;
		$this->subjectTransmitter = $subjectTransmitter;
		$this->messageTransmitter = $messageTransmitter;
		$this->tokenTransmitter = $tokenTransmitter;

		//Se crean los datos del mail.
		$this->mail = new PHPMailer(true);
		$this->mail->setLanguage('es', '../resources/libraries/PHPMailer/language/');

		//Configuraciones del servidor.
		$this->mail->SMTPDebug = 0;                                       // Enable verbose debug output
		$this->mail->isSMTP();                                            // Set mailer to use SMTP
		$this->mail->CharSet    = 'UTF-8';								  //permitir envío de caracteres especiales (tildes y ñ)
		$this->mail->Host       = 'mail.valtec.systems';  				  // Specify main and backup SMTP servers
		$this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$this->mail->Username   = 'contacto@valtec.systems';              // SMTP username
		$this->mail->Password   = '#afK~T#@MU.T';                    	  // SMTP password
		$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port       = 26;                                     // TCP port to connect to

		//$this->model = new emailModel();
	}

	public function sendContactEmail() {
		try {
			//Crear emisor.
			$this->mail->setFrom('contacto@valtec.systems', 'ValTec');

			//Crear receptores.
			$this->mail->addAddress('santiagocorreaaguirre14@gmail.com');
			$this->mail->addReplyTo($this->emailTransmitter);

			//Contenido del correo.
			$this->mail->isHTML(true);                                 		   // Set email format to HTML
			$this->mail->Body = $this->messageTransmitter/* . '<br>He aceptado que he leído y entendido la política de tratamiento de datos.'*/;
			$this->mail->Subject = $this->setSubject();
			return $this->mail->send();
		}catch (Exception $e) {
			return false;
		}
	}

	public function sendConfirmEmail() {
		$this->setMessageContact();
		try {
			//Crear emisor.
			$this->mail->setFrom('contacto@valtec.systems', 'ValTec');

			//Crear receptores.
			$this->mail->addAddress($this->emailTransmitter);
			//$this->mail->addReplyTo($this->emailTransmitter);

			//Contenido del correo.
			$this->mail->isHTML(true);                                 		   // Set email format to HTML
			$this->mail->Body = $this->messageTransmitter;
			$this->mail->Subject = $this->setSubject();
			return $this->mail->send();
		}catch (Exception $e) {
			return false;
		}
	}

	public function sendRecoverPasswordEmail() {
		$this->setMessageRecoverPassword();
		try {
			//Crear emisor.
			$this->mail->setFrom('contacto@valtec.systems', 'ValTec');

			//Crear receptores.
			$this->mail->addAddress($this->emailTransmitter);

			//Contenido del correo.
			$this->mail->isHTML(true);                                 		   // Set email format to HTML
			$this->mail->Body = $this->messageTransmitter;
			$this->mail->Subject = $this->setSubject();
			return $this->mail->send();
		}catch (Exception $e) {
			return false;
		}
	}

	private function setSubject() {
		$subject = $this->subjectTransmitter . " " . $this->nameTransmitter . '.';

		$subject = str_replace('á', 'Á', $subject);
		$subject = str_replace('é', 'É', $subject);
		$subject = str_replace('í', 'Í', $subject);
		$subject = str_replace('ó', 'Ó', $subject);
		$subject = str_replace('ú', 'Ú', $subject);
		$subject = str_replace('ñ', 'Ñ', $subject);

		$subject = strtoupper($subject);

		return $subject;
	}

	private function setMessageContact() {
		$this->messageTransmitter = 'Para confirmar la cuenta de correo electrónico, haga click <a href="http://localhost:8080/confirmar/' . $this->tokenTransmitter . '">aquí (' . $this->tokenTransmitter . ')</a>.';
	}

	private function setMessageRecoverPassword() {
		$this->messageTransmitter = 'Para cambiar la contraseña de su cuenta, acceda en <a href="http://localhost:8080/recuperar/' . $this->tokenTransmitter . '">este link</a>.';
	}

	public function registerEmail()	{
		//$email = new emailClass($this->nameTransmitter, $this->emailTransmitter, $this->subjectTransmitter, $this->messageTransmitter);
		//return $this->model->registerEmailDB($email);
	}

}