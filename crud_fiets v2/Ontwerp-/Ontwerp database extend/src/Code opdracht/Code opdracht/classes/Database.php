<?php
// Functie: Database class
// Auteur: Pascal Petri

class Database {
    protected ?PDO $_conn = null;

    private string $host = "localhost";
    private string $dbname = "Login";
    private string $user = "root";
    private string $password = "";

    public function __construct() {
        try {
            $this->_conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->user,
                $this->password
            );
            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connectie mislukt: " . $e->getMessage());
        }
    }

    public function connectDb(): PDO {
        if ($this->_conn === null) {
            // Herstel verbinding als deze weg is
            $this->__construct();
        }
        return $this->_conn;
    }
}
?>
