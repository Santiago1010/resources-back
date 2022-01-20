<?php

include_once 'Manifest/Autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;

use Src\Controllers\UsersController;
use Src\Controllers\ResultsController;

$dot = Dotenv\Dotenv::createImmutable(__DIR__);
$dot->load();

$router = new RouteCollector();

$router->get("/", function() {
	return "No puedes estar aquí.";
});

// Funciones con el método PUT, para Crear (C).
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
	$router->group(['before' => 'create'], function($router) {
		$router->get('entrada', function() {
			return 'Bienvenido a la entrada';
		});

		$router->any('user', function() {
			$_POST = json_decode(file_get_contents("php://input"), true);
			$user = new UsersController();
			extract($_POST['userData']);
			return $user->createUser($document, $name, $lastName, $email, $phone, 'Amo_Bleach.4');
		});
	});
}

// Funciones con el método GET, para Leer (R).
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	// LLamar list de usuarios.
	$router->group(['before' => 'read'], function($router) {
		$router->get('users', function() {
			$user = new UsersController();
			return $user->readUsers();
		});
	});

	// Llamar lista de resultados de investigación.
	$router->group(['before' => 'read'], function($router) {
		$router->get('results/{document}', function($document) {
			$ri = new ResultsController();
			print_r($ri->readResearchResults((int)$document));
		});
	});
}

// Funciones con el método POST, para Actualizar (U).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$router->group(['before' => 'update'], function($router) {
		$router->post('users', function() {
			$_POST = json_decode(file_get_contents("php://input"), true);
			$user = new UsersController();
			extract($_POST['userData']);
			return $user->readUsers();
		});
	});
}

// Funciones con el método DELETE, para Actualizar (D).
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
	$router->group(['before' => 'DELETE'], function($router) {
		$router->delete('users', function() {
			$_POST = json_decode(file_get_contents("php://input"), true);
			$user = new UsersController();
			extract($_POST['userData']);
			return $user->readUsers();
		});
	});
}

function dispatcher(Dispatcher $dispatcher): void {
	try {
		print_r($dispatcher->dispatch(
			$_SERVER['REQUEST_METHOD'], 
			processInput($_SERVER["REQUEST_URI"])
		));
	} catch (HttpRouteNotFoundException $e) {
		print_r("Error: Ruta no encontrada");
	} catch (HttpMethodNotAllowedException $e) {
		print_r("Error: Ruta encontrada pero método no permitido");
	}
}

function processInput($uri) {
	return implode('/', array_slice(explode('/', $uri), 3));
}

dispatcher(new Dispatcher($router->getData()));