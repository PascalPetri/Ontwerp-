<?php
// Standaardlocatie: Amsterdam
$latitude = 52.3676;
$longitude = 4.9041;
$locationName = "Amsterdam";

$error = null;
$weather = null;

// Plaatsnaam uit formulier
if (!empty($_GET['plaats'])) {
    $plaats = trim($_GET['plaats']);

    // Nominatim API (plaats → coördinaten)
    $nominatimUrl = "https://nominatim.openstreetmap.org/search?" . http_build_query([
        'q' => $plaats,
        'format' => 'json',
        'limit' => 1
    ]);

    $opts = [
        "http" => [
            "header" => "User-Agent: WeerApp/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);

    $geoResponse = file_get_contents($nominatimUrl, false, $context);
    $geoData = json_decode($geoResponse, true);

    if (!empty($geoData[0])) {
        $latitude = $geoData[0]['lat'];
        $longitude = $geoData[0]['lon'];
        $locationName = htmlspecialchars($plaats);
    } else {
        $error = "Plaats niet gevonden, Amsterdam wordt gebruikt";
    }
}

// Weer API
$apiUrl = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&current_weather=true";

try {
    $response = file_get_contents($apiUrl);
    if ($response === false) {
        throw new Exception("Kan de weersdata niet ophalen");
    }

    $data = json_decode($response, true);

    if (!isset($data['current_weather'])) {
        throw new Exception("Geen weersdata beschikbaar");
    }

    $weather = $data['current_weather'];

} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Weerbericht</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="weather-box">

    <h1>Weer in <?= $locationName ?></h1>

    <form method="get">
        <input
            type="text"
            name="plaats"
            placeholder="Voer plaatsnaam in"
            value="<?= isset($_GET['plaats']) ? htmlspecialchars($_GET['plaats']) : '' ?>"
        >
        <button type="submit">Zoek</button>
    </form>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($weather): ?>
        <p>🌡 Temperatuur: <?= $weather['temperature'] ?>°C</p>
        <p>💨 Windsnelheid: <?= $weather['windspeed'] ?> km/h</p>
    <?php endif; ?>

</div>
</body>
</html>
