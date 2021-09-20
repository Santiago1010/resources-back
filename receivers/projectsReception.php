<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST');
header('Allow: GET, POST');
header('Content-type: application/json');

ini_set("default_charset", "UTF-8");

//Se requiere el controlador.
require '../controllers/projectsController.php';

// Se requieren las funciones.
require '../controllers/functions/validationClass.php';

/**
 * Clase que recibe y reenvía los datos.
 */
class projectsReception extends validationClass {

	private $controller;

	public function __construct() {
		$this->controller = new projectsController();
	}

	public function invokeControllers() {
		switch ($_POST['typeFunction']) {

			// Registrar usuario.
			case 'createProject':
				return $this->callCreateProject();
				break;

			// Registrar usuario.
			case 'createObjectives':
				return $this->callCreateObjectives();
				break;

			default:
			return 'No se ha podido realizar la acción.';
			break;
		}
	}

	// Se llama la función para registrar el usuario.
	private function callCreateProject() : int {
		return $this->isEmpty($_POST['project'], ['second', 'description', 'post']) && $this->validateJustString($_POST['project']['title']) && $this->validateJustString($_POST['project']['first']) && $this->validateJustString($_POST['project']['second']) && $this->validateJustString($_POST['project']['municipality']) && $this->validateJustString($_POST['project']['description']) && $this->validateJustString($_POST['project']['post']) && $this->validateJustString($_POST['project']['justification']) && $this->validateJustString($_POST['project']['state']) && $this->validateJustString($_POST['project']['bibliography']) ? $this->controller->createProject($this->setMayus($this->noScapesStrings($_POST['project']['title'])), $this->noScapesStrings($_POST['project']['first']), $this->noScapesStrings($_POST['project']['second']), $this->noScapesStrings($_POST['project']['municipality']), $this->noScapesStrings($_POST['project']['description']), $this->noScapesStrings($_POST['project']['post']), $this->noScapesStrings($_POST['project']['justification']), $this->noScapesStrings($_POST['project']['state']), $this->noScapesStrings($_POST['project']['bibliography'])) : false;
	}

	private function callCreateObjectives()	{
		return $this->isEmpty([$_POST['objectives']['generalVerb'], $_POST['objectives']['generalText'], $_POST['objectives']['specificVerb'], $_POST['objectives']['specificText']]) ? $this->controller->createObjectives($_POST['objectives']['generalVerb'], $_POST['objectives']['generalText'], $_POST['objectives']['specificVerb'], $_POST['objectives']['specificText'], $_POST['id']) : false;
	}

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_POST = json_decode(file_get_contents("php://input"), true);
	
	$r = new projectsReception();
	print_r($r->invokeControllers());
}