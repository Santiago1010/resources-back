<?php

namespace Src\Models\Capsules;

/**
 * Clase que encapsula los datos de los usuarios.
 */
class UsersEntity {

    public function __construct(
        private ?int $id = NULL,
        private ?int $document = NULL,
        private ?string $name = NULL,
        private ?string $lastName = NULL,
        private ?string $email = NULL,
        private ?int $phone = NULL,
        private ?string $password = NULL,
        private ?string $token = NULL,
        private ?string $state = NULL
    ) {}
    
    public function getId() : int {
        return $this->id;
    }
    
    public function getDocument() : int {
        return $this->document;
    }
    
    public function getName() : string {
        return $this->name;
    }
    
    public function getLastName() : string {
        return $this->lastName;
    }
    
    public function getEmail() : string{
        return $this->email;
    }
    
    public function getPhone() : ?int {
        return $this->phone;
    }
    
    public function getPassword() : string {
        return $this->password;
    }
    
    public function getToken() : ?string {
        return $this->token;
    }
    
    public function getState() : string {
        return $this->state;
    }

}