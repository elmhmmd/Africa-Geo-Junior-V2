<?php

class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $is_admin;

    public function __construct($id = null, $username = '', $email = '', $password = '', $is_admin = 0) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->is_admin = $is_admin;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getIsAdmin() { return $this->is_admin; }

    // Setters
    public function setUsername($username) { $this->username = $username; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setIsAdmin($is_admin) { $this->is_admin = $is_admin; }
    
}