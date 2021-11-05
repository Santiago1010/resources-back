<?php

namespace Src\Controllers\Traits;

/**
 * Trait que gestionará todos los archivos.
 */
trait Files {

	private $newName;
	private $extension;
	private $error;

	protected function __construct($name, $type, $error) {
		$this->getExtension($name);
		$this->setNewName($type);
		$this->error = $error;
	}

	protected function uploadFile($temp, $route) {
		$error = $this->validateError($this->error);
		if ($error == 0) {
			if (!is_dir($route)) {
				mkdir($route, 0777, true);
			}
			return move_uploaded_file($temp, $route . '/' . $this->newName) ? $this->newName : 'Error al subir el archivo al servidor.';
		}else {
			return $error;
		}
	}

	protected function validateError($error) {
		switch ($error) {
			case 0:
				return 0;
				break;

			case 1:
				return 'El archivo excede el límite de espacio permitido por el servidor.';
				break;

			case 2:
				return 'El archivo excede el límite de espacio permitido por el servidor.';
				break;

			case 3:
				return 'Error: El archivo fue parcialmente subido.';
				break;

			case 4:
				return 'No se ha subido ningún archivo.';
				break;
			
			default:
				return 0;
				break;
		}
	}

	protected function validateSize($file, $min = 0, $max = 20971520) {
		return filesize($file) >= $min && filesize($file) <= $max ? true : false;
	}

	protected function validateWeight($file, $minW = 0, $maxW = 10000, $minH = 0, $maxH = 10000) {
		$image = getimagesize($file);
		return $image[0] >= $minW && $image[0] <= $maxW && $image[1] >= $minH && $image[1] <= $image[1] ? true : false;
	}

	protected function validateImages($expected = ['PNG', 'JPG']) {
		$valid = false;
		foreach ($expected as $key => $e) {
			if (strtolower($this->extension) == strtolower($e)) $valid = true;
		}
		return $valid;
	}

	private function getExtension($name)	{
		$e = explode('.', $name);
		$this->extension = end($e);
	}

	private function setNewName($type) {
		$type = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), 'a', $type);
		$type = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), 'e', $type);
		$type = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), 'i', $type);
		$type = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), 'o', $type);
		$type = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), 'u', $type);
		$type = str_replace(array('ñ', 'Ñ'), 'n', $type);
		$type = str_replace(array('ç', 'Ç'), 'c', $type);
		$type = ucwords($type);

		$type = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $type);

		$this->newName = uniqid() . $this->withoutSpaces($type) . '.' . $this->extension;
	}

	private function withoutSpaces($type) {
		$type = ucwords($type);
		return str_replace(' ', '', $type);
	}

	protected function getExtensionExtern() {
		return $this->extension;
	}

}