<?php

/**
 * Clase que encapsula los datos de las escuelas.
 */
class schoolsClass {

	public function __construct(
		private ?int $id = NULL,
		private ?string $name = NULL,
		private ?string $municipality = NULL
	) {}

    public function getId() : int {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getMunicipality() : string {
        return $this->municipality;
    }

}