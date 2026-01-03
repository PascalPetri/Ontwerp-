<?php
// functie: formulier en database insert fiets
// auteur: PascalPetri

require_once('functions.php');
     
// Test of er op de insert-knop is gedrukt 
if(isset($_POST['btn_ins'])){
    try {
        $fiets = new CrudFiets\classes\Fiets();
        $fiets->setMerk($_POST['merk'] ?? '');
        $fiets->setType($_POST['type'] ?? '');
        $fiets->setPrijs($_POST['prijs'] ?? 0);

        if($fiets->insertFiets()){
            echo "<script>alert('Fiets is toegevoegd')</script>";
            echo "<script> location.replace('read.php'); </script>";
        } else {
            echo '<script>alert("Fiets is NIET toegevoegd")</script>';
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Voeg Fiets Toe</title>
</head>
<body>
    <div class="container">
        <h1>Voeg Fiets Toe</h1>
        <nav>
            <a href='read.php'>Terug naar overzicht</a>
        </nav>
        
        <form method="post">
            <label for="merk">Merk:</label>
            <input type="text" id="merk" name="merk" required placeholder="Bijv. Gazelle, Batavus...">
            
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" required placeholder="Bijv. Chamonix, Blockbuster...">
            
            <label for="prijs">Prijs (€):</label>
            <input type="number" step="0.01" min="0" id="prijs" name="prijs" required placeholder="Bijv. 799.99">
            
            <button type="submit" name="btn_ins" class="btn">Toevoegen</button>
            <a href="read.php" class="btn" style="background-color: #95a5a6;">Annuleren</a>
        </form>
    </div>
</body>
</html>