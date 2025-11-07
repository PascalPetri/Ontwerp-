<?php

// gemaakt door Pascal Petri
// datum 7-11-2025

// classes/FilmActeur.php
class FilmActeur {
    private $conn;
    private $table_name = "film_acteurs";

    public $film_id;
    public $acteur_id;
    public $rol;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Check if link already exists
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE film_id = :film_id AND acteur_id = :acteur_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":film_id", $this->film_id);
        $stmt->bindParam(":acteur_id", $this->acteur_id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return false; // Link already exists
        }

        // Create new link
        $query = "INSERT INTO " . $this->table_name . " SET film_id=:film_id, acteur_id=:acteur_id, rol=:rol";
        $stmt = $this->conn->prepare($query);
        
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        
        $stmt->bindParam(":film_id", $this->film_id);
        $stmt->bindParam(":acteur_id", $this->acteur_id);
        $stmt->bindParam(":rol", $this->rol);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getActeursByFilm($film_id) {
        $query = "SELECT a.acteur_id, a.acteurnaam, fa.rol 
                  FROM acteurs a 
                  INNER JOIN film_acteurs fa ON a.acteur_id = fa.acteur_id 
                  WHERE fa.film_id = :film_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":film_id", $film_id);
        $stmt->execute();
        return $stmt;
    }
}
?>