<?php

/**
 * Clase que encapsula los datos de los impactos.
 */
class impactsClass {

	

	public function __construct(
		private ?int $id = NULL,
		private ?string $ambit = NULL,
		private ?string $expected = NULL,
		private ?string $pinter = NULL,
		private ?int $project = NULL
	) {}

    public function getId() : int {
        return $this->id;
    }

    public function getAmbit() : string {
        return $this->ambit;
    }

    public function getExpected() : string {
        return $this->expected;
    }

    public function getPinter() : string {
        return $this->pinter;
    }

    public function getProject() : int {
        return $this->project;
    }

}