<?php
// index.php
include_once 'includes/header.php';

// Read data for display
$films_with_actors = $film->getFilmsWithActors();
?>

<div class="page-links">
    <a href="index.php">Overzicht</a> | 
    <a href="film-toevoegen.php">Film Toevoegen</a> | 
    <a href="acteur-toevoegen.php">Acteur Toevoegen</a> | 
    <a href="acteur-koppelen.php">Acteur Koppelen</a>
</div>

<div class="overview-page">
    <h2>Volledig Overzicht</h2>
    <div class="overview-list">
        <?php if($films_with_actors->rowCount() > 0): ?>
            <?php while ($row = $films_with_actors->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="overview-item">
                    <div class="film-header">
                        <strong><?php echo $row['filmaam']; ?></strong>
                    </div>
                    <div class="film-details">
                        <em>Genre: <?php echo $row['genre_naam']; ?></em>
                    </div>
                    <div class="film-actors">
                        <strong>Acteurs:</strong> 
                        <?php echo $row['acteurs_met_rollen'] ? $row['acteurs_met_rollen'] : 'Nog geen acteurs gekoppeld'; ?>
                    </div>
                </div>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-data">
                <p>Nog geen films toegevoegd. <a href="film-toevoegen.php">Voeg de eerste film toe</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>