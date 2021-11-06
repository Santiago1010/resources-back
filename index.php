<?php

include_once 'Manifest/Autoload.php';

use Src\Listener\Listener;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

	// Se valida si la información es enviada con un formData.
	if (!isset($_POST['form']) || $_POST['form'] === false) {
		$data = json_decode(file_get_contents("php://input"), true);
	}

	$listener = new Listener();
	$listener->actionListener($_SERVER['REQUEST_METHOD'], $_POST);
}else {
	//print_r(json_encode(["¿Qué verga haces aquí? Esto está prohibido para los mortales."], JSON_UNESCAPED_UNICODE));

	// create a log channel
	//$logger = new Logger('logs');
	//$logger->pushHandler(new StreamHandler('Src/Controllers/Logs/' . date('d-m-Y') . '_error.log', Logger::WARNING));
	//$logger->pushHandler(new FirePHPHandler());

	//$logger->warning('Adding a new user', ['xD']);
}