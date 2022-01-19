<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-type: application/json');

ini_set("default_charset", "UTF-8");

date_default_timezone_set('America/Bogota');

spl_autoload_register(function(string $clase) {
	include_once $clase . '.php';
});

require_once 'resources/libraries/vendor/autoload.php';

//require_once 'Resources/Libraries/vendor/devster/ubench/src/Ubench.php';