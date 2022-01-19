<?php

/**
 * Clase que encapsula los datos de los resultados de investigación.
 */
class ResearchResultEntity {

	public function __construct(
		private ?int $id = NULL,
		private ?string $projectName = NULL,
		private ?string $techName = NULL,
		private ?int $year = NULL,
		private ?string $codeType = NULL,
		private ?string $code = NULL,
		private ?string $summary = NULL,
		private ?int $trl = NULL,
		private ?string $tipology = NULL,
		private ?string $groupTipology = NULL,
		private ?string $knowledeArea = NULL,
		private ?string $subKnowledgeNetwork = NULL,
		private ?string $knowledgeNetwork = NULL,
		private ?string $dateStart = NULL,
		private ?string $lastModification = NULL,
		private ?string $rights = NULL,
		private ?int $regional = NULL,
		private ?int $center = NULL,
		private ?int $group = NULL
	) {}

    public function getId(): ?int {
        return $this->id;
    }

    public function getProjectName(): ?string {
        return $this->projectName;
    }

    public function getTechName(): ?string {
        return $this->techName;
    }

    public function getYear(): ?int {
        return $this->year;
    }

    public function getCodeType(): ?string {
        return $this->codeType;
    }

    public function getCode(): ?string {
        return $this->code;
    }

    public function getSummary(): ?string {
        return $this->summary;
    }

    public function getTrl(): ?int {
        return $this->trl;
    }

    public function getTipology(): ?string {
        return $this->tipology;
    }

    public function getGroupTipology(): ?string {
        return $this->groupTipology;
    }

    public function getKnowledeArea(): ?string {
        return $this->knowledeArea;
    }

    public function getSubKnowledgeNetwork(): ?string {
        return $this->subKnowledgeNetwork;
    }

    public function getKnowledgeNetwork(): ?string {
        return $this->knowledgeNetwork;
    }

    public function getDateStart(): ?string {
        return $this->dateStart;
    }

    public function getLastModification(): ?string {
        return $this->lastModification;
    }

    public function getRights(): ?string {
        return $this->rights;
    }

    public function getRegional(): ?int {
        return $this->regional;
    }

    public function getCenter(): ?int {
        return $this->center;
    }

    public function getGroup(): ?int {
        return $this->group;
    }

}