<?php
// Generera JavaScript-data
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../database/database.php';

header('Content-Type: application/javascript');

// Hämta rumspriser och add-ons från databasen
$roomPrices = getAllRoomPrices($database);
$addons = getAddons($database);

// Generera JavaScript-data
echo "const roomPrices = " . json_encode($roomPrices) . ";\n";
echo "const addonPrices = " . json_encode(array_column($addons, 'price', 'id')) . ";";
