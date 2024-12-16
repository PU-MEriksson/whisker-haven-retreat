<?php

declare(strict_types=1);
require __DIR__ . '/../database/database.php'; //Connection to database

// Functions for bookings

//To see if a room is available
function isRoomAvailable(PDO $database, int $roomId, string $arrivalDate, string $departureDate): bool
{
    $statement = $database->prepare("
        SELECT id
        FROM bookings
        WHERE room_id = :room_id
        AND (
            (arrival_date < :departure_date AND departure_date > :arrival_date)
        )
    ");
    $statement->execute([
        ':room_id' => $roomId,
        ':arrival_date' => $arrivalDate,
        ':departure_date' => $departureDate
    ]);

    // Fetch all matching bookings
    $conflictingBookings = $statement->fetchAll(PDO::FETCH_ASSOC);

    // If the array is empty, the room is available
    return count($conflictingBookings) === 0;
}


//Test variables
$roomId = 1;
$arrivalDate = "2025-01-11";
$departureDate = "2025-01-12";

$isAvailable = isRoomAvailable($database, $roomId, $arrivalDate, $departureDate);
echo $isAvailable;

// Output the result
if ($isAvailable === true) {
    echo "Room is available!";
} else {
    echo "Room is not available.";
}
