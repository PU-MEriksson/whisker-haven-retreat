<?php

declare(strict_types=1);


//Potential workflow for booking a room:
// 1.Fetch Room Details - use a function to fetch the room price from the database
// 2. Calculate the total cost - use a function that calculates the total cost, based on number of days and extra addons
// 3. Check availability - use a function that checks if the rooms is available on the chosen date
// 4. Validate the transfer code - FIGURE OUT HOW TO DO THIS
// 5. Save booking - with a saveBooking function

//Variables to see if a room is available. I need to change these to form input later!
$roomId = 2;
$arrivalDate = "2025-01-05";
$departureDate = "2025-01-12";
