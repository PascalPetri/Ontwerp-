<?php
// functie: toon alle fietsen in een tabel
// auteur: PascalPetri

require_once('functions.php');

// Maak Fiets object
$fiets = new CrudFiets\classes\Fiets();

// Haal alle fietsen op
try {
    $result = $fiets->getAllFietsen();
} catch (Exception $e) {
    echo '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $result = [];
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fietsen Overzicht</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Fietsen Overzicht</h1>
        <nav>
            <a href='insert.php'>Toevoegen nieuwe fiets</a>
            <a href='#' onclick="window.location.reload()">Verversen</a>
        </nav>
        
        <?php
        if (empty($result)) {
            echo "<div class='alert alert-info'>Geen fietsen gevonden in de database.</div>";
            echo "<p><a href='insert.php' class='btn'>Voeg je eerste fiets toe</a></p>";
        } else {
            printCrudTabel($result);
        }
        ?>
        
        <?php
        // Toon aantal fietsen
        if (!empty($result)) {
            echo "<p class='count'>Totaal: " . count($result) . " fiets" . (count($result) !== 1 ? 'en' : '') . "</p>";
        }
        ?>
    </div>
</body>
</html>