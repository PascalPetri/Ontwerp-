<?php

// gemaakt door Pascal Petri
// datum 7-11-2025

// acteur-toevoegen.php
include_once 'includes/header.php';

// Process form submission
if($_POST && isset($_POST['add_actor'])){
    $acteur->acteurnaam = $_POST['acteurnaam'];
    
    if($acteur->create()){
        $_SESSION['message'] = "Acteur succesvol toegevoegd!";
        header("Location: acteur-toevoegen.php");
        exit();
    } else{
        $_SESSION['message'] = "Kon acteur niet toevoegen.";
    }
}
?>

<div class="page-links">
    <a href="index.php">Overzicht</a> | 
    <a href="film-toevoegen.php">Film Toevoegen</a> | 
    <a href="acteur-toevoegen.php">Acteur Toevoegen</a> | 
    <a href="acteur-koppelen.php">Acteur Koppelen</a>
</div>

<div class="form-page">
    <div class="form-container">
        <h2>Acteur Toevoegen</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="acteurnaam">Acteurnaam:</label>
                <input type="text" id="acteurnaam" name="acteurnaam" required>
            </div>
            <button type="submit" class="submit-btn" name="add_actor">Acteur Toevoegen</button>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>