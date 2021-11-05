<?php

namespace Src\controllers;

// Se traen las cápsulas.
use Src\models\capsules\UsersClass;

// Se usan los traits.
use Src\controllers\traits\Security;
use Src\controllers\traits\Validation;

// Se usa el modelo.
use Src\models\UsersModel;

/**
 * Clase que cotrola y gestiona los datos de los usuarios.
 */
class UsersController {

	use Security;
	use Validation;
	
	private $model;

	public function __construct(?string $rol = NULL) {
		$this->model = new UsersModel($rol);
	}

	// Se registra el usuario y si devuelve true, envía el correo electrónico; de lo contrario, devuelve false.
	public function createUser(int $document, string $name, string $lastName, string $email, ?int $phone = NULL, string $password, string $token, string $rol) : bool {
		return $this->model->readUserExistDB(new usersClass(NULL, $document)) ? ($this->model->createUserDB(new usersClass($documentType, $document, $name, $lastName, $email, $phone, $this->encryptRSA($this->encryptHash($password)), $token, $rol,)) ? $this->sendEmailConfirm($name, $lastName, $email, $token) : false) : false;
	}

	// Se confirma el correo electrónico del usuario.
	public function confirmUser(string $token) : bool {
		return $this->model->confirmUserDB(new usersClass(NULL, NULL, NULL, NULL, NULL, NULL, NULL, $token));
	}

	// Se envía el correo de confirmación.
	private function sendEmailConfirm(string $name, string $lastName, string $email, string $token) : bool {
		$email = new emailController($name . ' ' . $lastName, $email, 'CORREO DE CONFIRMACIÓN', NULL, $token);
		return $email->sendConfirmEmail();
	}

	// Se envía correo para recuperar la contraseña.
	public function sendEmailPassword(int $document, string $name, string $lastName, string $email, string $token) : bool {
		if ($this->model->setTokenDB(new usersClass(NULL, $document, NULL, NULL, NULL, NULL, NULL, $token))) {
			$email = new emailController($name . ' ' . $lastName, $email, 'CORREO PARA RECUPERACIÓN DE CONTRASEÑA', NULL, $token);
			return $email->sendRecoverPasswordEmail();
		}else {
			return false;
		}
	}

	// Se verifican y se devuelven los datos del usuario.
	public function loginUser(int $document, string $password) : array {
		return $this->validHash($password, $this->decryptRSA($this->model->readUsersPasswordDB(new usersClass(NULL, $document))['password_user'])) ? $this->model->readUserDataDB(new usersClass(NULL, $document)) : [false];
	}

	// Leer todos los usuarios registrados.
	public function readUsers() : array {
		return $this->model->readUsersDB();
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