<?php

namespace Src\models\capsules;

/**
 * Clase que encapsula los datos de los usuarios.
 */
class usersClass {

    public function __construct(
        private ?string $documentType = NULL,
        private ?int $document = NULL,
        private ?string $name = NULL,
        private ?string $lastName = NULL,
        private ?string $email = NULL,
        private ?int $phone = NULL,
        private ?string $password = NULL,
        private ?string $token = NULL,
        private ?string $emailConfirm = 'Sin confirmar',
        private ?string $rol = NULL,
        private ?int $regional = NULL,
        private ?int $center = NULL
    ) {}

    public function getDocumentType() : string {
        return $this->documentType;
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
    
    public function getToken() : string {
        return $this->token;
    }
    
    public function getEmailConfirm() : string {
        return $this->emailConfirm;
    }
    
    public function getRol() : string {
        return $this->rol;
    }
    
    public function getRegional() : int {
        return $this->regional;
    }
    
    public function getCenter() : int {
        return $this->center;
    }

}