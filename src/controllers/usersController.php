<?php

namespace Src\controllers;

// Se traen las cápsulas.
//require '../controllers/capsules/usersClass.php';

// Se usan los traits.
use Src\controllers\traits\security;

// Se usa el modelo.
use Src\models\usersModel;

/**
 * Clase que cotrola y gestiona los datos de los usuarios.
 */
class usersController {

	use security;
	
	private $model;

	public function __construct(?string $rol = NULL) {
		$this->model = new usersModel($rol);
	}

	// Se registra el usuario y si devuelve true, envía el correo electrónico; de lo contrario, devuelve false.
	public function createUser(string $documentType, int $document, string $name, string $lastName, string $email, ?int $phone = NULL, string $password, string $token, string $rol, int $regional, int $center) : bool {
		return $this->model->readUserExistDB(new usersClass(NULL, $document)) ? ($this->model->createUserDB(new usersClass($documentType, $document, $name, $lastName, $email, $phone, $this->encryptRSA($this->encryptHash($password)), $token, 'Sin confirmar', $rol, $regional, $center)) ? $this->sendEmailConfirm($name, $lastName, $email, $token) : false) : false;
	}

	// Se confirma el correo electrónico del usuario.
	public function confirmUser(string $token) : bool {
		return $this->model->confirmUserDB(new usersClass(NULL, NULL, NULL, NULL, NULL, NULL, NULL, $token));
	}

	// Se envía el correo de confirmación.
	private function sendEmailConfirm(string $name, string $lastName, string $emailConfirm, string $token) : bool {
		$email = new emailController($name . ' ' . $lastName, $emailConfirm, 'CORREO DE CONFIRMACIÓN', NULL, $token);
		return $email->sendConfirmEmail();
	}

	// Se envía correo para recuperar la contraseña.
	public function sendEmailPassword(int $document, string $name, string $lastName, string $emailConfirm, string $token) : bool {
		if ($this->model->setTokenDB(new usersClass(NULL, $document, NULL, NULL, NULL, NULL, NULL, $token))) {
			$email = new emailController($name . ' ' . $lastName, $emailConfirm, 'CORREO PARA RECUPERACIÓN DE CONTRASEÑA', NULL, $token);
			return $email->sendRecoverPasswordEmail();
		}else {
			return false;
		}
	}

	// Se verifican y se devuelven los datos del usuario.
	public function loginUser(int $document, string $password) : string {
		$user = $this->model->readUsersPasswordDB(new usersClass(NULL, $document));
		return $this->validHash($password, $this->decryptRSA($user['password_user'])) ? json_encode($this->model->readUserDataDB(new usersClass(NULL, $document)), JSON_UNESCAPED_UNICODE) : false;
	}

	// Leer todos los usuarios registrados.
	public function readUsers() {
		return $this->model->readUsersDB();//json_encode($this->model->readUsersDB(), JSON_UNESCAPED_UNICODE);
	}

	// Se actualiza la contraseña. Ya sea como recuperación, o desde dentro de la plataforma.
	public function updatePasswordUser(int $document, string $password) : bool {
		$user = new usersClass('C.C.', $document, NULL, NULL, NULL, NULL, $password);
		return $this->model->updatePasswordUserDB($user);
	}

	// Se actualizan los datos del usuario.
	public function updateUser(string $documentType, int $document, string $name, string $lastName, ?int $phone = NULL, string $email) : bool {
		$user = new usersClass($documentType, $document, $name, $lastName, $phone, $email);
		return $this->model->updateUserDB($user);
	}

	// Se elimina el usuario.
	public function deleteUser(int $document) : bool {
		return $this->model->deleteUserDB(new usersClass(NULL, $document));
	}

}