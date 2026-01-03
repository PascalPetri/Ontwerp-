<?php
// functie: update fiets
// auteur: PascalPetri

require_once('functions.php');

// Test of er op de wijzig-knop is gedrukt 
if(isset($_POST['btn_wzg'])){
    try {
        $fiets = new CrudFiets\classes\Fiets();
        $fiets->setId($_POST['id'] ?? 0);
        $fiets->setMerk($_POST['merk'] ?? '');
        $fiets->setType($_POST['type'] ?? '');
        $fiets->setPrijs($_POST['prijs'] ?? 0);

        if($fiets->updateFiets()){
            echo "<script>alert('Fiets is gewijzigd')</script>";
            echo "<script> location.replace('read.php'); </script>";
        } else {
            echo '<script>alert("Fiets is NIET gewijzigd")</script>';
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Test of id is meegegeven in de URL
$fiets = null;
if(isset($_GET['id'])){  
    $id = $_GET['id'];
    try {
        $fiets = getRecord($id);
    } catch (Exception $e) {
        echo '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    
    if (!$fiets) {
        echo "<div class='alert alert-error'>Geen fiets gevonden met dit id</div>";
        echo "<a href='read.php' class='btn'>Terug naar overzicht</a>";
        exit;
    }
} else {
    echo "<div class='alert alert-error'>Geen id opgegeven</div>";
    echo "<a href='read.php' class='btn'>Terug naar overzicht</a>";
    exit;
}
?> 

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Wijzig Fiets</title>
</head>
<body>
  <div class="container">
    <h1>Wijzig Fiets</h1>
    <nav>
        <a href='read.php'>Terug naar overzicht</a>
    </nav>
    
    <?php if ($fiets): ?>
        <div class="fiets-details">
            <h3>Huidige gegevens:</h3>
            <?php $fiets->toonDetails(); ?>
        </div>
        
        <h2>Nieuwe gegevens:</h2>
        <form method="post">
            <input type="hidden" id="id" name="id" required value="<?php echo htmlspecialchars($fiets->getId()); ?>">
            
            <label for="merk">Merk:</label>
            <input type="text" id="merk" name="merk" required value="<?php echo htmlspecialchars($fiets->getMerk()); ?>">
            
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" required value="<?php echo htmlspecialchars($fiets->getType()); ?>">
            
            <label for="prijs">Prijs (€):</label>
            <input type="number" step="0.01" min="0" id="prijs" name="prijs" required value="<?php echo htmlspecialchars($fiets->getPrijs()); ?>">
            
            <button type="submit" name="btn_wzg" class="btn">Wijzigingen opslaan</button>
            <a href="read.php" class="btn" style="background-color: #95a5a6;">Annuleren</a>
        </form>
    <?php endif; ?>
  </div>
</body>
</html>