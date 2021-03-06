<?php

namespace Src\Controllers\Traits;

trait Responses {

	private array $response = ["status" => "done", "result" => []];

	/*---------- RESPUESTAS DE ÉXTIO. ----------*/
	public function doneMessage(string $text = 'La acción se ha realizado con éxito.') : array {
		$this->response['status'] = "done";
		$this->response['result'] = ["id_error" => 200, "text_error" => $text];
		return $this->response;
	}

	/*---------- RESPUESTAS DE ERROR. ----------*/
	protected function genericError(string $text = 'La acción ha fallado.') : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 200, "text_error" => $text];
		return $this->response;
	}

	protected function error200(string $text = 'La acción se ha realizado con éxito.') : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 200, "text_error" => $text];
		return $this->response;
	}

	protected function error400() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 400, "text_error" => "La solicitud no es válida en:"];
		$this->setErrorLog(__FILE__, $this->response['result']['text_error']);
		return $this->response;
	}

	protected function error401() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 401, "text_error" => "No cuentas con autorización para realiar esta acción en:"];
		return $this->response;
	}

	protected function error403() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 403, "text_error" => "No tienes acceso a este recurso en:"];
		return $this->response;
	}

	protected function error404() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 404, "text_error" => "Página no encontrada en:"];
		return $this->response;
	}

	protected function error405() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 405, "text_error" => "Método no permitido en:"];
		return $this->response;
	}

	protected function error406() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 406, "text_error" => "Formato inválido de la información."];
		return $this->response;
	}

	protected function error409() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 409, "text_error" => "Se ha detectado un conflicto en:"];
		return $this->response;
	}

	protected function error415() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 415, "text_error" => "Content-Type no es compatible con el recurso de destino en:"];
		return $this->response;
	}

	protected function error500() : array {
		$this->response['status'] = "error";
		$this->response['result'] = ["id_error" => 500, "text_error" => "Error interno del servidor en:"];
		return $this->response;
	}
}