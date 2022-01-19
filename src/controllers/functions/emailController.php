<?php

namespace Src\Controllers\Functions;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Src\Models\Capsules\Email;

use Src\Models\Capsules\EmailEntity;

class EmailController {

	private PHPMailer $mail;
	
	public function __construct() {
		$this->mail = new PHPMailer(true);

		define("SMTP_GMAIL", "smtp.gmail.com");
		define("PORT_GMAIL", 587);

		define("SMTP_OUTLOOK", "smtp.live.com");
		define("PORT_OUTLOOK", 587);
	}

	public function send(EmailEntity $emailClass) {
		try {
			$this->mail->SMTPDebug = 0;
			$this->mail->isSMTP();
			$this->mail->Host = 'mail.valtec.systems';
			$this->mail->CharSet = 'UTF-8';
			$this->mail->SMTPAuth = true;
			$this->mail->Username = 'contacto@valtec.systems';
			$this->mail->Password = '#afK~T#@MU.T';
			$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$this->mail->Port = 26;

			$this->mail->setFrom('contacto@valtec.systems', 'Santiago Correa');
			$this->mail->addAddress($emailClass->getAddAddress());
			$this->mail->addReplyTo($emailClass->getAddReplyTo());
			if($emailClass->getAddCC() != null) $this->mail->addCC($emailClass->getAddCC());
			if($emailClass->getAddBCC() != null) $this->mail->addBCC($emailClass->getAddBCC());
			if($emailClass->getAddAttachment() != null) $this->mail->addAttachment($emailClass->getAddAttachment());
			$this->mail->isHTML(true);
			$this->mail->Subject = $emailClass->getSubject();
			$this->mail->Body = $emailClass->getBody();
			$this->mail->AltBody = $emailClass->getAltBody();

			return $this->mail->send();
		} catch (Exception $e) {
			return $e;
		}
	}

}