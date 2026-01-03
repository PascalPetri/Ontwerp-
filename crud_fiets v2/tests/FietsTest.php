<?php
namespace CrudFiets\tests;

use PHPUnit\Framework\TestCase;
use CrudFiets\classes\Fiets;
use CrudFiets\classes\Database;

require_once __DIR__ . '/../vendor/autoload.php';

// Define constants voor de tests
if (!defined('DATABASE')) define("DATABASE", "fietsenmaker");
if (!defined('SERVERNAME')) define("SERVERNAME", "localhost");
if (!defined('USERNAME')) define("USERNAME", "root");
if (!defined('PASSWORD')) define("PASSWORD", "");
if (!defined('CRUD_TABLE')) define("CRUD_TABLE", "fietsen");

class FietsTest extends TestCase
{
    private $testFietsId = null;
    private $db;
    private $fiets;
    
    protected function setUp(): void
    {
        $this->db = new Database();
        $this->fiets = new Fiets();
        
        // Insert een test fiets direct via PDO
        $conn = $this->db->connect();
        $sql = "INSERT INTO " . CRUD_TABLE . " (merk, type, prijs) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['TestMerk', 'TestType', 999.99]);
        
        $this->testFietsId = $conn->lastInsertId();
    }
    
    protected function tearDown(): void
    {
        // Verwijder test fiets als die bestaat
        if ($this->testFietsId) {
            try {
                $conn = $this->db->connect();
                $sql = "DELETE FROM " . CRUD_TABLE . " WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$this->testFietsId]);
            } catch (\Exception $e) {
                // Negeer errors tijdens cleanup
            }
        }
        
        // Cleanup andere test fietsen
        $conn = $this->db->connect();
        $sql = "DELETE FROM " . CRUD_TABLE . " WHERE merk IN ('Batavus', 'ToDelete')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    
    public function testFietsProperties()
    {
        $fiets = new Fiets();
        $fiets->setId(1);
        $fiets->setMerk('Gazelle');
        $fiets->setType('Chamonix');
        $fiets->setPrijs(799.99);
        
        $this->assertEquals(1, $fiets->getId());
        $this->assertEquals('Gazelle', $fiets->getMerk());
        $this->assertEquals('Chamonix', $fiets->getType());
        $this->assertEquals(799.99, $fiets->getPrijs());
    }
    
    public function testInsertFiets()
    {
        $fiets = new Fiets();
        $fiets->setMerk('Batavus');
        $fiets->setType('Blockbuster');
        $fiets->setPrijs(1000.00);
        
        $result = $fiets->insertFiets();
        $this->assertTrue($result);
    }
    
    public function testGetFiets()
    {
        // Gebruik de fixture die in setUp is aangemaakt
        $result = $this->fiets->getFiets($this->testFietsId);
        
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals('TestMerk', $result['merk']);
        $this->assertEquals('TestType', $result['type']);
        $this->assertEquals(999.99, $result['prijs']);
    }
    
    public function testUpdateFiets()
    {
        $fiets = new Fiets();
        $fiets->setId($this->testFietsId);
        $fiets->setMerk('UpdatedMerk');
        $fiets->setType('UpdatedType');
        $fiets->setPrijs(888.88);
        
        $result = $fiets->updateFiets();
        $this->assertTrue($result);
        
        // Verify update
        $updated = $fiets->getFiets($this->testFietsId);
        $this->assertEquals('UpdatedMerk', $updated['merk']);
        $this->assertEquals('UpdatedType', $updated['type']);
        $this->assertEquals(888.88, $updated['prijs']);
    }
    
    public function testDeleteFiets()
    {
        // Maak een nieuwe fiets speciaal voor deze test
        $conn = $this->db->connect();
        $sql = "INSERT INTO " . CRUD_TABLE . " (merk, type, prijs) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['ToDelete', 'DeleteMe', 500.00]);
        $deleteId = $conn->lastInsertId();
        
        $fiets = new Fiets();
        $result = $fiets->deleteFiets($deleteId);
        
        $this->assertTrue($result);
        
        // Verify deletion
        $deleted = $fiets->getFiets($deleteId);
        $this->assertEmpty($deleted);
    }
    
    public function testToonDetails()
    {
        $fiets = new Fiets();
        $fiets->setId(5);
        $fiets->setMerk('Test');
        $fiets->setType('Model');
        $fiets->setPrijs(500.00);
        
        // Capture output
        ob_start();
        $fiets->toonDetails();
        $output = ob_get_clean();
        
        // Controleer op de inhoud in plaats van exacte string
        $this->assertStringContainsString('Fiets Details', $output);
        $this->assertStringContainsString('Test', $output);
        $this->assertStringContainsString('Model', $output);
        $this->assertStringContainsString('500', $output);
    }
    
    public function testGetAllFietsen()
    {
        $fiets = new Fiets();
        $result = $fiets->getAllFietsen();
        
        $this->assertIsArray($result);
        // Er zou minstens onze testfiets moeten zijn
        $this->assertNotEmpty($result);
    }
}
?>