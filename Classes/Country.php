<?php


class Country {
    private $id;
    private $nom;
    private $population;
    private $langue;
    private $continent_id;

    public function __construct($id = null, $nom = '', $population = 0, $langue = null, $continent_id = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->population = $population;
        $this->langue = $langue;
        $this->continent_id = $continent_id;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPopulation() { return $this->population; }
    public function getLangue() { return $this->langue; }
    public function getContinentId() { return $this->continent_id; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setPopulation($population) { $this->population = $population; }
    public function setLangue($langue) { $this->langue = $langue; }
    public function setContinentId($continent_id) { $this->continent_id = $continent_id; }

    // CRUD Operations
    public static function create($nom, $population, $langue, $continent_id) {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO pays (nom, population, langue, id_continent) VALUES (:nom, :population, :langue, :id_continent)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':population' => $population,
            ':langue' => $langue,
            ':id_continent' => $continent_id
        ]);
        return new self($db->lastInsertId(), $nom, $population, $langue, $continent_id);
    }

    public static function getById($id) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM pays WHERE id_pays = :id"; 
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new self(
            isset($data['id_pays']) ? $data['id_pays'] : null, 
            isset($data['nom']) ? $data['nom'] : '',
            isset($data['population']) ? $data['population'] : 0,
            isset($data['langue']) ? $data['langue'] : null,
            isset($data['continent_id']) ? $data['continent_id'] : null
        ) : null;
    }

    public static function getAll() {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM pays";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $pays = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pays[] = new self(
                isset($data['id_pays']) ? $data['id_pays'] : null,  
                isset($data['nom']) ? $data['nom'] : '',
                isset($data['population']) ? $data['population'] : 0,
                isset($data['langue']) ? $data['langue'] : null,
                isset($data['continent_id']) ? $data['continent_id'] : null
            );
        }
        return $pays;
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $sql = "UPDATE pays SET nom = :nom, population = :population, langue = :langue, id_continent = :id_continent WHERE id_pays = :id";  
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id' => $this->id,
            ':nom' => $this->nom,
            ':population' => $this->population,
            ':langue' => $this->langue,
            ':id_continent' => $this->continent_id
        ]);
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $sql = "DELETE FROM pays WHERE id_pays = :id";  
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    // Related data methods
    public function getVilles() {
        return Ville::getByPaysId($this->id);
    }

    public static function getByContinent($continent_id) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM pays WHERE id_continent = :continent_id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':continent_id' => $continent_id]);
        $pays = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pays[] = new self(
                isset($data['id_pays']) ? $data['id_pays'] : null,  
                isset($data['nom']) ? $data['nom'] : '',
                isset($data['population']) ? $data['population'] : 0,
                isset($data['langue']) ? $data['langue'] : null,
                isset($data['continent_id']) ? $data['continent_id'] : null
            );
        }
        return $pays;
    }

    public static function getByName($nom) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM pays WHERE nom = :nom";
        $stmt = $db->prepare($sql);
        $stmt->execute([':nom' => $nom]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new self(
            isset($data['id_pays']) ? $data['id_pays'] : null,  
            isset($data['nom']) ? $data['nom'] : '',
            isset($data['population']) ? $data['population'] : 0,
            isset($data['langue']) ? $data['langue'] : null,
            isset($data['continent_id']) ? $data['continent_id'] : null
        ) : null;
    }
}