<?php
// functions.php - algemene functies
// auteur: PascalPetri

// Include configuratie
require_once __DIR__ . '/../src/config.php';

// Autoloader via Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Helper functies
function printCrudTabel($result){
    if (empty($result)) {
        echo "<div class='alert alert-info'>Geen fietsen gevonden.</div>";
        return;
    }
    
    $table = "<table class='crud-table'>";
    $headers = array_keys($result[0]);
    $table .= "<thead><tr>";
    foreach($headers as $header){
        $table .= "<th>" . htmlspecialchars($header) . "</th>";   
    }
    $table .= "<th colspan='2'>Actie</th>";
    $table .= "</tr></thead>";
    
    $table .= "<tbody>";
    foreach ($result as $row) {
        $table .= "<tr>";
        foreach ($row as $cell) {
            $table .= "<td>" . htmlspecialchars($cell) . "</td>";  
        }
        
        $table .= "<td>
            <form method='get' action='update.php' style='display:inline;'>
                <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                <button type='submit' class='btn-wijzig'>Wijzig</button>
            </form>
            </td>";

        $table .= "<td>
            <form method='get' action='delete.php' style='display:inline;' onsubmit='return confirm(\"Weet je zeker dat je deze fiets wilt verwijderen?\")'>
                <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                <button type='submit' class='btn-verwijder'>Verwijder</button>
            </form>
            </td>";

        $table .= "</tr>";
    }
    $table.= "</tbody></table>";
    echo $table;
}

function getRecord($id){
    try {
        $fiets = new CrudFiets\classes\Fiets();
        $data = $fiets->getFiets($id);
        
        if (!empty($data)) {
            $fiets->setId($data['id']);
            $fiets->setMerk($data['merk']);
            $fiets->setType($data['type']);
            $fiets->setPrijs($data['prijs']);
            return $fiets;
        }
        return null;
    } catch (Exception $e) {
        error_log("Error in getRecord: " . $e->getMessage());
        return null;
    }
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>