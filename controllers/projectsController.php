<?php

// Se requiere el modelo.
require '../model/projectsModel.php';

// Se requieren las cápsulas.
require '../controllers/capsules/projectsClass.php';
require '../controllers/capsules/objectivesClass.php';

// Se requieren las funciones.
require '../controllers/functions/securityCraft.php';
require '../controllers/functions/emailController.php';

/**
 * Clase que controlará los datos de los usuarios.
 */
class projectsController extends securityCraft {

	private $model;
	
	public function __construct() {
		$this->model = new projectsModel();
	}

	/* C: Crear */

	// Registrar usuarios.
	public function createProject(string $title, string $fisrt, ?string $second = NULL, string $municipality, ?string $description = NULL, ?string $post = NULL, string $justification, string $state, string $bibliography) : int {
		return $this->model->createProjectDB(new projectsClass(NULL, $title, $fisrt, $second, $municipality, $description, $post, $justification, $state, $bibliography))['id'];
	}

	public function createObjectives(array $generalV, array $generalT, array $specificV, array $specificT, int $id) : bool {
		$check = false;
		foreach ($generalV as $key => $gv) {
			$check = $this->model->createObjectivesDB(new objectivesClass(NULL, $gv, $generalT[$key], 'general', $id));
		}
		foreach ($specificV as $key => $sv) {
			$check = $this->model->createObjectivesDB(new objectivesClass(NULL, $sv, $specificT[$key], 'específico', $id));
		}
		return $check;
	}

	/* R: Read */

	/* U: Update */

	/* D: Delete */

	/* Others */

}