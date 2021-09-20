<?php

/**
 * Clase que encapsula los datos de los proyectos.
 */
class projectsClass {

	public function __construct(
		private ?int $id = NULL,
		private ?string $title = NULL,
		private ?string $fisrt = NULL,
		private ?string $second = NULL,
		private ?string $municipality = NULL,
		private ?string $description = NULL,
		private ?string $post = NULL,
		private ?string $justification = NULL,
		private ?string $state = NULL,
		private ?string $bibliography = NULL
	) {}

    public function getId() : int {
        return $this->title;
    }

    public function getTitle() : string {
        return $this->title;
    }

    public function getFisrt() : string {
        return $this->fisrt;
    }

    public function getSecond() : ?string {
        return $this->second;
    }

    public function getMunicipality() : string {
        return $this->municipality;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function getPost() : ?string {
        return $this->post;
    }

    public function getJustification() : string {
        return $this->justification;
    }

    public function getState() : string {
        return $this->state;
    }

    public function getBibliography() : string {
        return $this->bibliography;
    }

}