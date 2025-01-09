<?php

declare(strict_types=1);

// ============================
// SETUP AND IMPORTS
// ============================

require __DIR__ . '/../database/database.php';

use GuzzleHttp\Client;
use benhall14\phpCalendar\Calendar;

// ============================
// BOOKING CORE FUNCTIONS
// ============================

// Saves a new booking to the database
// Returns the ID of the newly created booking, or 0 if creation fails
function saveBooking(PDO $database, string $visitorName, int $roomId, string $arrivalDate, string $departureDate, string $transferCode): int
{
    $query = 'INSERT INTO bookings (visitor_name, arrival_date, departure_date, room_id, transfer_code) VALUES (:visitor_name, :arrival_date, :departure_date, :room_id, :transfer_code)';
    $statement = $database->prepare($query);

    $statement->bindParam(':visitor_name', $visitorName, PDO::PARAM_STR);
    $statement->bindParam(':arrival_date', $arrivalDate, PDO::PARAM_STR);
    $statement->bindParam(':departure_date', $departureDate, PDO::PARAM_STR);
    $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
    $statement->bindParam(':transfer_code', $transferCode, PDO::PARAM_STR);

    try {
        $statement->execute();
        return (int) $database->lastInsertId();
    } catch (PDOException $e) {
        return 0;
    }
}

// Validates that all required booking fields are present and valid
// Returns true if all inputs are valid, false otherwise
function validateBookingInput(string $name, string $arrival, string $departure, int $roomId, string $transferCode): bool
{
    if (empty($name) || empty($arrival) || empty($departure) || $roomId <= 0 || empty($transferCode)) {
        return false;
    }
    return true;
}

// Checks if a room is available for the specified date range
// Returns true if room is available, false if already booked
function isRoomAvailable(PDO $database, int $roomId, string $arrivalDate, string $departureDate): bool
{
    $query = "SELECT id FROM bookings WHERE room_id = :room_id AND ((arrival_date < :departure_date AND departure_date > :arrival_date))";
    $statement = $database->prepare($query);
    $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
    $statement->bindParam(':arrival_date', $arrivalDate, PDO::PARAM_STR);
    $statement->bindParam(':departure_date', $departureDate, PDO::PARAM_STR);
    $statement->execute();
    $conflictingBookings = $statement->fetchAll(PDO::FETCH_ASSOC);
    return count($conflictingBookings) === 0;
}


// ============================
// PRICING AND COST CALCULATIONS
// ============================

// Calculates the total cost of selected add-ons
// Returns total cost of selected add-ons
function calculateAddonCost(array $selectedAddons, PDO $database): float
{
    if (empty($selectedAddons)) {
        return 0.0;
    }
    $addonIds = implode(',', array_map('intval', $selectedAddons));
    $stmt = $database->prepare("SELECT SUM(price) as total FROM addons WHERE id IN ($addonIds)");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0.0;
}

// Calculates the total cost of a stay including room price and add-ons
// Returns total cost of the stay
function totalCost(PDO $database, int $roomId, string $arrivalDate, string $departureDate, array $selectedAddons = []): float
{
    $basePrice = getRoomPrices($database, $roomId);
    $numberOfDays = calculateNumberOfDays($arrivalDate, $departureDate);
    $roomCost = $basePrice * $numberOfDays;
    $addonCost = calculateAddonCost($selectedAddons, $database);
    return $roomCost + $addonCost;
}

// Retrieves the price for a specific room
// Return price of the room or 0 if it fails
function getRoomPrices(PDO $database, int $roomId): int
{
    try {
        $statement = $database->prepare("SELECT * FROM rooms WHERE id = :room_id");
        $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $statement->execute();
        $roomPrices = $statement->fetch(PDO::FETCH_ASSOC);
        return $roomPrices['price'];
    } catch (PDOException $e) {
        return 0;
    }
}

// Retrieves prices for all rooms
// Return associative array of room IDs and their prices
function getAllRoomPrices(PDO $database): array
{
    try {
        $query = 'SELECT id, type, price FROM rooms';
        $statement = $database->prepare($query);
        $statement->execute();
        $rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

        $roomPrices = [];
        foreach ($rooms as $room) {
            $roomPrices[$room['id']] = $room['price'];
        }
        return $roomPrices;
    } catch (PDOException $e) {
        return [];
    }
}

// ============================
// ADD-ON/FEATURES MANAGEMENT
// ============================

// Saves selected add-ons to database
function saveAddonsToBooking(PDO $database, int $bookingId, array $selectedAddons): void
{
    foreach ($selectedAddons as $addonId) {
        $checkStmt = $database->prepare("SELECT COUNT(*) FROM booking_addons WHERE booking_id = :booking_id AND addon_id = :addon_id");
        $checkStmt->execute([
            'booking_id' => $bookingId,
            'addon_id' => $addonId,
        ]);

        if ($checkStmt->fetchColumn() == 0) {
            $stmt = $database->prepare("INSERT INTO booking_addons (booking_id, addon_id) VALUES (:booking_id, :addon_id)");
            $stmt->execute([
                'booking_id' => $bookingId,
                'addon_id' => $addonId,
            ]);
        }
    }
}

// Retrieves all available add-ons from the database
// Returns an array of all add-ons with their details
function getAddons(PDO $database): array
{
    $statement = $database->prepare("SELECT * FROM addons");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Gets details for specific add-ons by their IDs
// Returns an associative array of add-on details (name and price)
function getAddonDetails(PDO $database, array $selectedAddons): array
{
    if (empty($selectedAddons)) {
        return [];
    }

    $addonIds = implode(',', array_map('intval', $selectedAddons));
    $stmt = $database->prepare("SELECT name, price FROM addons WHERE id IN ($addonIds)");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ============================
// CALENDAR AND DATE OPERATIONS
// ============================

// Generates an HTML calendar showing room availability
// Return string HTML representation of the calendar
function generateCalendar(PDO $database, int $roomId, string $startDate): string
{
    try {
        $calendar = new Calendar();
        $calendar->useMondayStartingDate();

        $rawEvents = getBookedDates($database, $roomId);

        if (!$rawEvents) {
            return "<p>Calendar data unavailable. Please try again later.</p>";
        }

        $formattedEvents = array_map(function ($booking) {
            return [
                'start' => $booking['arrival_date'],
                'end' => $booking['departure_date'],
                'mask' => true,
            ];
        }, $rawEvents);

        $calendar->addEvents($formattedEvents);
        return $calendar->draw($startDate);
    } catch (Exception $e) {
        return "<p>Calendar unavailable due to an error.</p>";
    }
}

// Retrieves all booked dates for a specific room
// Return an array of booked date ranges
function getBookedDates(PDO $database, int $roomId): array
{
    try {
        $query = "SELECT arrival_date, departure_date FROM bookings WHERE room_id = :room_id";
        $statement = $database->prepare($query);
        $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Calculates the number of days between two dates
// Return number of days between dates
function calculateNumberOfDays($arrivalDate, $departureDate)
{
    $arrivalTimestamp = strtotime($arrivalDate);
    $departureTimestamp = strtotime($departureDate);
    $secondsDifference = $departureTimestamp - $arrivalTimestamp;
    return (int)($secondsDifference / (60 * 60 * 24));
}

// ============================
// PAYMENT AND BANKING
// ============================

// Validates a transfer code with the central bank
// Returns true if transfer code is valid
function validateTransferCode(string $transferCode, float $totalCost): bool
{
    $client = new Client();
    $url = 'https://www.yrgopelago.se/centralbank/transferCode';

    try {
        $response = $client->post($url, [
            'json' => [
                'transferCode' => $transferCode,
                'totalcost' => $totalCost,
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        return isset($responseData['status']) && $responseData['status'] === 'success';
    } catch (Exception $e) {
        return false;
    }
}

// Deposits funds to the central bank
// Returns true if deposit was successful
function depositFunds(string $transferCode, int $numberOfDays): bool
{
    $client = new Client();
    $url = 'https://www.yrgopelago.se/centralbank/deposit';

    try {
        $response = $client->post($url, [
            'json' => [
                'user' => 'Malin',
                'transferCode' => $transferCode,
                'numberOfDays' => $numberOfDays,
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        return isset($responseData['status']) && $responseData['status'] === 'success';
    } catch (Exception $e) {
        return false;
    }
}

// ============================
// RESPONSE FORMATTING
// ============================

// Creates a formatted booking response message
// Return array formatted booking response
function getBookingResponse($arrivalDate, $departureDate, $totalCost, array $addons = []): array
{
    return [
        'island' => 'Purradise Island',
        'hotel' => 'Whisker Haven Retreat',
        'arrival_date' => $arrivalDate,
        'departure_date' => $departureDate,
        'total_cost' => $totalCost,
        'stars' => 3,
        'features' => $addons,
        'additional_info' => [
            'greeting' => 'Thank you for staying at Whisker Haven Retreat!',
            'image_url' => 'https://c.tenor.com/HofsbGhfgAEAAAAd/tenor.gif',
        ],
    ];
}

// Formats and outputs data as JSON
function createJsonResponse(array $data): void
{
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

// ============================
// UTILITY FUNCTIONS
// ============================

// Sanitize the inputs from forms
function sanitizeInput(string $input): string
{
    return htmlspecialchars(trim($input));
}
