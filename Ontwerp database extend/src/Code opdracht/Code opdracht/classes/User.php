<?php
// Functie: User class (gebaseerd op Database inheritance)
// Auteur: Pascal Petri

require_once "Database.php";

class User extends Database {

    public string $username = "";
    public string $email = "";
    private string $password = "";
    public string $role = "";

    private PDO $conn;

    public function __construct() {
        parent::__construct(); // Roept Database constructor aan
        $this->conn = $this->connectDb(); // Haal PDO object op
        // session_start() blijft buiten deze class
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function validateUser(): array {
        $errors = [];

        if (empty($this->username)) {
            $errors[] = "Invalid username";
        } elseif (strlen($this->username) < 3 || strlen($this->username) > 50) {
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

    public function loginUser(): array {
        $result = [
            'success' => false,
            'message' => ''
        ];

        if (empty($this->username) || empty($this->password)) {
            $result['message'] = 'Vul zowel username als wachtwoord in';
            return $result;
        }

        $stmt = $this->conn->prepare("SELECT * FROM User WHERE username = :username LIMIT 1");
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $result['message'] = 'Gebruiker bestaat niet';
            return $result;
        }

        if (!password_verify($this->password, $user['password'])) {
            $result['message'] = 'Wachtwoord is onjuist';
            return $result;
        }

        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];

        $result['success'] = true;
        $result['message'] = 'Login succesvol';
        return $result;
    }

    public function isLoggedin(): bool {
        return isset($_SESSION['username']);
    }

    public function logout(): void {
        session_unset();
        session_destroy();
    }

    public function showUser(): void {
        if ($this->isLoggedin()) {
            echo "Username: " . $_SESSION['username'] . "<br>";
            echo "Role: " . $_SESSION['role'] . "<br>";
        } else {
            echo "Geen gebruiker ingelogd";
        }
    }

    public function getUser(string $username): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM User WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getUserFromSession(): void {
        if ($this->isLoggedin()) {
            $this->username = $_SESSION['username'];
        }
    }
}
?>
