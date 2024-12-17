<?php

//Start a database connection
$database = new PDO('sqlite:' . __DIR__ . '/../database/hotel.db');
$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
