<?php

//Start a database connection
// $database = new PDO('sqlite:' . __DIR__ . '/../database/hotel.db');
// $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $database = new PDO('sqlite:' . realpath(__DIR__ . '/../database/hotel.db'));
// $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



// Kontrollera om databasfilen finns
if (!file_exists(__DIR__ . '/../database/hotel.db')) {
    die("Database file not found at: " . __DIR__ . '/../database/hotel.db');
}

try {
    // Starta en databasanslutning
    $database = new PDO('sqlite:' . __DIR__ . '/../database/hotel.db');
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
