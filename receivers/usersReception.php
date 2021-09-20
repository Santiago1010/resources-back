<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST');
header('Allow: GET, POST');
header('Content-type: application/json');

ini_set("default_charset", "UTF-8");

//Se requiere el controlador.
require '../controllers/usersController.php';

// Se requieren las funciones.
require '../controllers/functions/validationClass.php';

/**
 * Clase que recibe y reenvía los datos.
 */
class usersReception extends validationClass {

	private $controller;

	public function __construct() {
		$this->controller = new usersController();
	}

	public function invokeControllers() {
		switch ($_POST['typeFunction']) {

			// Registrar usuario.
			case 'createUser':
				return $this->callCreateUser();
				break;

			// Leer los datos para el login.
			case 'loginUser':
				return $this->callLoginUser();
				break;

			// Confirmar usuarios.
			case 'confirmUser':
				return $this->callConfirmUser();
				break;

			default:
			return 'No se ha podido realizar la acción.';
			break;
		}
	}

	// Se llama la función para registrar el usuario.
	private function callCreateUser() {
		if ($this->isEmpty($_POST) && $this->validateEmail($_POST['user']['email']) && $this->validLong($_POST['user']['password'], 8) && $this->validateJustString($_POST['user']['name']) && $this->validateJustString($_POST['user']['lastName']) && $this->validBirthday($_POST['user']['birthday']) && $this->validateJustNumbers($_POST['user']['school'])) {
			return $this->controller->createUsers($this->noScapesStrings($_POST['user']['email']), $this->encryptPassword($_POST['user']['password']), $this->setNames($_POST['user']['name']), $this->setNames($_POST['user']['lastName']), $_POST['user']['birthday'], $this->setMayus($this->encryptToken($_POST['user']['email'] . '' . $_POST['user']['password'] . '' . $_POST['user']['name'] . '' . $_POST['user']['lastName'])), $_POST['user']['school']);
		}else {
			return $this->validBirthday($_POST['user']['birthday']);
		}
	}

	// Se llama la función para iniciar sesión.
	private function callLoginUser() {
		return $this->isEmpty($_POST) && $this->validateEmail($_POST['user']['email']) && $this->validLong($_POST['user']['password'], 8) ? $this->controller->loginUser($this->noScapesStrings($_POST['user']['email']), $this->encryptPassword($_POST['user']['password'])) : 'Los datos no coinciden.';
	}

	// Llamar a la función para confirmar usuario.
	private function callConfirmUser() : bool {
		return $this->isEmpty($_POST) ? $this->controller->confirmUser($this->noScapesStrings($_POST['token'])) : false;
	}

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_POST = json_decode(file_get_contents("php://input"), true);
	
	$r = new usersReception();
	print_r($r->invokeControllers());
}