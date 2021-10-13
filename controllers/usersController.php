<?php

// Se requiere el modelo.
require '../model/usersModel.php';

// Se requieren las cápsulas.
require '../controllers/capsules/usersClass.php';

// Se requieren las funciones.
require '../controllers/functions/securityCraft.php';
require '../controllers/functions/emailController.php';

/**
 * Clase que controlará los datos de los usuarios.
 */
class usersController extends securityCraft {

	private $model;
	
	public function __construct() {
		$this->model = new usersModel();
	}

	/* C: Crear */

	// Registrar usuarios.
	public function createUsers(string $email, string $password, string $name, string $lastName, string $dateBorn, string $token, int $school) : string|bool {
		return $this->model->readUserExistDB(new usersClass(NULL, $email))['exist'] == 0 ? (($this->model->createUsersDB(new usersClass(NULL, $email, $this->encryptRSA($this->encryptHash($password)), $name, $lastName, $dateBorn, 'Sin confirmar', $token, $school)) ? $this->sendEmailConfirm($name, $lastName, $email, $token) : false)) : false;
	}

	/* R: Read */

	// Iniciar sesión.
	public function loginUser(string $email, string $password) : string {
		return $this->model->readUserExistDB(new usersClass(NULL, $email))['exist'] == 1 ? ($this->validHash($password, $this->decryptRSA($this->model->readUsersPasswordDB(new usersClass(NULL, $email))['password'])) ? json_encode($this->model->readUserDataDB(new usersClass(NULL, $email)), JSON_UNESCAPED_UNICODE) : 'No se ha podido iniciar sesión.') : 'El usuario no se ha registrado o no se ha confirmado.';
	}

	/* U: Update */

	// Confirmar usuario.
	public function confirmUser(string $token) : bool {
		return $this->model->confirmUserDB(new usersClass(NULL, NULL, NULL, NULL, NULL, NULL, NULL, $token));
	}

	// Generar token para recuperar la contraseña.
	public function setRecoverPassword(string $email, string $token) {
		return $this->model->readUserExistDB(new usersClass(NULL, $email))['exist'] == 1 ? ($this->model->setRecoverPasswordDB(new usersClass(NULL, NULL, NULL, NULL, NULL, NULL, NULL, $token)) ? $this->sendEmailPassword() : false) : false;
	}

	// Cambiar contraseña.
	public function changePassword() {
		// code...
	}

	/* D: Delete */

	/* Others */

	// Enviar correo de registro, para confirmar el usuarios
	private function sendEmailConfirm(string $name, string $lastName, string $email, string $token) {
		$email = new emailController($name . ' ' . $lastName, $email, 'CORREO DE CONFIRMACIÓN', NULL, $token);
		return $email->sendConfirmEmail();
	}

	// Enviar correo para recuperar la contraseña de los usuarios.
	private function sendEmailPassword(string $name, string $lastName, string $email, string $token) {
		$email = new emailController($name . ' ' . $lastName, $email, 'CORREO PARA LA RECUPERACIÓN DE CONTRASEÑA', NULL, $token);
		return $email->sendRecoverPasswordEmail();
	}

}