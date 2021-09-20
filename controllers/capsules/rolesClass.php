<?php

/**
 * Clase que encapsula los datos de los roles.
 */
class rolesClass {

	public function __construct(
		private ?int $id = NULL,
		private ?string $name = NULL
	) {}

    public function getId() : int {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }
}