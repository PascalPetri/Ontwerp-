<?php
namespace CrudFiets\tests;

use PHPUnit\Framework\TestCase;
use CrudFiets\classes\Database;

require_once __DIR__ . '/../vendor/autoload.php';

// Define constants voor de tests
if (!defined('DATABASE')) define("DATABASE", "fietsenmaker");
if (!defined('SERVERNAME')) define("SERVERNAME", "localhost");
if (!defined('USERNAME')) define("USERNAME", "root");
if (!defined('PASSWORD')) define("PASSWORD", "");
if (!defined('CRUD_TABLE')) define("CRUD_TABLE", "fietsen");

class DatabaseTest extends TestCase
{
    public function testDatabaseConnection()
    {
        $db = new Database();
        $conn = $db->connect();
        
        $this->assertInstanceOf(\PDO::class, $conn);
    }
    
    public function testExecuteQuery()
    {
        $db = new Database();
        $result = $db->executeQuery("SELECT 1 as test");
        
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals(1, $result[0]['test']);
    }
    
    public function testCloseConnection()
    {
        $db = new Database();
        $conn1 = $db->connect();
        
        $db->closeConnection();
        
        // Nieuwe connectie
        $conn2 = $db->connect();
        
        $this->assertInstanceOf(\PDO::class, $conn1);
        $this->assertInstanceOf(\PDO::class, $conn2);
    }
}
?>