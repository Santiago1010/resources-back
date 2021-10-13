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
				"createObjectives" => "CALL createObjectives(?, ?, ?, ?)", // Regiustrar objetivos.
				"createAuthors" => "CALL createAuthors(?, ?, ?)", // Se crean los autores.
				"createImpacts" => "CALL createImpacts(?, ?, ?, ?)", // Se crean los impactos.
				"createResults" => "CALL createResults(?, ?, ?)", // Crear resultados.
				"createProducts" => "CALL createProducts(?, ?)", // Crear productos.
				"read_project_basic_data" => "SELECT projects.id_project AS id, CAST(AES_DECRYPT(projects.title_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS title, CAST(AES_DECRYPT(projects.firstKnowledgeArea_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS first, CAST(AES_DECRYPT(projects.secondKnowledgeArea_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS second, CAST(AES_DECRYPT(projects.municipality_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS municipality, CAST(AES_DECRYPT(projects.descriptionStrategy_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS description, CAST(AES_DECRYPT(projects.postConflictResources_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS post, CAST(AES_DECRYPT(projects.justification_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS justification, CAST(AES_DECRYPT(projects.stateArt_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS state, CAST(AES_DECRYPT(projects.bibliography_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS bibliography FROM projects WHERE projects.id_project = ?", // Leer los datos básicos del proyecto.
				"read_authors" => "SELECT authors.id_author AS id, CAST(AES_DECRYPT(authors.name_author, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS name, authors.role_author AS rol, authors.id_project AS project, projects.title_project AS title FROM (authors JOIN projects ON (projects.id_project = authors.id_project)) WHERE authors.id_project = ?", // Leer los autores de cada proyecto.
				"read_impacts" => "SELECT impacts.id_impact AS id, CAST(AES_DECRYPT(impacts.ambit_impact, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS ambit, CAST(AES_DECRYPT(impacts.expectedEffect_impact, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS expected, CAST(AES_DECRYPT(impacts.pinter_impact, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS pinter, impacts.id_project AS project, CAST(AES_DECRYPT(projects.title_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS title FROM impacts INNER JOIN projects ON projects.id_project = impacts.id_project WHERE impacts.id_project = ?", // Leer los impactos.
				"read_objectives" => "SELECT CAST(AES_DECRYPT(objectives.verb_objective, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS verb, CAST(AES_DECRYPT(objectives.text_objective, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS text, objectives.type_objective AS type, objectives.id_project AS project, CAST(AES_DECRYPT(projects.title_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS title FROM objectives INNER JOIN projects ON projects.id_project = objectives.id_project WHERE objectives.id_project = ?", // Se leen los objetivos.
				"read_products" => "SELECT products.id_product AS id, CAST(AES_DECRYPT(products.text_product, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS text, products.id_result AS result, CAST(AES_DECRYPT(results.text_result, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS rText FROM products INNER JOIN results ON results.id_result = products.id_result; WHERE products.id_result = ?", // Leer productos.
				"read_results" => "SELECT results.id_result AS id, CAST(AES_DECRYPT(results.text_result, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS text, results.id_objective AS objective, CAST(AES_DECRYPT(objectives.verb_objective, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS verb, CAST(AES_DECRYPT(objectives.text_objective, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS textO, objectives.type_objective AS type, results.id_project AS project, CAST(AES_DECRYPT(projects.title_project, UNHEX('AF6B5E4E39F974B3F3FB0F22320CC60B')) AS CHAR (255) CHARSET UTF8MB4) AS title FROM results INNER JOIN objectives ON objectives.id_objective = results.id_objective INNER JOIN projects ON projects.id_project = results.id_project WHERE objectives.id_objective = ? AND results.id_project = ?" // Se leen los resultados.
			]
		];

		return $sql[$function[0]][$function[1]];
	}

}