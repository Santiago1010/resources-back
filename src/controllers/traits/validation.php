<?php

namespace Src\Controllers\Traits;

trait Validation {
	protected function isEmpty(int|string $data, array $ignore = NULL) : bool {
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
		return preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $this->resetSpecialStrings($email)) ? true : false;
	}

	protected function validateJustString(string $string) : bool {
		return is_string($this->resetSpecialStrings($string));
	}

	protected function validateJustNumbers(int $number) : bool {
		return is_numeric($this->resetSpecialStrings($number));
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

	protected function specialsPregs($regex, $validation, $thruty = true, $falsy = false) : int|string|bool {
		return preg_match($regex, $validation) ? $thruty : $falsy;
	}

	protected function resetSpecialStrings(int|string $data) : int|string {
		$data = str_replace('/', '', $data);
		$data = str_replace('&', '', $data);
		$data = str_replace('$', '', $data);
		$data = str_replace('<', '', $data);
		$data = str_replace('>', '', $data);
		$data = str_replace('DELETE FROM', '', $data);
		$data = str_replace('UPDATE ', '', $data);
		$data = str_replace('echo ', '', $data);
		$data = str_replace('var_dump', '', $data);
		$data = str_replace('print ', '', $data);
		return htmlspecialchars($data, ENT_QUOTES);
	}

	protected function setMayus(string $data) : string	{
		$data = str_replace('á', 'Á', $data);
		$data = str_replace('é', 'É', $data);
		$data = str_replace('í', 'Í', $data);
		$data = str_replace('ó', 'Ó', $data);
		$data = str_replace('ú', 'Ú', $data);
		$data = str_replace('ñ', 'Ñ', $data);
		return $this->resetSpecialStrings(strtoupper($data));
	}

	protected function validEnum(string $data, ?string $type = NULl) : bool {
		switch ($type) {
			case 'documentType':
				$enum = ['R.C.', 'T.I.', 'C.C.', 'T.E.', 'C.E.', 'NIT', 'Pasaporte'];
				break;

			case 'codeType':
				$enum = ['SGPS', 'SIGP', 'Iniciativa de centro'];
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
}