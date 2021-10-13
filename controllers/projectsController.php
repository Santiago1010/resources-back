<?php

// Se requiere el modelo.
require '../model/projectsModel.php';

// Se requieren las cápsulas.
require '../controllers/capsules/projectsClass.php';
require '../controllers/capsules/objectivesClass.php';
require '../controllers/capsules/authorsClass.php';
require '../controllers/capsules/impactsClass.php';
require '../controllers/capsules/productsClass.php';
require '../controllers/capsules/resultsClass.php';

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

	// Registrar objetivos.
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

	// Registrar autores.
	public function createAuthors(array $name, array $role, int $project) : bool {
		$check = false;
		foreach ($name as $key => $n) {
			$check = $this->model->createAuthorsDB(new authorsClass(NULL, $n, $role[$key], $project));
		}
		return $check;
	}

	// Registrar impactos.
	public function crateImpacts(array $ambit, array $expected, array $pinter, int $project) : bool {
		$check = false;
		foreach ($ambit as $key => $a) {
			$check = $this->model->crateImpactsDB(new impactsClass(NULL, $a, $expected[$key], $pinter[$key], $project));
		}
		return $check;
	}

	// Registrar resultados.
	public function createResults(array $text, int $objective, int $project) : bool {
		$check = false;
		foreach ($text as $key => $t) {
			$check = $this->model->createResultsDB(new resultsClass(NULL, $t, $objective, $project));
		}
		return $check;
	}

	// Registrar productos.
	public function createProducts(array $text, int $result) : bool {
		$check = false;
		foreach ($text as $key => $t) {
			$check = $this->model->createProductsDB(new productsClass(NULL, $t, $result));
		}
		return $check;
	}

	/* R: Read */

	/* U: Update */

	/* D: Delete */

	/* Others */

}