<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/database/database.php';

// Booking process
if (isset($_POST['visitor_name'], $_POST['arrival_date'], $_POST['departure_date'], $_POST['room_id'], $_POST['transfer_code'])) {

    // Variables used in the booking process
    $visitorName = sanitizeInput($_POST['visitor_name'] ?? '');
    $arrivalDate = sanitizeInput($_POST['arrival_date'] ?? '');
    $departureDate = sanitizeInput($_POST['departure_date'] ?? '');
    $roomId = (int)($_POST['room_id'] ?? 0);
    $transferCode = sanitizeInput($_POST['transfer_code'] ?? '');
    $selectedAddons = $_POST['addons'] ?? [];

    // Validate the input so none of the forms are empty
    if (!validateBookingInput($visitorName, $arrivalDate, $departureDate, $roomId, $transferCode)) {
        createJsonResponse(['message' => 'Invalid input. Please check your data.'], false);
        exit;
    }

    // Ensure the arrival date is earlier than the departure date
    if ($arrivalDate > $departureDate) {
        createJsonResponse(['message' => 'The arrival date must be before the departure date.'], false);
        exit;
    }
    // Check if room is available
    if (!isRoomAvailable($database, $roomId, $arrivalDate, $departureDate)) {
        createJsonResponse(['message' => 'The room is not available on the selected dates.'], false);
        exit;
    }

    // Calculate the room price, add-on costs, and total booking cost
    $basePrice = getRoomPrices($database, $roomId);
    $totalAddonCost = calculateAddonCost($selectedAddons, $database);
    $totalCost = $basePrice * calculateNumberOfDays($arrivalDate, $departureDate) + $totalAddonCost;


    // Validate the transfer code
    if (!validateTransferCode($transferCode, $totalCost)) {
        createJsonResponse(['message' => 'Invalid transfer code. Please try again.'], false);
        exit;
    }

    // Deposit the funds
    if (!depositFunds($transferCode, calculateNumberOfDays($arrivalDate, $departureDate))) {
        createJsonResponse(['message' => 'Error: Could not deposit funds. Please contact support.'], false);
        exit;
    }

    // Save booking to database
    $bookingId = saveBooking($database, $visitorName, $roomId, $arrivalDate, $departureDate, $transferCode);

    // If booking is successful:
    // 1. Save add-ons to the database.
    // 2. Retrieve add-on details.
    // 3. Generate and send a JSON response with booking details.
    if ($bookingId > 0) {
        saveAddonsToBooking($database, $bookingId, $selectedAddons);
        $addonDetails = getAddonDetails($database, $selectedAddons);
        $bookingResponse = getBookingResponse($arrivalDate, $departureDate, $totalCost, $addonDetails);
        createJsonResponse($bookingResponse);
    } else {
        createJsonResponse(['message' => 'Could not save booking.'], false);
    }
}
