<?php

class connection {

	private $host = "localhost";
	private $db_name = "new_valtec";
	private $user = "readRol";
	private $password = "X(HLWFb[(Z/8etgc";
	private $options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
	];

	private static $conexion = false;
	private $conn;
	
	// Se inicia la conexión.
	private function __construct(?string $rol) {
		$this->setConnection($rol);
		try {
			$this->conn = new PDO("mysql:host=" . ($this->host) . ";dbname=" . ($this->db_name) . ";" . "charset=utf8", $this->user, $this->password, $this->options);
		} catch (PDOException $e) {
			$this->conn = false;
		}
	}

	public static function getInstance(?string $rol) {
		if(!self::$conexion) {
			self::$conexion = new connection($rol);
		}
		return self::$conexion;
	}

	private function setConnection(?string $rol) : void {
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
				"createUser" => "CALL createUsers(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", // Registrar usuario.
				"createTemporaryUser" => "CALL createTemporaryUser(?, ?)", // Se crea un usuario temporal.
				"confirmUsers" => "CALL confirmUsers(?)", // Confirmar correo electrónico del usuario.
				"read_user_password" => "SELECT password_user FROM new_valtec.read_user_password WHERE read_user_password.document_user = ?", // Leer el hash de la contraseña.
				"read_user_data" => "SELECT * FROM new_valtec.read_user_data WHERE read_user_data.document = ? AND emailConfirm = 'Confirmado'", // Leer los datos relevantes del usuario.
				"read_user_exist" => "SELECT COUNT(document) AS countExist FROM new_valtec.read_user_data WHERE document = ?", // Se verifica si el usuario existe.
				"read_users_data" => "SELECT * FROM new_valtec.read_user_data", // Leer los datos relevantes de todos los usuarios.
				"read_temporary_users" => "SELECT * FROM new_valtec.read_temporary_user", // Leer los datos de los usuarios temporales. 
				"read_temporary_user_exist" => "SELECT COUNT(read_temporary_user.document) AS countTemporary FROM new_valtec.read_temporary_user WHERE read_temporary_user.document = ?", // Leer si l usuario temporal ya se encuentra registrado.
				"updateUser" => "CALL updateUser(?, ?, ?, ?, ?)", // Actualizar los datos del usuario.
				"setToken" => "CALL setToken(?, ?)", // Generar token.
				"updatePassword" => "CALL updatePassword(?, ?)", // Actualizar contraseña.
				"deleteUser" => "CALL deleteUser(?)" // Eliminar usuarios.
			],
			"LocationsModel" => [
				"readRegionals" => "SELECT * FROM new_valtec.read_regionals", // Se llaman las regionales.
				"readCenters" => "SELECT * FROM new_valtec.read_centers WHERE read_centers.id_regional = ?", // Se llaman los centros de formación.
				"readInvestigationGroups" => "SELECT * FROM new_valtec.read_investigation_groups WHERE read_investigation_groups.id_center = ?" // Se leen los grupos de investigación.
			],
			"StatsModel" => [
				"readRegisterResults" => "SELECT COUNT(read_stats_results.id_proyecto) AS total FROM valtec.read_stats_results",
				"readResultsBigger" => "SELECT COUNT(read_stats_results.id_proyecto) AS total FROM valtec.read_stats_results WHERE read_stats_results.trl_proyecto = 'TRL 7' OR read_stats_results.trl_proyecto = 'TRL 8' OR read_stats_results.trl_proyecto = 'TRL 9'",
				"readTotalUsers" => "SELECT COUNT(documento_usuario) AS total FROM read_users"
			],
			"ResultsModel" => [
				"createResearchResults" => "CALL createResearchResult(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", // Se registra el resultado de investigación y se devuelve el id.
				"ceateRelationInvestigatorRI" => "CALL ceateRelationInvestigatorRI(?, ?, ?, ?)", // Crear la relación entre investigadores y RI.
				"read_own_research_result_a" => "SELECT * FROM new_valtec.read_research_results WHERE read_research_results.document = ? AND read_research_results.rolI != 'Ingreso de información'", // Se leen los resultados de investigación propios.
				"read_other_research_result_a" => "SELECT * FROM new_valtec.read_research_results WHERE (read_research_results.document != ? OR read_research_results.document IS NULL)", // Se leen los resultados de investigación ajenos.
				"read_own_research_result_b" => "SELECT * FROM new_valtec.read_research_results WHERE read_research_results.document = ? AND read_research_results.id_regional = ? AND read_research_results.rolI != 'Ingreso de información'", // Se leen los resultados de investigación propios.
				"read_other_research_result_b" => "SELECT * FROM new_valtec.read_research_results WHERE (read_research_results.document != ? OR read_research_results.document IS NULL) AND read_research_results.id_regional = ?", // Se leen los resultados de investigación propios.
				"read_research_result_c" => "SELECT * FROM new_valtec.read_research_results WHERE read_research_results.document = ?", // Se leen los resultados de investigación propios.
				"read_research" => "SELECT * FROM new_valtec.read_research_results WHERE read_research_results.id = ? GROUP BY read_research_results.id", // Se leen los resultados de investigación propios.
				"read_relation_investigators" => "SELECT * FROM new_valtec.read_relation_investigators WHERE read_relation_investigators.ri = ?", // Se llaman los investigadores de u RI específico.
				"updateResearchResult" => "CALL updateResearchResult(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", // Se actualizan los resultados de investigación.
				"deleteResearchResult" => "CALL deleteResearchResult(?)" // Se elimina el resultado de investigación.
			],
			"SelectsModel" => [
				"setChecks" => "CALL setChecks(?)" // Se agrega la fila de los checks.
			],
			"TrlModels" => [
				"read_state_trl" => "SELECT * FROM new_valtec.read_state_trl WHERE read_state_trl.id_ri = ?", // Se llama el estado actual de la TRL.
				"updateTrl" => "CALL updateTrl(?, ?)", // Se actualiza la TRL.
				"updateChecks" => "CALL updateSelect(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" // Se actualizan las selecciones.
			],
			"ValidationModel" => [
				"createValidationRI" => "CALL createValidationRI(?)", // Se crea el módulo de validación.
				"read_validation_ri" => "SELECT * FROM new_valtec.read_validation_ri WHERE read_validation_ri.id_ri = ?", // Se leen los datos de validación.
				"updateValidationRI" => "CALL updateValidationRI(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" // Se actualizan los datos de la validación.
			]

		];

		return $sql[$function[0]][$function[1]];
	}

}