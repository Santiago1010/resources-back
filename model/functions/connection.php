<?php

class connection {

	private $host = "localhost";
	private $db_name = "idi";
	private $user = "root";
	private $password = "";
	private $options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
	];
	private $rol;

	private static $conexion = false;
	private $conn;
	
	private function __construct(?string $rol = NULL) {
		$this->rol = $rol;
		$this->setPermissions();
		try {
			$this->conn = new PDO("mysql:host=" . ($this->host) . ";dbname=" . ($this->db_name) . ";" . "charset=utf8", $this->user, $this->password, $this->options);
		} catch (PDOException $e) {
			$this->conn = false;
		}
	}

	public static function getInstance(?string $rol = NULL) {
		if(!self::$conexion) {
			self::$conexion = new connection($rol);
		}
		return self::$conexion;
	}

	private function setPermissions() : void {
		switch ($this->rol) {
			case 'value':
				$this->user = "";
				$this->password = "";
				break;
			
			default:
				$this->user = "root";
				$this->password = "";
				break;
		}
	}

	public function getPrepareStatement($sql) {
		return $this->conn->prepare($this->getQuery($sql));
	}

	public function getBindValue(bool $inverted, $ps, Object $object, array $function) {
    	$methods = get_class_methods($object);
		$count = 1;
    	
    	if (!$inverted) { // Para traer sólo los que se quieren.
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
				"createUser" => "CALL createUser(?, ?, ?, ?, ?, ?, ?)", // Se registra el usuario.
				"read_user_password" => "SELECT CAST(AES_DECRYPT(idi.users.password_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS password FROM idi.users WHERE CAST(AES_DECRYPT(idi.users.email_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) = ?", // Se verifica la contraseña.
				"read_user_exist" => "SELECT COUNT(idi.users.id_user) AS exist FROM idi.users WHERE CAST(AES_DECRYPT(idi.users.email_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) = ?", // Leer si un usuario ya existe.
				"read_user_data" => "SELECT idi.users.id_user AS id, CAST(AES_DECRYPT(idi.users.email_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS email, CAST(AES_DECRYPT(idi.users.password_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS password, CAST(AES_DECRYPT(idi.users.name_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS name, CAST(AES_DECRYPT(idi.users.lastName_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) AS lastName, idi.users.dateBorn_user AS birthday, idi.users.id_school AS school, idi.users.id_rol AS rol FROM idi.users WHERE idi.users.confirmEmail_user = 'Confirmado' AND CAST(AES_DECRYPT(idi.users.email_user, UNHEX('07FB945926849D2B1641E708C85E4390')) AS CHAR (255) CHARSET UTF8MB4) = ?", // Leer los datos de los usuarios.
				"confirmUser" => "CALL confirmUser(?)", // Confirmar usuario.
				"setToken" => "setToken(?)" // Generar nuevo token.
			],
			"ProjectsModel" => [
				"createProject" => "CALL createProject(?, ?, ?, ?, ?, ?, ?, ?, ?)", // Registrar la información básica de un proyecto.
				"createObjectives" => "CALL createObjectives(?, ?, ?, ?)" // Regiustrar objetivos.
			]
		];

		return $sql[$function[0]][$function[1]];
	}

}