<?php

//Se incluye la conexión.
include '../model/functions/connection.php';

/**
 * Clase que conecta con la base de datos con respecto a lo usuarios.
 */
class usersModel {

	private $connection;
	
	public function __construct() {
		$this->connection = connection::getInstance();
	}

	public function createUsersDB(usersClass $user)	{
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['UsersModel', 'createUser']), $user, ['__construct']));
	}

	public function readDataUserLoginDB(usersClass $user) {
		return $this->connection->getFetch($this->connection->getBindValue(false, $this->connection->getPrepareStatement(['UsersModel', 'readUserLoginData']), $user, ['getEmail', 'getPassword']), false);
	}

}