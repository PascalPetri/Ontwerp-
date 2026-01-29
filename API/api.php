<?php
//gemaakt door Pascal Petri
//Datum 28-1-2026

//CRUD API voor Producten
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Error reporting aan voor debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Database 
$host = 'localhost';
$dbname = 'st1738846998';
$username = 'st1738846998';
$password = 'ivPlJaMNme7GUnz';


try {
    // Maak database verbinding
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection failed',
        'message' => $e->getMessage(),
        'config' => [
            'host' => $host,
            'database' => $dbname,
            'username' => $username,
            'password_provided' => !empty($password)
        ]
    ]);
    exit();
}


// Helper functie voor validatie
function validateProductData($data, $checkUniqueName = true, $pdo = null, $excludeId = null) {
    $errors = [];
    
    // Naam validatie
    if (empty($data['naam'])) {
        $errors[] = 'Productnaam is verplicht';
    } elseif (strlen($data['naam']) > 50) {
        $errors[] = 'Productnaam mag maximaal 50 tekens bevatten';
    }
    
    // Prijs validatie
    if (!isset($data['prijs']) || $data['prijs'] === '') {
        $errors[] = 'Prijs is verplicht';
    } elseif (!is_numeric($data['prijs'])) {
        $errors[] = 'Prijs moet een numerieke waarde zijn';
    } elseif ($data['prijs'] < 0) {
        $errors[] = 'Prijs mag niet negatief zijn';
    }
    
    // Check of naam uniek is
    if ($checkUniqueName && $pdo && isset($data['naam']) && !empty($data['naam'])) {
        if ($excludeId) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE naam = ? AND id != ?");
            $stmt->execute([$data['naam'], $excludeId]);
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE naam = ?");
            $stmt->execute([$data['naam']]);
        }
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Productnaam moet uniek zijn';
        }
    }
    
    return $errors;
}

// Request Handeling

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$input = json_decode(file_get_contents('php://input'), true);

// Haal ID uit query parameters of uit de input
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

switch ($method) {
    case 'GET':
        // GET: Opvragen van data
        if ($id > 0) {
            // Haal specifiek product op
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            
            if ($product) {
                http_response_code(200);
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product niet gevonden']);
            }
        } else {
            // Haal alle producten op
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id");
            $products = $stmt->fetchAll();
            
            http_response_code(200);
            echo json_encode($products);
        }
        break;
        
    case 'POST':
        // POST: Opslaan van nieuw product
        $errors = validateProductData($input, true, $pdo);
        
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO products (naam, prijs) VALUES (?, ?)");
            $stmt->execute([
                trim($input['naam']),
                number_format(floatval($input['prijs']), 2, '.', '')
            ]);
            
            $newId = $pdo->lastInsertId();
            
            // Haal het nieuwe product op
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$newId]);
            $newProduct = $stmt->fetch();
            
            http_response_code(201);
            echo json_encode([
                'message' => 'Product succesvol aangemaakt',
                'product' => $newProduct
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    case 'PUT':
        // PUT: Wijzigingen aanbrengen in bestaand product
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Ongeldig product ID']);
            break;
        }
        
        // Controleer of het product bestaat
        $checkStmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $checkStmt->execute([$id]);
        if (!$checkStmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Product niet gevonden']);
            break;
        }
        
        $errors = validateProductData($input, true, $pdo, $id);
        
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE products SET naam = ?, prijs = ? WHERE id = ?");
            $stmt->execute([
                trim($input['naam']),
                number_format(floatval($input['prijs']), 2, '.', ''),
                $id
            ]);
            
            if ($stmt->rowCount() > 0) {
                // Haal het bijgewerkte product op
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $updatedProduct = $stmt->fetch();
                
                http_response_code(200);
                echo json_encode([
                    'message' => 'Product succesvol bijgewerkt',
                    'product' => $updatedProduct
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product niet gevonden of geen wijzigingen']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    case 'DELETE':
        // DELETE: Verwijderen van een product
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Ongeldig product ID']);
            break;
        }
        
        // Controleer of het product bestaat
        $checkStmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $checkStmt->execute([$id]);
        if (!$checkStmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Product niet gevonden']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                http_response_code(204); // No Content
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product niet gevonden']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>