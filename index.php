<?php

include_once 'Manifest/Autoload.php';

use Src\Listener\Listener;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

	$listener = new Listener();

	// Se valida si la información es enviada con un formData.
	if (!isset($_POST['form']) || $_POST['form'] === false) {
		$data = json_decode(file_get_contents("php://input"), true);
	}

	// Si entra por POST, es de lectura y actualización.
	switch ($_SERVER['REQUEST_METHOD']) {
		case 'POST': // Si es método es POST, puede ser para lectura y actualización (RU).
			// code...
			break;

		case 'PUT': // Si es método es PUT, puede ser para crear datos (C).
			// code...
			break;

		case 'DELETE': // Si es método es DELETE, puede ser para eliminar datos (D).
			// code...
			break;
	}
}else {
	print_r(json_encode(["¿Qué verga haces aquí? Esto está prohibido para los mortales."], JSON_UNESCAPED_UNICODE));
}