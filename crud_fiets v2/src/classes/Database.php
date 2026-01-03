<?php
namespace CrudFiets\classes;

use PDO;
use PDOException;

class Database
{
    private $servername;
    private $username;
    private $password;
    private $database;
    private $connection = null;
    
    public function __construct()
    {
        $this->servername = SERVERNAME;
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->database = DATABASE;
    }
    
    public function connect(): PDO
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->servername};dbname={$this->database};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->connection;
    }
    
    public function executeQuery($sql, $params = []): array
    {
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function closeConnection(): void
    {
        $this->connection = null;
    }
}
?>