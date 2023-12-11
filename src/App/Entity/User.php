<?php

namespace App\Entity;

class User {
    private string $id;
    private string $login;
    private string $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getLogin() {
        return $this->login;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function setId(string $id) {
        $this->id = $id;
    }
    
    public function setLogin(string $login) {
        $this->login = $login;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }
}