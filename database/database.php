<?php

//Start a database connection

// Check if the database file exists
if (!file_exists(__DIR__ . '/../database/hotel.db')) {
    die("Database file not found at: " . __DIR__ . '/../database/hotel.db');
}

try {
    // Start a new connection
    $database = new PDO('sqlite:' . __DIR__ . '/../database/hotel.db');
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
