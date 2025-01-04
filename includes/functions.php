<?php

declare(strict_types=1);
require __DIR__ . '/../database/database.php'; //Connection to database
use GuzzleHttp\Client;
use benhall14\phpCalendar\Calendar;

//Function that get the addons
function getAddons(PDO $database): array
{
    $statement = $database->prepare("SELECT * FROM addons");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
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
function getBookingResponse($arrivalDate, $departureDate, $totalCost): array
{
    $message = [
        'island' => 'The name of my island',
        'hotel' => 'The name of my hotel',
        'arrival_date' => $arrivalDate,
        'departure_date' => $departureDate,
        'total_cost' => $totalCost,
        'stars' => 2,
        //Add features here
        'additional_info' => [
            'greeting' => 'Thank you for choosing our hotel!',
            'image_url' => 'ImageURL'
        ]
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
function saveBooking(PDO $database, string $visitorName, int $roomId, string $arrivalDate, string $departureDate, string $transferCode): bool
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
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
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
function totalCost(PDO $database, int $roomId, string $arrivalDate, string $departureDate): float
{
    $basePrice = getRoomPrices($database, $roomId);
    $numberOfDays = calculateNumberOfDays($arrivalDate, $departureDate);
    $total = $basePrice * $numberOfDays;

    //Apply 30% discount for bookings longer than 3 days, remove comment to activate later!
    // if ($numberOfDays > 3) {
    //     $total *= 0.7; 
    // }

    return $total;
}
