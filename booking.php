<?php

declare(strict_types=1);
require __DIR__ . '/includes/functions.php';

if (isset($_POST['visitor_name'], $_POST['arrival_date'], $_POST['departure_date'], $_POST['room_id'], $_POST['transfer_code'])) {

    //Variables used in the booking process
    $visitorName = sanitizeInput($_POST['visitor_name'] ?? '');
    $arrivalDate = sanitizeInput($_POST['arrival_date'] ?? '');
    $departureDate = sanitizeInput($_POST['departure_date'] ?? '');
    $roomId = (int)($_POST['room_id'] ?? 0);
    $transferCode = sanitizeInput($_POST['transfer_code'] ?? '');

    //Validates the input so none of the forms are empty
    if (validateBookingInput($visitorName, $arrivalDate, $departureDate, $roomId, $transferCode)) {

        //The booking sequence
        //Check if room is available, if room is available get the base price and total cost
        if (isRoomAvailable($database, $roomId, $arrivalDate, $departureDate)) {
            $basePrice = getRoomPrices($database, $roomId);
            $totalCost = totalCost($basePrice, $arrivalDate, $departureDate);

        } else {
            echo "The room is not available on the chosen dates"
        }

        // Fetch room price 
        // Calculate the total cost 
        // Check availability 
        // Validate the transfer code 
        // Save booking 
    }

    //Test
    // echo "Name: $visitorName<br>";
    // echo "Arrival date: $arrivalDate <br>";
    // echo "Departure date: $departureDate <br>";
    // echo "Room id: $roomId <br>";
    // echo "Transfer code: $transferCode ";


}

//Potential workflow for booking a room:
// 1.Fetch Room Details - use a function to fetch the room price from the database
// 2. Calculate the total cost - use a function that calculates the total cost, based on number of days and extra addons
// 3. Check availability - use a function that checks if the rooms is available on the chosen date - KLART!
// 4. Validate the transfer code - FIGURE OUT HOW TO DO THIS
// 5. Save booking - with a saveBooking function - KLART!
