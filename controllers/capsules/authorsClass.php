<?php

/**
 * Clase que encapsula los datos de los autores.
 */
class authorsClass {

	public function __construct(
		private ?int $id = NULL,
		private ?string $name = NULL,
		private ?string $role = NULL,
		private ?int $project = NULL
	) {}

    public function getId() : int {
        return $this->title;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getRole() : string {
        return $this->role;
    }

    public function getProject() : int {
        return $this->project;
    }

}