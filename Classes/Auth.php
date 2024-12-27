<?php
require_once 'Database.php';
require_once 'User.php';

class Auth {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            return true;
        }
        return false;
    }

    public function register($username, $email, $password) {
        // Check if email exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            return false;
        }

        // Check if username exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch()) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (:username, :email, :password, 0)");
        
        if ($stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword
        ])) {
            $userId = $this->db->lastInsertId();
            return new User($userId, $username, $email, $hashedPassword, 0);
        }
        return false;
    }

    public function validateRegistration($username, $email, $password, $confirm_password) {
        $errors = [];
        
        // Username validation
        if (empty($username)) {
            $errors[] = "Le nom d'utilisateur est requis";
        } else {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetch()) {
                $errors[] = "Ce nom d'utilisateur existe déjà";
            }
        }

        // Email validation
        if (empty($email)) {
            $errors[] = "L'email est requis";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        } else {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $errors[] = "Cet email existe déjà";
            }
        }

        // Password validation
        if (empty($password)) {
            $errors[] = "Le mot de passe est requis";
        } elseif (strlen($password) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }
        
        return $errors;
    }

    public function validateLogin($email, $password) {
        $errors = [];
        
        if (empty($email)) {
            $errors[] = "L'email est requis";
        }
        
        if (empty($password)) {
            $errors[] = "Le mot de passe est requis";
        }

        if (empty($errors) && !$this->login($email, $password)) {
            $errors[] = "Email ou mot de passe incorrect";
        }

        return $errors;
    }
}