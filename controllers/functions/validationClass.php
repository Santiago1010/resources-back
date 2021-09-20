<?php

/**
 * Esta clase hará todas las validaciones necesarias para el proyecto, incluyendo la encriptación de contraseñas y el token.
 */
class validationClass {

	protected function isEmpty(array $data, array $ignore = NULL) : bool {
		$val = true;

		if ($ignore != NULL) {
			for ($i = 0; $i < count($ignore); $i++) { 
				$index = array_diff($ignore, $data);
				unset($data[$index[$i]]);
			}

			$data = array_values($data);
		}

		$xd = '';

		foreach ($data as $key => $value) {
			if (!isset($data[$key]) || empty($data[$key])) {
				$val = false;
				break;
			}
		}

		return $val;
	}

	protected function validateEmail(string $email) : bool {
		return preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $this->noScapesStrings($email)) ? true : false;
	}

	protected function validateJustString(string $string) : bool {
		return is_string($this->noScapesStrings($string)) || $string == NULL ? true : false;
	}

	protected function validateJustNumbers(int $number) : bool {
		return is_numeric($this->noScapesStrings($number)) || $number == NULL ? true : false;
	}

	protected function validLong(string|int $data, int $min = 0, int $max = 99999999999999) : bool {
		return strlen($data) >= $min && strlen($data) <= $max ? true : false; 
	}

	protected function encryptPassword(string $string) : string {
		$password = md5($string);
		$pass = md5($password);
		return crypt($pass, $password);
	}

	protected function encryptToken(string $string) : string {
		$first = md5($string);
		$password = crypt($string, $first);

		$token = password_hash($password, PASSWORD_BCRYPT);

		$corregido = str_replace('/', '', $token);
		$corregido = str_replace('&', '', $corregido);
		$corregido = str_replace('$', '', $corregido);

		return md5($corregido);
	}

	protected function specialsPregs($regex, $validation, $thruty = true, $falsy = false) {
		return preg_match($regex, $validation) ? $thruty : $falsy;
	}

	protected function noScapesStrings(string $string) : string {
		$string = str_replace("[", '', $string);
		$string = str_replace("]", '', $string);
		$string = str_replace("^", '', $string);
		$string = str_replace("DELETE", '', $string);
		$string = str_replace("delete", '', $string);
		$string = str_replace("UPDATE", '', $string);
		$string = str_replace("update", '', $string);
		$string = str_replace("FROM", '', $string);
		$string = str_replace("from", '', $string);
		$string = str_replace("CALL", '', $string);
		$string = str_replace("call", '', $string);
		$string = str_replace("SELECT", '', $string);
		$string = str_replace("select", '', $string);
		$string = str_replace('DELETE FROM', '', $string);
		$string = str_replace('UPDATE ', '', $string);
		$string = str_replace('echo ', '', $string);
		$string = str_replace('var_dump', '', $string);
		$string = str_replace('print', '', $string);
		return $string;
	}

	protected function setMayus(string $data) : string	{
		$data = str_replace('á', 'Á', $data);
		$data = str_replace('é', 'É', $data);
		$data = str_replace('í', 'Í', $data);
		$data = str_replace('ó', 'Ó', $data);
		$data = str_replace('ú', 'Ú', $data);
		$data = str_replace('ñ', 'Ñ', $data);
		return $this->noScapesStrings(strtoupper($data));
	}

	protected function setNames(string $data) : string	{
		$data = strtolower($data);
		$data = str_replace('Á', 'á', $data);
		$data = str_replace('É', 'é', $data);
		$data = str_replace('Í', 'í', $data);
		$data = str_replace('Ó', 'ó', $data);
		$data = str_replace('Ú', 'ú', $data);
		$data = str_replace('Ñ', 'ñ', $data);
		return $this->noScapesStrings(ucwords($data));
	}

	protected function validEnum(string $data, ?string $type = NULl) : bool {
		switch ($type) {
			case '':
				//
				break;
			
			default:
				$enum = ['Sí', 'No'];
				break;
		}

		foreach ($enum as $p => $e) {
			$valid = $data === $e ? true : false;
		}

		return $valid;
	}

	protected function validBirthday(string $birthday)	{
		$b = explode('-', $birthday);
		$limit = date('Y') - 11;
		return (int)$b[0] <= $limit ? true : false;
	}

}