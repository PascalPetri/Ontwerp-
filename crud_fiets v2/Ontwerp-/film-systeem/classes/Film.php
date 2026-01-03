<?php

// gemaakt door Pascal Petri
// datum 7-11-2025

// classes/Film.php
class Film {
    private $conn;
    private $table_name = "films";

    public $film_id;
    public $filmaam;
    public $genre_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET filmaam=:filmaam, genre_id=:genre_id";
        $stmt = $this->conn->prepare($query);

        $this->filmaam = htmlspecialchars(strip_tags($this->filmaam));
        $this->genre_id = htmlspecialchars(strip_tags($this->genre_id));

        $stmt->bindParam(":filmaam", $this->filmaam);
        $stmt->bindParam(":genre_id", $this->genre_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT f.*, g.genre_naam 
                  FROM " . $this->table_name . " f 
                  LEFT JOIN genres g ON f.genre_id = g.genre_id 
                  ORDER BY f.filmaam";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getFilmsWithActors() {
        $query = "SELECT f.film_id, f.filmaam, g.genre_naam,
                         GROUP_CONCAT(CONCAT(a.acteurnaam, ' als ', fa.rol) SEPARATOR ', ') as acteurs_met_rollen
                  FROM films f 
                  LEFT JOIN genres g ON f.genre_id = g.genre_id 
                  LEFT JOIN film_acteurs fa ON f.film_id = fa.film_id 
                  LEFT JOIN acteurs a ON fa.acteur_id = a.acteur_id 
                  GROUP BY f.film_id 
                  ORDER BY f.filmaam";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>