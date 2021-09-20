<?php

//Se incluye la conexión.
include '../model/functions/connection.php';

/**
 * Clase que conecta con la base de datos con respecto a lo usuarios.
 */
class projectsModel {

	private $connection;
	
	public function __construct(?string $rol = NULL) {
		$this->connection = connection::getInstance($rol);
	}

	// Registrar usuario.
	public function createProjectDB(projectsClass $project) : array {
		return $this->connection->getFetch($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['ProjectsModel', 'createProject']), $project, ['__construct', 'getId']), false);
	}

	// Registrar objetivos.
	public function createObjectivesDB(objectivesClass $objective) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['ProjectsModel', 'createObjectives']), $objective, ['__construct', 'getId']));
	}

}