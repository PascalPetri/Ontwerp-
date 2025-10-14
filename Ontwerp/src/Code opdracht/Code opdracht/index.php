<?php
// Functie: programma login OOP 
// Auteur: Pascal Petri

session_start();
require_once 'classes/User.php';

$user = new User();

// Logout check
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    $user->logout();
    header("Location: login_form.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>

    <h3>PDO Login and Registration</h3>
    <hr/>

    <?php if (!$user->isLoggedin()): ?>
        <p>U bent niet ingelogd. Login om verder te gaan.</p>
        <a href="login_form.php">Login</a> | 
        <a href="register_form.php">Registreren</a>
    <?php else: ?>
        <?php
            $user->getUserFromSession();
            $userData = $user->getUser($user->username);
        ?>
        <h2>Welkom, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p>Je bent succesvol ingelogd.</p>
        <p><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>
        <a href="?logout=true">Uitloggen</a>
    <?php endif; ?>

</body>
</html>
