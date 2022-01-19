<?php

namespace Manifest;

// Importar PDO.
use \PDO;
use \PDOStatement;
use \PDOException;

// Importar traits.
use Src\Controllers\Traits\Singleton;

class Connection {

	use Singleton;

	private PDO $conn;

	private string $host = $_ENV['DB_HOST'];
	private string $db_name = $_ENV['DB_NAME'];
	private string $user = $_ENV['DB_USERNAME'];
	private string $port = $_ENV['DB_PORT'];
	private string $password = $_ENV['DB_PASSWORD'];
	private $options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_TIMEOUT => 5
	];

	protected function init() {
		try {
			$this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8", $this->user, $this->password, $this->options);
		} catch (PDOException $e) {
			$this->conn = false;
		}
	}

	/*private function setConnection(?string $rol) : void {
		switch ($rol) {
			case 'aXVPYm9ZK09yUVJxTkJhSXdRNmNjdz09OjoL18HGXURa1hMvX8zyN+y3':
				$this->user = 'valtec_712faMqkJB0vo_admin';
				$this->password = '530AQXm1xPkeAuIT';
				break;
			
			default:
				$this->user = 'readRol';
				$this->password = 'X(HLWFb[(Z/8etgc';
				break;
		}
	}*/

	public function getBindValue(bool $inverted, $ps, Object $object, array $function) : PDOStatement {
    	$methods = get_class_methods($object);
		$count = 1;
    	
    	if (!$inverted) { // Para traer los que s equieren.
    		foreach ($function as $key => $value) {
    			$ps->bindValue($count++, $object->$value()/*, gettype($var) === 'integer' ? PDO::PARAM_INT ? PDO::PARAM_STR*/);
    		}
    	}else { // Para ignorar los otros.
    		$index = null;

    		for ($i = 0; $i < count($function); $i++) { 
    			$index = array_search($function[$i], $methods);
    			unset($methods[$index]);
    		}

    		$methods = array_values($methods);

    		foreach ($methods as $key => $value) {
    			$ps->bindValue($count++, $object->$value()/*, gettype($var) === 'integer' ? PDO::PARAM_INT ? PDO::PARAM_STR*/);
    			//echo $count++ . " - " . $value . " ";
    		}
    	}

    	return $ps;
    }

	public function fetch(PDOStatement $stmt, bool $option): array {
		return !$stmt->execute() ? [] : (!$option ? $stmt->fetch() : $stmt->fetchAll());
	}

	public function prepare(array $function): PDOStatement {
		$sql = function($function) {
			$tables = [
				"users" => "{$this->db_name}.users"
			];

			$sql_list = [
				"UsersModel" => [
					"createUser" => "CALL createUser(?, ?, ?, ?, ?, ?, ?)",
					"read_users_data" => "SELECT users.id_user AS id, CAST(AES_DECRYPT(users.document_user , UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS document, CAST(AES_DECRYPT(users.name_user , UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS name, CAST(AES_DECRYPT(users.lastName_user , UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS lastName, CAST(AES_DECRYPT(users.email_user , UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS email, CAST(AES_DECRYPT(users.phone_user , UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS phone, CAST(AES_DECRYPT(users.token_user , UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS token, CAST(AES_DECRYPT(users.state_user , UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS state FROM users",
					"read_user_exist" => "SELECT COUNT(id_user) AS 'exist' FROM users WHERE CAST(AES_DECRYPT(users.document_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) = ?",
					"update" => "UPDATE {$tables['users']} SET users_name=?, users_last_name=? WHERE idusers=?",
					"delete" => "DELETE FROM {$tables['users']} WHERE idusers=?"
				]
			];

			return $sql_list[$function[0]][$function[1]];
		};

		return $this->conn->prepare($sql($function));
	}

}