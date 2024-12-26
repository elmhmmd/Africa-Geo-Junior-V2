<?php
//I don't know if hashing logic has something to do with this class but if it does remember to implement it
//remember to import needed classes
//remember to add methods as needed
class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            return true;
        }
        return false;
    }

    public function register($username, $password) {
    // Check if username already exists
    $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetch()) {
        return false; // Username already taken
    }
    
    // Hash password and create user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $this->db->prepare("INSERT INTO users (username, password, is_admin) VALUES (:username, :password, 0)");
    return $stmt->execute([
        ':username' => $username,
        ':password' => $hashedPassword
    ]);
}
}