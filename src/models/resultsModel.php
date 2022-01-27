<?php

namespace Src\Models;

// Se usa la conexión.
use Manifest\Connection;

// Se llama la entidad.
use Src\Models\Capsules\UsersEntity;

/**
 * Clase que conecta con la base de datos de los usuarios.
 */
class ResultsModel {

	private $connection;
	
	public function __construct(
		private ?string $rol = NULL
	) {
		$this->connection = connection::getInstance([$rol]);
	}

	// Registrar resultado de investigación a la base de datos.
	public function createResearchResultDB(ResearchResultEntity $result) : array {
		return $this->connection->fetch($this->connection->getBindValue(true, $this->connection->prepare(['ResultsModel', 'createResearchResults']), $result, ['__construct', 'getId', 'getTrl', 'getCtei']), false);
	}

	// Se crea la fila con los checks para el RI.
	public function setChecksDB(checksClass $check) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(false, $this->connection->prepare(['SelectsModel', 'setChecks']), $check, ['getRi']));
	}

	// Se verifica si el usuario existe.
	public function readUserExistDB(UsersEntity $user) : array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'read_user_exist']), $user, ['getDocument']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se verifica si el usuario temporal existe.
	public function readTemporaryUserExistDB(temporaryUserClass $user) : array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'read_temporary_user_exist']), $user, ['getDocument']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Crear usuario temporal.
	public function createTemporaryUserDB(temporaryUserClass $temporary) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->prepare(['UsersModel', 'createTemporaryUser']), $temporary, ['__construct', 'getId']));
	}

	// Generar relación entre investigadores y RI.
	public function ceateRelationInvestigatorRIDB(usersProjectsClasss $relation) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->prepare(['ResultsModel', 'ceateRelationInvestigatorRI']), $relation, ['__construct', 'getId']));
	}

	// Se ha creado la fila de la validación del RI.
	public function createValidationRiDB(validationCapsule $validation) : bool {
		try {
			return $this->connection->getExecute($this->connection->getBindValue(false, $this->connection->prepare(['ValidationModel', 'createValidationRI']), $validation, ['getProject']));
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Consultar datos del usuario.
	public function readUserDataDB(UsersEntity $user) : array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'read_user_data']), $user, ['getDocument']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Consultar los resultados del usuario con súper permisos.
	public function readOwnRiADB(UsersEntity $user) : ?array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['ResultsModel', 'read_own_research_result_a']), $user, ['getDocument']), true);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Consultar los resultados del usuario con súper permisos.
	public function readOtherRiADB(UsersEntity $user) : array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['ResultsModel', 'read_other_research_result_a']), $user, ['getDocument']), true);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Consultar los resultados del usuario con medio permisos.
	public function readOwnRiBDB(UsersEntity $user) : ?array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['ResultsModel', 'read_own_research_result_b']), $user, ['getDocument', 'getRegional']), true);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Consultar los resultados generales con medio permisos.
	public function readOtherRiBDB(UsersEntity $user) : ?array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['ResultsModel', 'read_other_research_result_b']), $user, ['getDocument', 'getRegional']), true);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Consultar los resultados generales para investigadores.
	public function readRiCDB(UsersEntity $user) : ?array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['ResultsModel', 'read_research_result_c']), $user, ['getDocument']), true);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se llama un resultado de investigación específico.
	public function readResultDB(ResearchResultEntity $result) {
		try {
			$ps = $this->connection->prepare(['ResultsModel', 'read_research']);
			return $this->connection->fetch($this->connection->getBindValue(false, $ps, $result, ['getId']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se consultan los investigadores de un resultado de investigación.
	public function readInvestigatorsResultDB(usersProjectsClasss $relation) {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['ResultsModel', 'read_relation_investigators']), $relation, ['getProject']), true);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se actualuiza el resultado de investigación.
	public function updateResearchResultDB(ResearchResultEntity $result) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->prepare(['ResultsModel', 'updateResearchResult']), $result, ['__construct', 'getTrl', 'getDateStart']));
	}

	// Se elimina el resultado de investigación.
	public function deleteResearchResultDB(ResearchResultEntity $result) {
		return $this->connection->getExecute($this->connection->getBindValue(false, $this->connection->prepare(['ResultsModel', 'deleteResearchResult']), $result, ['getId']));
	}

}