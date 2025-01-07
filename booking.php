<?php

declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/database/database.php';


//Booking process
if (isset($_POST['visitor_name'], $_POST['arrival_date'], $_POST['departure_date'], $_POST['room_id'], $_POST['transfer_code'])) {

    //Variables used in the booking process
    $visitorName = sanitizeInput($_POST['visitor_name'] ?? '');
    $arrivalDate = sanitizeInput($_POST['arrival_date'] ?? '');
    $departureDate = sanitizeInput($_POST['departure_date'] ?? '');
    $roomId = (int)($_POST['room_id'] ?? 0);
    $transferCode = sanitizeInput($_POST['transfer_code'] ?? '');
    $selectedAddons = $_POST['addons'] ?? [];

    //Validates the input so none of the forms are empty, if not get the base price, total cost and cost for add-ons
    if (validateBookingInput($visitorName, $arrivalDate, $departureDate, $roomId, $transferCode)) {
        $basePrice = getRoomPrices($database, $roomId);
        $totalAddonCost = calculateAddonCost($selectedAddons, $database); // Beräkna add-ons kostnad
        $totalCost = $basePrice * calculateNumberOfDays($arrivalDate, $departureDate) + $totalAddonCost;

        if ($arrivalDate > $departureDate) {
            echo "The date for the arrival needs to be before the departure date";
        }

        // Check if room is available
        if (isRoomAvailable($database, $roomId, $arrivalDate, $departureDate)) {

            // Validate the transfer code
            if (validateTransferCode($transferCode, $totalCost)) {
                $numberOfDays = calculateNumberOfDays($arrivalDate, $departureDate);

                // Attempt to deposit the funds
                if (depositFunds($transferCode, $numberOfDays)) {
                    // Save the booking to the database
                    $bookingId = saveBooking($database, $visitorName, $roomId, $arrivalDate, $departureDate, $transferCode);
                } else {
                    echo "Error: Could not deposit funds. Please contact support.";
                }
            }

            if ($bookingId > 0) {
                // Save chosen add-ons to database
                saveAddonsToBooking($database, $bookingId, $selectedAddons);

                // Hämta detaljer om valda add-ons
                $addonDetails = getAddonDetails($database, $selectedAddons);

                // Skapa JSON-respons
                $bookingResponse = getBookingResponse($arrivalDate, $departureDate, $totalCost, $addonDetails);
                createJsonResponse($bookingResponse);
            } else {
                echo "Error: Could not save booking.";
            }
        } else {
            // Print message if the transfer code isn't valid
            echo "Invalid transfer code. Please try again.";
        }
    } else { // Print message if the room isn't available on the chosen date
        echo "The room is not available on the selected dates.";
    }
}
