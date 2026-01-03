<?php
// Functie: programma login OOP 
// Auteur: Pascal Petri

session_start();
require_once('classes/User.php');

$user = new User();
$errors = [];

if (isset($_POST['register-btn'])) {
    // Gegevens uit formulier halen
    $user->username = trim($_POST['username']);
    $user->email = trim($_POST['email']);
    $user->setPassword($_POST['password']);

    // Validatie
    $errors = $user->validateUser();

    if (empty($user->email) || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ongeldig e-mailadres";
    }

    // Als er geen fouten zijn, registreer de gebruiker
    if (count($errors) === 0) {
        $errors = $user->registerUser();
    }

    // Resultaat
    if (count($errors) > 0) {
        echo '<div style="color:red;">';
        foreach ($errors as $error) {
            echo "<p>" . htmlspecialchars($error) . "</p>";
        }
        echo '</div>';
    } else {
        echo "
            <script>alert('User registered')</script>
            <script>window.location = 'login_form.php'</script>
        ";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registratie</title>
</head>
<body>
    <h3>PHP - PDO Login and Registration</h3>
    <hr/>

    <form method="POST">
        <h4>Register here...</h4>

        <label>Username</label>
        <input type="text" name="username" required>
        <br>

        <label>Email</label>
        <input type="email" name="email" required>
        <br>

        <label>Password</label>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit" name="register-btn">Register</button>
        <br>
        <a href="login_form.php">Login</a>
    </form>
</body>
</html>
