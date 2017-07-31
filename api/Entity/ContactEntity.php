<?php

namespace Entity;

class ContactEntity
{
    private $id;
    private $name;
    private $email;
    private $phone;

    public function __construct($arrayContact = []) {
        $this
            ->setId(isset($arrayContact['id']) ? $arrayContact['id'] : null)
            ->setName(isset($arrayContact['name']) ? $arrayContact['name'] : '')
            ->setEmail(isset($arrayContact['email']) ? $arrayContact['email'] : '')
            ->setPhone(isset($arrayContact['phone']) ? $arrayContact['phone'] : '');
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        if (is_string($this->name) && !is_null($this->name))
            return $this->name;
    }

    public function setName($name) {
        $this->name= $name;
        return $this;
    }

    public function getEmail() {
        if (is_string($this->email) && !is_null($this->name)) {
            $this->email = filter_var($this->email, FILTER_VALIDATE_EMAIL);
            
            return $this->email;
        }
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getPhone() {
        if (is_string($this->phone) && !is_null($this->phone))
            return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }
}