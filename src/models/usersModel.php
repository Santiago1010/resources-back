<?php

namespace Src\Models;

// Se usa la conexión.
use Manifest\Connection;

// Se llama la entidad.
use Src\Models\Capsules\UsersEntity;

/**
 * Clase que conecta con la base de datos de los usuarios.
 */
class UsersModel {

	private $connection;
	
	public function __construct(
		private ?string $rol = NULL
	) {
		$this->connection = connection::getInstance([$rol]);
	}

	// Se registra el usuario en la base de datos.
	public function createUserDB(UsersEntity $user) : bool {
		try {
			return $this->connection->getBindValue(true, $this->connection->prepare(['UsersModel', 'createUser']), $user, ['__construct', 'getId', 'getState'])->execute();
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se confirma el usuario en la base de datos.
	public function confirmUserDB(UsersEntity $user) : bool {
		try {
			return $this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'confirmUsers']), $user, ['getToken'])->execute();
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se genera el nuevo token para el usuario.
	public function setTokenDB(UsersEntity $user) : bool {
		try {
			return $this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'setToken']), $user, ['getDocument', 'getToken'])->execute();
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se trae la contraseña desde la base de datos.
	public function readUsersPasswordDB(UsersEntity $user) : array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'read_user_password']), $user, ['getDocument']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se leen los datos del usuario, siempre y cuando este esté confirmado.
	public function readUserDataDB(UsersEntity $user) : array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'read_user_data']), $user, ['getDocument']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se leen los datos de todos los usuariossiempre y cuando este estén confirmado.
	public function readUsersDB() : array {
		try {
			return $this->connection->fetch($this->connection->prepare(['UsersModel', 'read_users_data']), true);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se verifica si el usuario existe.
	public function readUserExistDB(UsersEntity $user) : array {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'read_user_exist']), $user, ['getDocument']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se actualiza la contraseña del usuario.
	public function updatePasswordUserDB(UsersEntity $user) : bool {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'updatePassword']), $user, ['getPassword']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se actualizan los datos del usuario.
	public function updateUserDB(UsersEntity $user) : bool {
		try {
			return $this->connection->fetch($this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'updateUser']), $user, ['getDocument', 'getName', 'getLastName', 'getEmail', 'getPhone']), false);
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

	// Se elimina el usuario.
	public function deleteUserDB(UsersEntity $user) : bool {
		try {
			return $this->connection->getBindValue(false, $this->connection->prepare(['UsersModel', 'deleteUser']), $user, ['getDocument'])->execute();
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

}