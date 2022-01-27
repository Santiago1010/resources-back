<?php

namespace Src\Controllers\Traits;

trait Error {

	private string $route;

	protected function setErrorLog(string $file, string $error = 'Se ha registrado un error en:') : bool {
		$this->route = './Src/Controllers/Logs/' . date('d-m-Y') . '_error.log';
		return error_log("\n" . date('Y-m-d H:i:s') . " - {$error} " . $file . "\n", 3, $this->route);
	}
}