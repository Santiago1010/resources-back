<?php

//Se incluye la conexión.
include '../model/functions/connection.php';

/**
 * Clase que conecta con la base de datos con respecto a lo usuarios.
 */
class usersModel {

	private $connection;
	
	public function __construct(?string $rol = NULL) {
		$this->connection = connection::getInstance($rol);
	}

	// Registrar usuario.
	public function createUsersDB(usersClass $user) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['UsersModel', 'createUser']), $user, ['__construct', 'getId', 'getConfirmEmail', 'getRol']));
	}

	// Leer la contraseña del usuario.
	public function readUsersPasswordDB(usersClass $user) {
		return $this->connection->getFetch($this->connection->getBindValue(false, $this->connection->getPrepareStatement(['UsersModel', 'read_user_password']), $user, ['getEmail']), false);
	}

	// Saber si un usuario ya se encuentra registrado.
	public function readUserExistDB(usersClass $user) : ?array {
		return $this->connection->getFetch($this->connection->getBindValue(false, $this->connection->getPrepareStatement(['UsersModel', 'read_user_exist']), $user, ['getEmail']), false);
	}

	// Leer los datos de 1 sólo usuario.
	public function readUserDataDB(usersClass $user) : bool|array {
		return $this->connection->getFetch($this->connection->getBindValue(false, $this->connection->getPrepareStatement(['UsersModel', 'read_user_data']), $user, ['getEmail']), false);
	}

	// Se confirma el usuario.
	public function confirmUserDB(usersClass $user) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(false, $this->connection->getPrepareStatement(['UsersModel', 'confirmUser']), $user, ['getToken']));
	}

	// Se genera token para recuperar contraseña.
	public function setRecoverPasswordDB(usersClass $user) : bool {
		return $this->connection->getExecute($this->connection->getBindValue(false, $this->connection->getPrepareStatement(['UsersModel', 'setToken']), $user, ['getToken']));
	}

}