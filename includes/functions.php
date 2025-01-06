<?php

declare(strict_types=1);
require __DIR__ . '/../database/database.php'; //Connection to database
use GuzzleHttp\Client;
use benhall14\phpCalendar\Calendar;

//Function that saves the addons to the booking
function saveAddonsToBooking(PDO $database, int $bookingId, array $selectedAddons): void
{
    foreach ($selectedAddons as $addonId) {
        // Kontrollera om kombinationen redan finns
        $checkStmt = $database->prepare("SELECT COUNT(*) FROM booking_addons WHERE booking_id = :booking_id AND addon_id = :addon_id");
        $checkStmt->execute([
            'booking_id' => $bookingId,
            'addon_id' => $addonId,
        ]);

        if ($checkStmt->fetchColumn() == 0) { // Om kombinationen inte finns
            $stmt = $database->prepare("INSERT INTO booking_addons (booking_id, addon_id) VALUES (:booking_id, :addon_id)");
            $stmt->execute([
                'booking_id' => $bookingId,
                'addon_id' => $addonId,
            ]);
        }
    }
}

//Function that get the addons
function getAddons(PDO $database): array
{
    $statement = $database->prepare("SELECT * FROM addons");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

//Function to get which addons that are chosen when doing a booking
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

// REMEMBER TO TEST THIS!!!
//Calculate cost for add-ons 
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


//Function for getting the the dates the rooms are booked
function getBookedDates(PDO $database, int $roomId): array
{
    try {

        $query = "
            SELECT arrival_date, departure_date 
            FROM bookings 
            WHERE room_id = :room_id
        ";

        $statement = $database->prepare($query);
        $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $statement->execute();
        $bookedDates = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $bookedDates;
    } catch (PDOException $e) {
        // Logga eller hantera eventuella fel
        echo "Database error: " . $e->getMessage();
        return [];
    }
}

//Function to generate a calender that shows when the rooms are available
function generateCalendar(PDO $database, int $roomId, string $startDate, string $theme = 'purple'): string
{
    $calendar = new Calendar();
    $calendar->useMondayStartingDate();

    // Get the booked dates from the database
    $rawEvents = getBookedDates($database, $roomId);

    // Format the date to right format for the calender
    $formattedEvents = array_map(function ($booking) {
        return [
            'start' => $booking['arrival_date'],  // Datum för incheckning
            'end' => $booking['departure_date'],  // Datum för utcheckning
            'mask' => true,                      // Om dagarna ska maskas
        ];
    }, $rawEvents);

    // Add the booked dates to the calender
    $calendar->addEvents($formattedEvents);

    // Generate and return HTML for the calender
    return $calendar->draw($startDate, $theme);
}

//Function to validate the transfer code
function validateTransferCode(string $transferCode, float $totalCost): bool
{
    $client = new Client();
    $url = 'https://www.yrgopelago.se/centralbank/transferCode';

    try {
        // Send a POST request
        $response = $client->post($url, [
            'json' => [
                'transferCode' => $transferCode,
                'totalcost' => $totalCost,
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        // Interpret the API response
        $responseData = json_decode($response->getBody()->getContents(), true);

        // Check if the status is "success"
        return isset($responseData['status']) && $responseData['status'] === 'success';
    } catch (Exception $e) {
        // Print the error and return false on failure
        echo "Error validating transfer code: " . $e->getMessage();
        return false;
    }
}

//Function to sanitize input from forms
function sanitizeInput(string $input): string
{
    return htmlspecialchars(trim($input));
}



//Function for creating a booking message
// function getBookingResponse($arrivalDate, $departureDate, $totalCost): array
// {
//     $message = [
//         'island' => 'The name of my island',
//         'hotel' => 'The name of my hotel',
//         'arrival_date' => $arrivalDate,
//         'departure_date' => $departureDate,
//         'total_cost' => $totalCost,
//         'stars' => 2,
//         //Add features here
//         'additional_info' => [
//             'greeting' => 'Thank you for choosing our hotel!',
//             'image_url' => 'ImageURL'
//         ]
//     ];

//     return $message;
// }
function getBookingResponse($arrivalDate, $departureDate, $totalCost, array $addons = []): array
{
    $message = [
        'island' => 'The name of my island',
        'hotel' => 'The name of my hotel',
        'arrival_date' => $arrivalDate,
        'departure_date' => $departureDate,
        'total_cost' => $totalCost,
        'stars' => 2,
        'features' => $addons, // Lägg till valda add-ons här
        'additional_info' => [
            'greeting' => 'Thank you for choosing our hotel!',
            'image_url' => 'ImageURL',
        ],
    ];

    return $message;
}

function createJsonResponse(array $data): void
{
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

//Function to validate the input from the booking form
function validateBookingInput(string $name, string $arrival, string $departure, int $roomId, string $transferCode): bool
{
    if (empty($name) || empty($arrival) || empty($departure) || $roomId <= 0 || empty($transferCode)) {
        return false;
    }
    return true;
}

//Check if a room is available, returns true if rooms is available
function isRoomAvailable(PDO $database, int $roomId, string $arrivalDate, string $departureDate): bool
{
    $query = "SELECT id FROM bookings WHERE room_id = :room_id AND ((arrival_date < :departure_date AND departure_date > :arrival_date))";
    $statement = $database->prepare($query);

    $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
    $statement->bindParam(':arrival_date', $arrivalDate, PDO::PARAM_STR);
    $statement->bindParam(':departure_date', $departureDate, PDO::PARAM_STR);

    $statement->execute();

    // Fetch all matching bookings
    $conflictingBookings = $statement->fetchAll(PDO::FETCH_ASSOC);

    // If the array is empty, the room is available
    return count($conflictingBookings) === 0;
}


//Function to add a booking to the database
//Change variable names so they isn't the same used as in the isRoomAvailable function?
// function saveBooking(PDO $database, string $visitorName, int $roomId, string $arrivalDate, string $departureDate, string $transferCode): bool
// {
//     $query = 'INSERT INTO bookings (visitor_name, arrival_date, departure_date, room_id, transfer_code) VALUES (:visitor_name, :arrival_date, :departure_date, :room_id, :transfer_code)';
//     $statement = $database->prepare($query);

//     $statement->bindParam(':visitor_name', $visitorName, PDO::PARAM_STR);
//     $statement->bindParam(':arrival_date', $arrivalDate, PDO::PARAM_STR);
//     $statement->bindParam(':departure_date', $departureDate, PDO::PARAM_STR);
//     $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
//     $statement->bindParam(':transfer_code', $transferCode, PDO::PARAM_STR);

//     try {
//         $statement->execute();
//         return true;
//     } catch (PDOException $e) {
//         return "Error: " . $e->getMessage();
//     }
// }
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
        return (int) $database->lastInsertId(); // Returnera det nyss insatta ID:t
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0; // Returnera 0 som ett felvärde
    }
}


//function to fetch the room price from the database. Returns an integer
function getRoomPrices(PDO $database, int $roomId)
{
    try {
        $query = 'SELECT type, price FROM rooms';
        $statement = $database->prepare("SELECT * FROM rooms WHERE id = :room_id");
        $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $statement->execute();
        $roomPrices = $statement->fetch(PDO::FETCH_ASSOC);
        return $roomPrices['price'];
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage(); //Ta bort denna sen!!
    }
}


//function to calculate the number of days when doing a booking
function calculateNumberOfDays($arrivalDate, $departureDate)
{
    // Convert date strings to timestamps
    $arrivalTimestamp = strtotime($arrivalDate);
    $departureTimestamp = strtotime($departureDate);

    // Calculate the difference in seconds, then convert to days
    $secondsDifference = $departureTimestamp - $arrivalTimestamp;
    $daysBooked = $secondsDifference / (60 * 60 * 24); // Convert seconds to days

    return (int)$daysBooked;
}


//function for calulate the total cost of the stay
//needs to add the cost of add-ons later
// function totalCost(PDO $database, int $roomId, string $arrivalDate, string $departureDate): float
// {
//     $basePrice = getRoomPrices($database, $roomId);
//     $numberOfDays = calculateNumberOfDays($arrivalDate, $departureDate);
//     $total = $basePrice * $numberOfDays;

//     //Apply 30% discount for bookings longer than 3 days, remove comment to activate later!
//     // if ($numberOfDays > 3) {
//     //     $total *= 0.7; 
//     // }

//     return $total;
// }
function totalCost(PDO $database, int $roomId, string $arrivalDate, string $departureDate, array $selectedAddons = []): float
{
    // Hämta rumspriset från databasen
    $basePrice = getRoomPrices($database, $roomId);

    // Beräkna antal bokade dagar
    $numberOfDays = calculateNumberOfDays($arrivalDate, $departureDate);

    // Beräkna rumspriset för hela vistelsen
    $roomCost = $basePrice * $numberOfDays;

    // Beräkna kostnaden för valda tillval
    $addonCost = calculateAddonCost($selectedAddons, $database);

    // Totalkostnad = rumspris + kostnad för tillval
    $total = $roomCost + $addonCost;

    // Tillämpa 30% rabatt för vistelser längre än 3 dagar
    if ($numberOfDays > 3) {
        $total *= 0.7; // Rabatt på 30%
    }

    return $total;
}
