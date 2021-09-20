<?php

/**
 * Clase que encapsula los objetivos.
 */
class objectivesClass {

	public function __construct(
		private ?int $id = NULL,
		private ?string $verb = NULL,
		private ?string $text = NULL,
		private ?string $type = NULL,
		private ?int $project = NULL

	) {
		$this->id = $id;
		$this->verb = $verb;
		$this->text = $text;
		$this->type = $type;
		$this->project = $project;
	}

    public function getId() : int {
        return $this->id;
    }

    public function getVerb() : string {
        return $this->verb;
    }

    public function getText() : string {
        return $this->text;
    }

    public function getType() : string {
        return $this->type;
    }

    public function getProject() : int {
        return $this->project;
    }

}