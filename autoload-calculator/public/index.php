<?php
require '../vendor/autoload.php';
use Autoload\Calculator\Calculator;

$cijfer3 = '';
if ($_POST && !empty($_POST['cijfer1']) && !empty($_POST['cijfer2'])) {
    $calc = new Calculator();
    $cijfer3 = $calc->maakInput3($_POST['cijfer1'], $_POST['cijfer2']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Autoloader Calculator</title>
</head>
<body>
    <h1>Autoloader Calculator</h1>
    
    <form method="post">
        Cijfer 1: <input type="number" name="cijfer1" required><br><br>
        Cijfer 2: <input type="number" name="cijfer2" required><br><br>
        <button type="submit">Bereken Cijfer 3</button>
    </form>

    <?php if ($cijfer3 !== ''): ?>
        <h2>Resultaat</h2>
        <p><strong>Cijfer 3 = <?php echo $cijfer3; ?></strong></p>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p style="color:red">Vul beide cijfers in!</p>
    <?php endif; ?>
</body>
</html>