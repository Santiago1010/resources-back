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

	// Registrar autores.
	public function createAuthorsDB(authorsClass $author) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['ProjectsModel', 'createAuthors']), $author, ['__construct', 'getId']));
	}

	// Registrar impactos.
	public function createImpactsDB(impactsClass $impact) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['ProjectsModel', 'createImpacts']), $author, ['__construct', 'getId']));
	}

	// Registrar resultados.
	public function createResultsDB(resultsClass $result) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['ProjectsModel', 'createResults']), $author, ['__construct', 'getId']));
	}

	// Registrar resultados.
	public function createProductsDB(productsClass $result) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['ProjectsModel', 'createProducts']), $author, ['__construct', 'getId']));
	}

}