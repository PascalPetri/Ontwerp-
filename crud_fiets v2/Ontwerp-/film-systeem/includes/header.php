<?php

// gemaakt door Pascal Petri
// datum 7-11-2025

// includes/header.php
session_start();

// Include configuration and classes
include_once 'config/database.php';
include_once 'classes/Database.php';
include_once 'classes/Film.php';
include_once 'classes/Genre.php';
include_once 'classes/Acteur.php';
include_once 'classes/FilmActeur.php';

// database connection
$database = new Database();
$db = $database->getConnection();

// kijkt naar de objects
$film = new Film($db);
$genre = new Genre($db);
$acteur = new Acteur($db);
$filmActeur = new FilmActeur($db);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Systeem</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Film systeem</h1>
            <p class="subtitle">Beheer films, genres en acteurs</p>
        </header>
        
        <?php if(isset($_SESSION['message'])): ?>
            <div class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>