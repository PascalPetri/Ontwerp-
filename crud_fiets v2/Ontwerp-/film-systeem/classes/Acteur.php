<?php

// gemaakt door Pascal Petri
// datum 7-11-2025

// classes/Acteur.php
class Acteur {
    private $conn;
    private $table_name = "acteurs";

    public $acteur_id;
    public $acteurnaam;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY acteurnaam";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET acteurnaam=:acteurnaam";
        $stmt = $this->conn->prepare($query);

        $this->acteurnaam = htmlspecialchars(strip_tags($this->acteurnaam));
        $stmt->bindParam(":acteurnaam", $this->acteurnaam);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>