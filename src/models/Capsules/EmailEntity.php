<?php

namespace Src\Models\Capsules;

class EmailEntity {

	public function __construct(
		private ?string $addAddress = NULL, 
		private ?string $addReplyTo = NULL, 
		private ?string $addCC = NULL, 
		private ?string $addBCC = NULL, 
		private ?string $addAttachment = NULL, 
		private ?string $Subject = NULL, 
		private ?string $Body = NULL, 
		private ?string $AltBody = NULL
	) {}

    public function getAddAddress() {
        return $this->addAddress;
    }

    public function setAddAddress($addAddress) {
        $this->addAddress = $addAddress;
        return $this;
    }

    public function getAddReplyTo() {
        return $this->addReplyTo;
    }

    public function setAddReplyTo($addReplyTo) {
        $this->addReplyTo = $addReplyTo;
        return $this;
    }

    public function getAddCC() {
        return $this->addCC;
    }

    public function setAddCC($addCC) {
        $this->addCC = $addCC;
        return $this;
    }

    public function getAddBCC() {
        return $this->addBCC;
    }

    public function setAddBCC($addBCC) {
        $this->addBCC = $addBCC;
        return $this;
    }

    public function getAddAttachment() {
        return $this->addAttachment;
    }

    public function setAddAttachment($addAttachment) {
        $this->addAttachment = $addAttachment;
        return $this;
    }

    public function getSubject() {
        return $this->Subject;
    }

    public function setSubject($Subject) {
        $this->Subject = $Subject;
        return $this;
    }

    public function getBody() {
        return $this->Body;
    }

    public function setBody($Body) {
        $this->Body = $Body;
        return $this;
    }

    public function getAltBody() {
        return $this->AltBody;
    }

    public function setAltBody($AltBody) {
        $this->AltBody = $AltBody;
        return $this;
    }

}