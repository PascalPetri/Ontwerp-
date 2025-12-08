<?php
// AUTOLOADER INCLUDEN
require __DIR__ . '/../vendor/autoload.php';

use Autoload\Calculator\Calculator;

$input3 = '';
if (isset($_POST['input1']) && isset($_POST['input2'])) {
    $calc = new Calculator();
    $input3 = $calc->maakInput3($_POST['input1'], $_POST['input2']);
}
?>

<h1>Autoload Calculator</h1>
<form method="post">
    Input 1: <input name="input1"><br>
    Input 2: <input name="input2"><br>
    <input type="submit" value="Maak Input 3">
</form>

<?php if ($input3 !== ''): ?>
    <h2>Input 3 = <?php echo $input3; ?></h2>
<?php endif; ?>