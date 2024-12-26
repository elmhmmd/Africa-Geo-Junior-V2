<?php

class Continent {
    private $id;
    private $nom;
    private $nombrePays;

    public function __construct($id = null, $nom = '', $nombrePays = 0) {
        $this->id = $id;
        $this->nom = $nom;
        $this->nombrePays = $nombrePays;
    }

    // Getters and setters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getNombrePays() { return $this->nombrePays; }

    public function setNom($nom) { $this->nom = $nom; }
    public function setNombrePays($nombrePays) { $this->nombrePays = $nombrePays; }


}