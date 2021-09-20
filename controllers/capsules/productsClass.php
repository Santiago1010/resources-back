<?php

/**
 * Clase que encapsula los datos de los productos.
 */
class productsClass {

	public function __construct(
		private ?int $id = NULL, 
		private ?string $text = NULL, 
		private ?int $result = NULL
	) {}

    public function getId() : int {
        return $this->id;
    }

    public function getText() : string {
        return $this->text;
    }

    public function getResult() : int {
        return $this->result;
    }

}