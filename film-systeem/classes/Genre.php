<?php

// gemaakt door Pascal Petri
// datum 7-11-2025


// classes/Genre.php
class Genre {
    private $conn;
    private $table_name = "genres";

    public $genre_id;
    public $genre_naam;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY genre_naam";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET genre_naam=:genre_naam";
        $stmt = $this->conn->prepare($query);

        $this->genre_naam = htmlspecialchars(strip_tags($this->genre_naam));
        $stmt->bindParam(":genre_naam", $this->genre_naam);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>