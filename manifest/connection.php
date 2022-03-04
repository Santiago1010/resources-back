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

	private string $host = 'localhost';
	private string $db_name = 'new_valtec';
	private string $user = 'root';
	private string $password = '';
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
    			$ps->bindValue($count++, $object->$value());
    			echo $count++ . " - " . $value . " ";
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
				"createUser" => "CALL createUsers(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", // Registrar usuario.
				"createTemporaryUser" => "CALL createTemporaryUser(?, ?)", // Se crea un usuario temporal.
				"confirmUsers" => "CALL confirmUsers(?)", // Confirmar correo electrónico del usuario.
				"read_user_password" => "SELECT password FROM new_valtec.read_user_password WHERE read_user_password.document_user = ?", // Leer el hash de la contraseña.
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

		return $sql_list[$function[0]][$function[1]];
	};

	return $this->conn->prepare($sql($function));
}

}