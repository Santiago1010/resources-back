<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-type: application/json');

ini_set("default_charset", "UTF-8");

//Se requiere el controlador.
require '../controllers/usersController.php';

// Se requieren las funciones.
require '../controllers/functions/validationClass.php';
require '../controllers/functions/emailController.php';

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
				if ($this->isEmpty($_POST)) {
					return $this->controller->createUsers($this->noScapesStrings($_POST['usersBasicInformation']['email']), $this->noScapesStrings($_POST['usersBasicInformation']['name']), $this->noScapesStrings($_POST['usersBasicInformation']['lastName']), $_POST['usersBasicInformation']['phoneNumber'], $this->noScapesStrings($_POST['usersBasicInformation']['gender']), $_POST['usersBasicInformation']['birthday'], $this->noScapesStrings($_POST['usersBasicInformation']['job']), $this->encryptPassword($_POST['usersBasicInformation']['password']), $_POST['firstMetrics']['rh'], $_POST['firstMetrics']['weight'], $_POST['firstMetrics']['height'], $_POST['acceptPolitics'], $this->encryptToken($_POST['usersBasicInformation']['email']));
				}else {
					return 'Se requieren todos los datos.';
				}
				break;

			// Consultar los datos del usuario que desea registrarse.
			case 'readDataUserLogin':
				if ($this->isEmpty($_POST)) {
					return $this->controller->readDataUserLogin($this->noScapesStrings($_POST['dataUserLogin']['emailLogin']), $this->encryptPassword($_POST['dataUserLogin']['password']));
				}else {
					return 'Se requieren todos los datos.';
				}
				break;

			default:
				return 'No se ha podido realizar la acción.';
				break;
		}
	}

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_POST = json_decode(file_get_contents("php://input"), true);
	
	$r = new usersReception();
	print_r($r->invokeControllers());
}