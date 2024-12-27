<?php

class Ville {
    private $id;
    private $nom;
    private $description;
    private $type;
    private $pays_id;

    public function __construct($id = null, $nom = '', $description = '', $type = '', $pays_id = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->type = $type;
        $this->pays_id = $pays_id;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getType() { return $this->type; }
    public function getPaysId() { return $this->pays_id; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setDescription($description) { $this->description = $description; }
    public function setType($type) { $this->type = $type; }
    public function setPaysId($pays_id) { $this->pays_id = $pays_id; }

    // CRUD Operations
    public static function create($nom, $description, $type, $pays_id) {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO villes (nom, description, type, pays_id) VALUES (:nom, :description, :type, :pays_id)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':type' => $type,
            ':pays_id' => $pays_id
        ]);
        return new self($db->lastInsertId(), $nom, $description, $type, $pays_id);
    }

    public static function getById($id) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM villes WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new self($data['id'], $data['nom'], $data['description'], $data['type'], $data['pays_id']) : null;
    }

    public static function getAll() {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM villes";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $villes = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $villes[] = new self($data['id'], $data['nom'], $data['description'], $data['type'], $data['pays_id']);
        }
        return $villes;
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $sql = "UPDATE villes SET nom = :nom, description = :description, type = :type, pays_id = :pays_id WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id' => $this->id,
            ':nom' => $this->nom,
            ':description' => $this->description,
            ':type' => $this->type,
            ':pays_id' => $this->pays_id
        ]);
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $sql = "DELETE FROM villes WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    public static function getByPaysId($pays_id) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM villes WHERE pays_id = :pays_id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':pays_id' => $pays_id]);
        $villes = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $villes[] = new self($data['id'], $data['nom'], $data['description'], $data['type'], $data['pays_id']);
        }
        return $villes;
    }

    public static function getByType($type) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM villes WHERE type = :type";
        $stmt = $db->prepare($sql);
        $stmt->execute([':type' => $type]);
        $villes = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $villes[] = new self($data['id'], $data['nom'], $data['description'], $data['type'], $data['pays_id']);
        }
        return $villes;
    }
}