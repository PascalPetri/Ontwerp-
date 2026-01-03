<?php
// auteur: PascalPetri
// functie: verwijder een fiets op basis van de id

require_once('functions.php');

if(isset($_GET['id'])){
    try {
        $fiets = new CrudFiets\classes\Fiets();
        
        // Eerst fiets ophalen voor bevestiging
        $fietsData = $fiets->getFiets($_GET['id']);
        
        if (empty($fietsData)) {
            echo '<script>alert("Fiets niet gevonden")</script>';
            echo "<script> location.replace('read.php'); </script>";
            exit;
        }
        
        // Verwijder de fiets
        if($fiets->deleteFiets($_GET['id'])){
            echo '<script>alert("Fiets met ID: ' . $_GET['id'] . ' is verwijderd")</script>';
            echo "<script> location.replace('read.php'); </script>";
        } else {
            echo '<script>alert("Fiets is NIET verwijderd")</script>';
            echo "<script> location.replace('read.php'); </script>";
        }
    } catch (Exception $e) {
        echo '<script>alert("Error: ' . addslashes($e->getMessage()) . '")</script>';
        echo "<script> location.replace('read.php'); </script>";
    }
} else {
    // Geen ID gegeven, terug naar overzicht
    header('Location: read.php');
    exit;
}
?>