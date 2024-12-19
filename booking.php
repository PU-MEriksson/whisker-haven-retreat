<?php

declare(strict_types=1);
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/database/database.php';

if (isset($_POST['visitor_name'], $_POST['arrival_date'], $_POST['departure_date'], $_POST['room_id'], $_POST['transfer_code'])) {

    //Variables used in the booking process
    $visitorName = sanitizeInput($_POST['visitor_name'] ?? '');
    $arrivalDate = sanitizeInput($_POST['arrival_date'] ?? '');
    $departureDate = sanitizeInput($_POST['departure_date'] ?? '');
    $roomId = (int)($_POST['room_id'] ?? 0);
    $transferCode = sanitizeInput($_POST['transfer_code'] ?? '');

    //Validates the input so none of the forms are empty, if not get the base price and total cost
    if (validateBookingInput($visitorName, $arrivalDate, $departureDate, $roomId, $transferCode)) {
        $basePrice = getRoomPrices($database, $roomId);
        $totalCost = totalCost($database, $basePrice, $arrivalDate, $departureDate);

        if ($arrivalDate > $departureDate) {
            echo "The date for the arrival needs to be before the departure date";
        }

        //Check if room is available
        if (isRoomAvailable($database, $roomId, $arrivalDate, $departureDate)) {

            //Validate transfer code

            //Save booking
            saveBooking($database, $visitorName, $roomId, $arrivalDate, $departureDate, $transferCode);

            //Send json-response
            $bookingResponse = getBookingResponse($arrivalDate, $departureDate, $totalCost);
            createJsonResponse($bookingResponse);
        } else {
            echo "The room is not available on the selected dates";
        }
    }
}
