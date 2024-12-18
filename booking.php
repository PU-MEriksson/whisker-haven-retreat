<?php

declare(strict_types=1);

if (isset($_POST['visitor_name'], $_POST['arrival_date'], $_POST['departure_date'], $_POST['room_id'], $_POST['transfer_code'])) {

    //Variables used in the booking process, I need to sanitize these
    $visitorName = $_POST['visitor_name'];
    $arrivalDate = $_POST['arrival_date'];
    $departureDate = $_POST['departure_date'];
    $roomId = $_POST['room_id'];
    $transferCode = $_POST['transfer_code'];

    echo "Name: $visitorName<br>";
    echo "Arrival date: $arrivalDate <br>";
    echo "Departure date: $departureDate <br>";
    echo "Room id: $roomId <br>";
    echo "Transfer code: $transferCode ";
}

//Potential workflow for booking a room:
// 1.Fetch Room Details - use a function to fetch the room price from the database
// 2. Calculate the total cost - use a function that calculates the total cost, based on number of days and extra addons
// 3. Check availability - use a function that checks if the rooms is available on the chosen date - KLART!
// 4. Validate the transfer code - FIGURE OUT HOW TO DO THIS
// 5. Save booking - with a saveBooking function - KLART!
