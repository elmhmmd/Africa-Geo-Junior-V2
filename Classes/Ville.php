<?php

class Ville {
    private $id;
    private $nom;
    private $description;
    private $type;
    private $id_pays;

    public function __construct($id = null, $nom = '', $description = '', $type = '', $id_pays = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->type = $type;
        $this->id_pays = $id_pays;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getType() { return $this->type; }
    public function getPaysId() { return $this->id_pays; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setDescription($description) { $this->description = $description; }
    public function setType($type) { $this->type = $type; }
    public function setPaysId($id_pays) { $this->id_pays = $id_pays; }

    // CRUD Operations
    public static function create($nom, $description, $type, $id_pays) {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO villes (nom, description, type, id_pays) VALUES (:nom, :description, :type, :id_pays)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':type' => $type,
            ':id_pays' => $id_pays
        ]);
        return new self($db->lastInsertId(), $nom, $description, $type, $id_pays);
    }

    public static function getById($id) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM villes WHERE id_ville = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new self($data['id_ville'], $data['nom'], $data['description'], $data['type'], $data['id_pays']) : null;
    }

    public static function getAll() {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM villes";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $villes = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $villes[] = new self($data['id_ville'], $data['nom'], $data['description'], $data['type'], $data['id_pays']);
        }
        return $villes;
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $sql = "UPDATE villes SET nom = :nom, description = :description, type = :type, id_pays = :id_pays WHERE id_ville = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id' => $this->id,
            ':nom' => $this->nom,
            ':description' => $this->description,
            ':type' => $this->type,
            ':id_pays' => $this->id_pays
        ]);
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $sql = "DELETE FROM villes WHERE id_ville = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    public static function getByPaysId($id_pays) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM villes WHERE id_pays = :id_pays";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id_pays' => $id_pays]);
        $villes = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $villes[] = new self($data['id_ville'], $data['nom'], $data['description'], $data['type'], $data['id_pays']);
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
            $villes[] = new self($data['id_ville'], $data['nom'], $data['description'], $data['type'], $data['id_pays']);
        }
        return $villes;
    }
}