<?php

namespace Src\Controllers;

/**
 * Controlador que controla. :U
 */
class Controller {

	public function getToken() : string {
		/*$_SESSION['csrf_token'] = */return bin2hex(random_bytes(32));
		//print_r($_SESSION['csrf_token']);
	}

	public static function validateToken($data): bool {
		if (isset($data['csrf_token'], $_SESSION["csrf_token"])) {
			if (!empty($data["csrf_token"])) {
				return $data['csrf_token'] === $_SESSION["csrf_token"] ? true : false;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public static function fileGetContents() {
		return json_decode(file_get_contents("php://input"), true);
	}

}