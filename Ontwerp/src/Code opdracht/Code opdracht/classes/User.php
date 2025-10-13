<?php
// Functie: User class
// Auteur: Pascal Petri

require_once "Database.php";

class User {

    public string $username = "";
    public string $email = "";
    private string $password = "";

    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        // session_start() verwijderd; wordt in index.php gestart
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function getPassword(){
        return $this->password;
    }

    public function validateUser(): array {
        $errors = [];

        if (empty($this->username)) {
            $errors[] = "Invalid username";
        } else if (strlen($this->username) < 3 || strlen($this->username) > 50) {
            $errors[] = "Username moet tussen 3 en 50 tekens lang zijn";
        }

        if (empty($this->password)) {
            $errors[] = "Invalid password";
        }

        return $errors;
    }

    public function registerUser(): array {
        $errors = $this->validateUser();
        if (!empty($errors)) return $errors;

        $stmt = $this->conn->prepare("SELECT * FROM User WHERE username = :username");
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $errors[] = "Username bestaat al";
        } else {
            $hashed = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("
                INSERT INTO User (username, password, email, role)
                VALUES (:username, :password, :email, 'user')
            ");
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":password", $hashed);
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();
        }

        return $errors;
    }

    public function loginUser(): bool {
        $stmt = $this->conn->prepare("SELECT * FROM User WHERE username = :username");
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($this->password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }

        return false;
    }

    // Check of de user ingelogd is
    public function isLoggedin(): bool {
        return isset($_SESSION['username']);
    }

    // Logout functie
    public function Logout() {
        session_unset();
        session_destroy();
    }

    // Toon ingelogde gebruiker
    public function showUser() {
        if ($this->isLoggedin()) {
            echo "Username: " . $_SESSION['username'] . "<br>";
            echo "Role: " . $_SESSION['role'] . "<br>";
        } else {
            echo "Geen gebruiker ingelogd";
        }
    }

    // Haal userdata uit database (optioneel)
    public function getUser(string $username): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM User WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    // Vul username uit session (optioneel)
    public function getUserFromSession() {
        if ($this->isLoggedin()) {
            $this->username = $_SESSION['username'];
        }
    }
}
?>
