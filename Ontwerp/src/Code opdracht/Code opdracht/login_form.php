<?php
// Functie: programma login OOP 
// Auteur: Pascal Petri

session_start();
require_once('classes/User.php');

$user = new User();
$errors = [];

// Is de login button aangeklikt?
if (isset($_POST['login-btn'])) {
    $user->username = trim($_POST['username']);
    $user->setPassword($_POST['password']);

    $result = $user->loginUser();

    if ($result['success']) {
        header("Location: index.php");
        exit;
    } else {
        $errors[] = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h3>PHP - PDO Login and Registration</h3>
    <hr/>

    <form method="POST">
        <h4>Login here...</h4>

        <label>Username</label>
        <input type="text" name="username" required>
        <br>

        <label>Password</label>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit" name="login-btn">Login</button>
        <br>
        <a href="register_form.php">Nog geen account? Registreer hier</a>
    </form>

    <?php if (!empty($errors)): ?>
        <div style="color:red; margin-top:10px;">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
