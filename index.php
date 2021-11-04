<?php

include_once 'manifest/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	if (!isset($_POST['form']) || $_POST['form'] === false) {
		$_POST = json_decode(file_get_contents("php://input"), true);
	}
}