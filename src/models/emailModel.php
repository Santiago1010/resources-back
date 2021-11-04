<?php

include '../model/functions/connection.php';

/**
 * Clase que conecta con la base de datos de los usuarios.
 */
class emailModel {

	private $connection;
	
	public function __construct() {
		$this->connection = connection::getInstance();
	}

	public function registerEmailDB(emailClass $email) {
		try {
			$ps = $this->connection->getPrepareStatement(['EmailModel', 'registrerEmail']);
			return $this->connection->getExecute($this->connection->getBindValue(true, $ps, $hotbed, ['__construct']));
		} catch (PDOException $e) {
			return 'Error PDO: ' . $e;
		}
	}

}