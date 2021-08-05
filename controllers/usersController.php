<?php

// Se requiere el modelo.
require '../model/usersModel.php';

// Se requieren las cápsulas.
require '../controllers/capsules/usersClass.php';

/**
 * Clase que controlará los datos de los usuarios.
 */
class usersController {

	private $model;
	
	public function __construct() {
		$this->model = new usersModel();
	}

	public function createUsers(string $email = NULL, string $name = NULL, string $lastName = NULL, int $phone = NULL, string $gender = NULL, string $birthday = NULL, string $job = NULL, string $password = NULL, string $bloodType = NULL, string $weight = NULL, string $height = NULL, string $acceptPolitics = 'No', string $token = NULL, string $emailConfirm = 'No') : bool {
		return $this->model->createUsersDB(new usersClass($email, $name, $lastName, $phone, $gender, $birthday, $job, $password, $bloodType, $weight, $height, $acceptPolitics ? 'Sí' : 'No', $token, $emailConfirm, '4bm59hFaxPwuU'));
	}

	public function readDataUserLogin(string $email = NULL, string $password = NULL) : string {
		return json_encode($this->model->readDataUserLoginDB(new usersClass($email, NULL, NULL, NULL, NULL, NULL, NULL, $password)), JSON_UNESCAPED_UNICODE);
	}

}