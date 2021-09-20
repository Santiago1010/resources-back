<?php

/**
 * Cla<se que encapsula los datos de los resultados.
 */
class resultsClass {

	

	public function __construct(
		private ?int $id = NULL,
		private ?string $text = NULL,
		private ?int $objective = NULL,
		private ?int $project = NULL
	) {}

    public function getId() : int {
        return $this->id;
    }

    public function getText() : string {
        return $this->text;
    }

    public function getObjective() : int {
        return $this->objective;
    }

    public function getProject() : int {
        return $this->project;
    }

}