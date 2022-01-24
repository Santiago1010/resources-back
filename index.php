<?php

include_once 'Manifest/Autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;

use Src\Controllers\UsersController;
use Src\Controllers\ResultsController;

(Dotenv\Dotenv::createImmutable(__DIR__))->load();

$router = new RouteCollector();

$router->get("/", function() {
	return "No puedes estar aquí.";
});

// Funciones con el método POST, para Crear (C).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$router->group(['before' => 'update'], function($router) {
		$router->post('users', function() {
			$_POST = json_decode(file_get_contents("php://input"), true);
			$user = new UsersController();
			extract($_POST['userData']);
			return $user->readUsers();
		});
	});

	$router->group(['prefix' => 'user'], function($router) {
		$router->post('login', [Src\Controllers\UsersController::class, "loginUser"]);
	});
}

// Funciones con el método GET, para Leer (R).
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	// LLamar lista de usuarios.
	$router->group(['before' => 'read'], function($router) {
		$router->get('users', function() {
			print_r((new UsersController())->readUsers());
		});
	});

	// Llamar lista de resultados de investigación.
	$router->group(['before' => 'read'], function($router) {
		$router->get('results/{document}', function($document) {
			print_r((new ResultsController())->readResearchResults((int)$document));
		});
	});

	$router->group(['prefix' => 'get'], function($router) {
		$router->get('token', function() {
			print_r(password_hash($_ENV['ORIGIN'] . $_ENV['TOKEN_API'], PASSWORD_DEFAULT, ['cost' => 10]));
		});
	});
}

// Funciones con el método PUT, para Actualizar (U).
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