<?php

/**
 * Clase que encapsula los datos de los usuarios.
 */
class usersClass {

	public function __construct(
		private ?int $id = NULL,
		private ?string $email = NULL,
		private ?string $password = NULL,
		private ?string $name = NULL,
		private ?string $lastName = NULL,
		private ?string $dateBorn = NULL,
		private ?string $confirmEmail = NULL,
		private ?string $token = NULL,
		private ?int $school = NULL,
		private ?int $rol = NULL
	) {}

    public function getId() : int {
        return $this->id;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getLastName() : string {
        return $this->lastName;
    }

    public function getDateBorn() : string {
        return $this->dateBorn;
    }

    public function getConfirmEmail() : string {
        return $this->confirmEmail;
    }

    public function getToken() : ?string {
        return $this->token;
    }

    public function getSchool() : int {
        return $this->school;
    }

    public function getRol() : int {
        return $this->rol;
    }

}