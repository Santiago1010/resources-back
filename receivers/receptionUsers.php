<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-type: application/json');

ini_set("default_charset", "UTF-8");

// Se traen las funciones.
require '../controllers/functions/validationClass.php';

// Se traen los controladores.
require '../controllers/usersController.php';

/**
 * Clase que recibirá los datos y las acciones de los usuarios.
 */
class receptionUsers extends validationClass {

	private $usersController;
	
	public function __construct() {
		$this->usersController = new usersController();
	}

	public function invokerController() : bool|int|string	{

		switch ($_POST['typeFunction']) {

			// Registrar usuario.
			case 'createUser':
				return $this->callCreateUsers();
				break;

			// Confirmar usuario.
			case 'confirmUser':
				$this->callConfirmUser();
				break;

			// Iniciar sesión.
			case 'loginUser':
				$this->callLoginUser();
				break;

			// Iniciar sesión.
			case 'readUsers':
				$this->callReadUsers();
				break;

			// Cambiar contraseña.
			case 'updateUserPassword':
				$this->callUpdatePasswordUser();
				break;

			// Actualizar información de usuario.
			case 'updateUser':
				$this->callUpdateUser();
				break;

			// Eliminar información de usuario.
			case 'deleteUser':
				$this->callDeleteUser();
				break;
			
			default:
				return 'No se pudo realizar la acción.';
				break;

		}
	}

	// Llamar la función para crear el usuario.
	private function callCreateUsers() : bool|string {
		if ($this->isEmpty($_POST['dataUser'], ['phoneUser']) && $this->validateLength($_POST['dataUser']['passwordUser'], 8) && $this->validateEmail($_POST['dataUser']['emailUser']) && $this->validateNumbers($_POST['dataUser']['documentUser']) && $this->validateNumbers($_POST['dataUser']['phoneUser']) && $this->validateString($_POST['dataUser']['nameUser']) && $this->validateString($_POST['dataUser']['lastNameUser'])) {
			if ($_POST['dataUser']['passwordUser'] == $_POST['dataUser']['confirmPassword']) {
				return $this->usersController->createUser($_POST['dataUser']['documentUser'], $_POST['dataUser']['nameUser'], $_POST['dataUser']['lastNameUser'], $_POST['dataUser']['phoneUser'], $this->encryptPassword($_POST['dataUser']['passwordUser']), $this->encryptToken($_POST['dataUser']['documentUser']), $_POST['dataUser']['emailUser'], $_POST['dataUser']['roleUser'], $_POST['dataUser']['regionalUser'], $_POST['dataUser']['centerUser']);
			}else {
				return 'Las contraseñas no coinciden.';
			}
		}else {
			return 'Faltan datos.';
		}
	}

	// Llamar a la función para confirmar usuarios.
	private function callConfirmUser() : bool|string {
		return $this->isEmpty($_POST) ? $this->usersController->confirmUser($_POST['tokenUser']) : 'Faltan datos.';
	}

	// Llamar a la función para iniciar sesión.
	private function callLoginUser() : string {
		return $this->isEmpty($_POST) && $this->validateLength($_POST['passwordLogin'], 8) ? $this->usersController->loginUser($_POST['documentLogin'], $this->encryptPassword($_POST['passwordLogin'])) : 'Los datos no coinciden.';
	}

	// Llamar a la función para leer a los usuarios.
	private function callReadUsers() : string {
		return $this->usersController->readUsers();
	}

	// Llamar a la función para actualizar la contraseña.
	private function callUpdatePasswordUser() : bool {
		return $this->isEmpty($_POST) && $this->validateNumbers($_POST['documentUser']) && $this->validateLength($_POST['documentUser'], 5, 10) && $_POST['newPassword'] == $_POST['confirmNewPassword'] ? this->usersController->updatePasswordUser($_POST['documentUser'], $this->encryptPassword($_POST['newPassword'])) : 'Los datos no coinciden.';
	}

	// Llamar la función para actualizar el usuario.
	private function callUpdateUser() : bool {
		return $this->isEmpty($_POST['userData']) && $this->validateString($_POST['userData']['nameUser']) && $this->validateString($_POST['userData']['lastNameUser']) && $this->validateNumbers($_POST['userData']['phoneUser']) && $this->validateLength($_POST['userData']['phoneUser'], 5, 10) && $this->validateNumbers($_POST['userData']['documentUser']) && $this->validateLength($_POST['userData']['documentUser'], 7, 10) && $this->validateEmail($_POST['userData']['emailUser']) ? $this->usersController->updateUser($_POST['userData']['documentTypeUser'], $_POST['userData']['documentUser'], $_POST['userData']['nameUser'], $_POST['userData']['lastNameUser'], $_POST['userData']['phoneUser'], $_POST['userData']['emailUser']) : 'Los datos no coinciden.';
	}

	// Llamar a la fución para eliminar usuarios.
	private function callDeleteUser() : bool {
		return $this->isEmpty($_POST) && $this->validateNumbers($_POST['documentUser']) ? $this->controller->deleteUser($_POST['documentUser']) : 'Los datos no coinciden.';
	}

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_POST = json_decode(file_get_contents("php://input"), true);

	$r = new receptionUsers();
	print_r($r->invokerController());
}