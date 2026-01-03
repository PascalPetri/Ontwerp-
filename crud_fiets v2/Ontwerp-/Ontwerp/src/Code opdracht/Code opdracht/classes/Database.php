<?php
// Functie: Database connectie
// Auteur: Pascal Petri

class Database {
    private string $host = "localhost";
    private string $dbname = "Login";
    private string $user = "root";
    private string $password = "";

    private ?PDO $conn = null;

    public function connect(): PDO {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
                    $this->user,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die(" Database connectie mislukt: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
?>
