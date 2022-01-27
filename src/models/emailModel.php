<?php

include '../model/functions/connection.php';

/**
 * Clase que conecta con la base de datos de los usuarios.
 */
class EmailModel {

	private $connection;
	
	public function __construct() {
		$this->connection = connection::getInstance();
	}

	public function registerEmailDB(emailClass $email) {
		try {
			return $this->connection->getExecute($this->connection->getBindValue(true, $this->connection->getPrepareStatement(['EmailModel', 'registrerEmail']), $hotbed, ['__construct']));
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

}