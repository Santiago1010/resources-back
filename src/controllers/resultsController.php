<?php

namespace Src\Controllers;

// Se usan las cápsulas.
use Src\Models\Capsules\ResearchResultEntity;

// Se usan los traits.
use Src\Controllers\Traits\Security;
use Src\Controllers\Traits\Validation;
use Src\Controllers\Traits\Responses;

// Se usan las librerías.
use Respect\Validation\Validator as v;
use Egulias\EmailValidator\EmailValidator;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 * Clase que controla los datos de los resultados de investigación.
 */
class ResultsController {
	
	use Security;
	use Validation;
	use Responses;
	
	private $model;
	private $logger;

	// Se crea el método constructor.
	public function __construct() {
		$this->logger = new Logger('logs_user');
		$this->logger->pushHandler(new StreamHandler('Src/Controllers/Logs/' . date('d-m-Y') . '_users_error.log', Logger::INFO));
		$this->logger->pushHandler(new FirePHPHandler());
	}

	/*------------------------------------- Create -------------------------------------*/

	// Función para crear resultados de investigación.
	public function createResearchResult(string $projectName, string $techName, int $year, string $codeType, string $code, ?string $summary, int $trl, ?string $tipology, ?string $groupTipology, ?string $knowledeArea, ?string $subKnowledgeNetwork, ?string $knowledgeNetwork, string $dateStart, ?string $lastModification, string $rights, int $regional, int $center, int $group) : array {
		// Se valida si el resultado de investigación no existe.
		if (!$this->validIfExistReasearchResult($projectName, $techName, $year, $codeType, $code)) {
			if ($this->model->createResearchResultDB(new ResearchResultEntity(NULL, $projectName, $techName, $year, $codeType, $code, $summary, $trl, $tipology, $groupTipology, $knowledeArea, $subKnowledgeNetwork, $knowledgeNetwork, $dateStart, $lastModification, $rights, $regional, $center, $group))) {
				$this->logger->info('Se ha registrado el resultado de investigación "' . $projectName . '" del año ' . $year . ' e identificado con el ' . $codeType . ' ' . $code . '.', ['resultsController->createResearchResult']);
				return $this->doneMessage('Se ha registrado el resultado de investigación.');
			}
		}else {
			$this->logger->warning('El resultado de investigación ' . $projectName . ' ya se encuentra registrado.', ['resultsController->createResearchResult']);
			return $this->doneMessage('Este resultado de investigación ya se encuentra registrado; por favor, valide con los colegas para confirmar.');
		}
	}

	/*------------------------------------- Read -------------------------------------*/

	// Función para leer todos los resultados de investigación.
	public function readResearchResults() : string {
		return json_encode($this->model->readResearchResultsDB(), JSON_UNESCAPED_UNICODE);
	}

	// Validar si un resultado de investigación ya se encuentra registrado.
	public function validIfExistReasearchResult(string $projectName, string $techName, int $year, string $codeType, string $code) : bool {
		return $this->model->validIfExistReasearchResultDB(new ResearchResultEntity(NULL, $projectName, $techName, $year, $codeType, $code));
	}

	/*------------------------------------- Update -------------------------------------*/

	// Función para actualizar un resultado de investigación.
	public function updateResearchResult(int $id, string $projectName, string $techName, int $year, string $codeType, string $code, ?string $summary, int $trl, ?string $tipology, ?string $groupTipology, ?string $knowledeArea, ?string $subKnowledgeNetwork, ?string $knowledgeNetwork, string $dateStart, ?string $lastModification, string $rights, int $regional, int $center, int $group) : array {
		if ($this->model->updateResearchResultDB(new ResearchResultEntity($id, $projectName, $techName, $year, $codeType, $code, $summary, $trl, $tipology, $groupTipology, $knowledeArea, $subKnowledgeNetwork, $knowledgeNetwork, $dateStart, $lastModification, $rights, $regional, $center, $group))) {
			return true;
		}else {
			return false;
		}
	}

	/*------------------------------------- Delete -------------------------------------*/

	// Función para eliminar un resultado de investigación.
	public function deleteResearchResult(int $id) : array {
		if ($this->model->deleteResearchResultDB(new ResearchResultEntity($id))) {
			return true;
		}else {
			return false;
		}
	}

	/*------------------------------------- Complements -------------------------------------*/

}