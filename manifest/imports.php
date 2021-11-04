<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-type: application/json');

ini_set("default_charset", "UTF-8");

date_default_timezone_set('America/Bogota');

// Importar los traits.
include_once '../controllers/traits/validation.php';
include_once '../controllers/traits/singleton.php';
include_once '../controllers/traits/security.php';
include_once '../controllers/traits/files.php';

// Importar la conexión.
include_once '../../manifest/connection.php';

// Importar los modelos.
include_once '../models/usersModel.php';

// Importar los recibidores.
include_once '../receivers/receptionUsers.php';

// Importar los controladores.
include_once '../controllers/usersController.php';