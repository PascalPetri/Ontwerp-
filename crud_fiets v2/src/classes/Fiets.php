<?php
namespace CrudFiets\classes;

use PDOException;

class Fiets
{
    private $id;
    private $merk;
    private $type;
    private $prijs;
    private $database;
    
    public function __construct()
    {
        $this->database = new Database();
    }
    
    // Setters
    public function setId($id): void { 
        $this->id = (int)$id; 
    }
    
    public function setMerk($merk): void { 
        $this->merk = htmlspecialchars($merk, ENT_QUOTES, 'UTF-8'); 
    }
    
    public function setType($type): void { 
        $this->type = htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); 
    }
    
    public function setPrijs($prijs): void { 
        $this->prijs = (float)$prijs; 
    }
    
    // Getters
    public function getId(): int { 
        return $this->id; 
    }
    
    public function getMerk(): string { 
        return $this->merk; 
    }
    
    public function getType(): string { 
        return $this->type; 
    }
    
    public function getPrijs(): float { 
        return $this->prijs; 
    }
    
    // Methodes volgens diagram
    public function insertFiets(): bool
    {
        try {
            $conn = $this->database->connect();
            $sql = "INSERT INTO " . CRUD_TABLE . " (merk, type, prijs) VALUES (:merk, :type, :prijs)";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([
                ':merk' => $this->merk,
                ':type' => $this->type,
                ':prijs' => $this->prijs
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Insert failed: " . $e->getMessage());
        }
    }
    
    public function getFiets($id): array
    {
        try {
            $conn = $this->database->connect();
            $sql = "SELECT * FROM " . CRUD_TABLE . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();
            
            if ($result) {
                $this->id = $result['id'];
                $this->merk = $result['merk'];
                $this->type = $result['type'];
                $this->prijs = $result['prijs'];
            }
            
            return $result ?: [];
        } catch (PDOException $e) {
            throw new \Exception("Get failed: " . $e->getMessage());
        }
    }
    
    public function updateFiets(): bool
    {
        try {
            $conn = $this->database->connect();
            $sql = "UPDATE " . CRUD_TABLE . " SET merk = :merk, type = :type, prijs = :prijs WHERE id = :id";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([
                ':merk' => $this->merk,
                ':type' => $this->type,
                ':prijs' => $this->prijs,
                ':id' => $this->id
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Update failed: " . $e->getMessage());
        }
    }
    
    public function deleteFiets($id): bool
    {
        try {
            $conn = $this->database->connect();
            $sql = "DELETE FROM " . CRUD_TABLE . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new \Exception("Delete failed: " . $e->getMessage());
        }
    }
    
    public function toonDetails(): void
    {
        echo "<div class='fiets-details'>";
        echo "<h3>Fiets Details</h3>";
        echo "<p><strong>ID:</strong> " . ($this->id ?? 'N/A') . "</p>";
        echo "<p><strong>Merk:</strong> " . ($this->merk ?? 'N/A') . "</p>";
        echo "<p><strong>Type:</strong> " . ($this->type ?? 'N/A') . "</p>";
        echo "<p><strong>Prijs:</strong> €" . number_format($this->prijs ?? 0, 2, ',', '.') . "</p>";
        echo "</div>";
    }
    
    public function getAllFietsen(): array
    {
        try {
            $conn = $this->database->connect();
            $sql = "SELECT * FROM " . CRUD_TABLE . " ORDER BY id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new \Exception("Get all failed: " . $e->getMessage());
        }
    }
}
?>