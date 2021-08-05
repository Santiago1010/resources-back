<?php

class connection {

	private $host = "localhost";
	private $db_name = "aventura";
	private $user = "root";
	private $password = "";
	private $options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
	];

	private static $conexion = false;
	private $conn;
	
	private function __construct() {
		try {
			$this->conn = new PDO("mysql:host=" . ($this->host) . ";dbname=" . ($this->db_name) . ";" . "charset=utf8", $this->user, $this->password, $this->options);
		} catch (PDOException $e) {
			$this->conn = false;
		}
	}

	public static function getInstance() {
		if(!self::$conexion) {
			self::$conexion = new connection();
		}
		return self::$conexion;
	}

	public function getPrepareStatement($sql) {
		return $this->conn->prepare($this->getQuery($sql));
	}

	public function getBindValue(bool $inverted, $ps, Object $object, array $function) {
    	$methods = get_class_methods($object);
		$count = 1;
    	
    	if (!$inverted) { // Para traer los que s equieren.
    		foreach ($function as $key => $value) {
    			$ps->bindValue($count++, $object->$value(), $this->setType($object->$value()));
    		}
    	}else { // Para ignorar los otros.
    		$index = null;

    		for ($i = 0; $i < count($function); $i++) { 
    			$index = array_search($function[$i], $methods);
    			unset($methods[$index]);
    		}

    		$methods = array_values($methods);

    		foreach ($methods as $key => $value) {
    			$ps->bindValue($count++, $object->$value(), $this->setType($object->$value()));
    			//echo $count++ . " - " . $value . " ";
    		}
    	}

    	return $ps;
    }

	private function setType($var) {
		$type = gettype($var);
		switch ($type) {
			case 'integer':
				case 'boolean':
					return PDO::PARAM_INT;
					break;

			case 'string':
				return PDO::PARAM_STR;
				break;
			
			default:
				return PDO::PARAM_STR;
				break;
		}
	}

	public function getFetch($PreparedStatement, $option) {
		return $PreparedStatement->execute() ? (!$option ? $PreparedStatement->fetch() : $PreparedStatement->fetchAll()) : $PreparedStatement->errorInfo();
	}

	public function getExecute($PreparedStatement) {
		return $PreparedStatement->execute() ? true : $PreparedStatement->errorInfo();
	}

	private function getQuery($function) {
		$sql = [
			"UsersModel" => [
				"createUser" => "CALL createUser(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
				"readUserLoginData" => "SELECT COUNT(email_user) AS exist, users.name_user, users.lastName_user, users.birthday_user, users.gender_user, users.id_rol FROM users WHERE users.email_user = ? AND users.password_user = ? AND users.emailConfirm_user = 'Sí'"
			]
		];

		return $sql[$function[0]][$function[1]];
	}

}