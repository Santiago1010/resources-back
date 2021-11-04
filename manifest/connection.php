<?php

namespace Manifest;

// Importar PDO.
use \PDO;
use \PDOStatement;
use \PDOException;

// Importar traits.
use Src\controllers\traits\singleton;

class connection {

	use singleton;

	private PDO $conn;

	private string $host = "localhost";
	private string $db_name = "new_valtec";
	private string $user = "root";
	private string $port = "3306";
	private string $password = ""/*"X(HLWFb[(Z/8etgc"*/;
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
				"users" => [
					"create" => "INSERT INTO {$tables['users']} (users_name, users_last_name) VALUES (?,?)",
					"read" => "SELECT * FROM {$tables['users']}",
					"update" => "UPDATE {$tables['users']} SET users_name=?, users_last_name=? WHERE idusers=?",
					"delete" => "DELETE FROM {$tables['users']} WHERE idusers=?"
				]
			];

			return $sql_list[$function[0]][$function[1]];
		};

		return $this->conn->prepare($sql($function));
	}

}