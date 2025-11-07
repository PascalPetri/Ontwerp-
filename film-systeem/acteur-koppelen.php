<?php

// Gemaakt door Pascal Petri
// datum 7-11-2025

// acteur-koppelen.php
include_once 'includes/header.php';

// Process form submission
if($_POST && isset($_POST['link_actor'])){
    $filmActeur->film_id = $_POST['film_id'];
    $filmActeur->acteur_id = $_POST['acteur_id'];
    $filmActeur->rol = $_POST['rol'];
    
    if($filmActeur->create()){
        $_SESSION['message'] = "Acteur succesvol gekoppeld aan film!";
        header("Location: acteur-koppelen.php");
        exit();
    } else{
        $_SESSION['message'] = "Kon acteur niet koppelen (mogelijk al gekoppeld).";
    }
}

// Read data for dropdowns
$films_stmt = $film->read();
$actors_stmt = $acteur->read();
?>

<div class="page-links">
    <a href="index.php">Overzicht</a> | 
    <a href="film-toevoegen.php">Film Toevoegen</a> | 
    <a href="acteur-toevoegen.php">Acteur Toevoegen</a> |  
    <a href="acteur-koppelen.php">Acteur Koppelen</a>
</div>

<div class="form-page">
    <div class="form-container">
        <h2>Acteur Koppelen aan Film</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="film_id">Film:</label>
                <select id="film_id" name="film_id" required>
                    <option value="">Selecteer een film</option>
                    <?php while ($film_row = $films_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $film_row['film_id']; ?>">
                            <?php echo $film_row['filmaam']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="acteur_id">Acteur:</label>
                <select id="acteur_id" name="acteur_id" required>
                    <option value="">Selecteer een acteur</option>
                    <?php while ($actor_row = $actors_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $actor_row['acteur_id']; ?>"><?php echo $actor_row['acteurnaam']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rol">Rol:</label>
                <input type="text" id="rol" name="rol" required placeholder="Bijv: Hoofdrol, Bijrol, etc.">
            </div>
            <button type="submit" class="submit-btn" name="link_actor">Acteur Koppelen</button>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>