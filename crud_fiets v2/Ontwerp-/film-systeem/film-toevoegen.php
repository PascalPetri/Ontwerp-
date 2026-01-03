<?php

// gemaakt door Pascal Petri
// datum 7-11-2025

// film-toevoegen.php
include_once 'includes/header.php';

// Process form submission
if($_POST && isset($_POST['add_film'])){
    $film->filmaam = $_POST['filmaam'];
    $film->genre_id = $_POST['genre_id'];
    
    if($film->create()){
        $_SESSION['message'] = "Film succesvol toegevoegd!";
        header("Location: film-toevoegen.php");
        exit();
    } else{
        $_SESSION['message'] = "Kon film niet toevoegen.";
    }
}

// Read genres for dropdown
$genres_stmt = $genre->read();
?>

<div class="page-links">
    <a href="index.php">Overzicht</a> | 
    <a href="film-toevoegen.php">Film Toevoegen</a> | 
    <a href="acteur-toevoegen.php">Acteur Toevoegen</a> | 
    <a href="acteur-koppelen.php">Acteur Koppelen</a>
</div>

<div class="form-page">
    <div class="form-container">
        <h2>Film Toevoegen</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="filmaam">Filmaam:</label>
                <input type="text" id="filmaam" name="filmaam" required>
            </div>
            <div class="form-group">
                <label for="genre_id">Genre:</label>
                <select id="genre_id" name="genre_id" required>
                    <option value="">Selecteer een genre</option>
                    <?php while ($genre_row = $genres_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $genre_row['genre_id']; ?>"><?php echo $genre_row['genre_naam']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="submit-btn" name="add_film">Film Toevoegen</button>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>