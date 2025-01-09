<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/database/database.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get room prices and the cost for add-ons
$roomPrices = getAllRoomPrices($database) ?? ['1' => 'N/A', '2' => 'N/A', '3' => 'N/A'];
$addons = getAddons($database) ?? [];
?>

<main>
    <!-- Hero -->
    <section class="hero">
        <video autoplay muted loop playsinline class="background-video">
            <source src="./assets/images/whisker-haven-retreat.mp4" type="video/mp4">
            Your browser does not support the video tag. Please upgrade to view this content.
        </video>
        <div class="hero-content">
            <h1>Whisker Haven Retreat</h1>
            <span class="stars">★★★</span>
            <p>Your purrfect getaway awaits!</p>
        </div>
        <div class="book-now">
            <a href="#rooms" class="cta">Choose your room</a>
        </div>
    </section>

    <!-- About section -->
    <section id="about">
        <h2>About the Whisker Haven Retreat</h2>
        <div class="about-container">
            <img src="./assets/images/sunset.jpg" alt="Sunset view of Purradise Island">
            <article>
                <h3>Welcome to Purradise Island</h3>
                <p> Nestled in the heart of the tropics, Purradise Island is a haven for both cats and cat lovers. With its lush greenery, golden beaches, and crystal-clear waters, it’s the perfect escape from the hustle and bustle of everyday life. The island is home to a thriving cat sanctuary where every stay helps support rescue efforts.</p>
            </article>
        </div>
        <div class="about-container middle">
            <img src="./assets/images/cat-lounge.png" alt="Outside view of cat lounge">
            <article>
                <h3>Your Home Away from Home</h3>
                <p>At The Whisker Haven Retreat, we combine luxury and compassion. Enjoy our beautifully designed cat-friendly rooms, each with direct access to our sanctuary. Guests can relax while helping our feline friends find forever homes. Whether you’re here to unwind or make a difference, you’ll leave with memories to cherish.</p>
            </article>
        </div>
        <div class="about-container">
            <img src="./assets/images/kittens.jpg" alt="Two kittens sitting on a table">
            <article>
                <h3>Stay for the Cause</h3>
                <p>Every booking at The Whisker Haven Retreat supports our rescue efforts. By staying with us, you help provide medical care, food, and shelter for our rescued cats. Meet our friendly residents, learn their stories, and even consider adopting your next furry companion!</p>
            </article>
        </div>
    </section>

    <!-- Rooms-section -->
    <section id="rooms">
        <h2>Our Rooms</h2>
        <div class="rooms-container">
            <!-- Budget Room -->
            <div class="room-selection">
                <img src="./assets/images/budget-room.png" alt="Budget hotel room">
                <h3>Cozy Comfort on a Budget</h3>
                <p>Experience the charm of The Whisker Haven Retreat without breaking the bank. Our Budget Rooms offer a snug and simple retreat featuring a queen-sized bed, minimalist decor, and cat-friendly touches like a small scratching post and cozy cat bed. Perfect for solo travelers or couples seeking an affordable stay while enjoying all the amenities of our tropical paradise.</p>
                <ul>
                    <li>Queen-sized bed</li>
                    <li>Private bathroom</li>
                    <li>Complimentary Wi-Fi</li>
                    <li>Cat bed and scratching post</li>
                </ul>
                <p class="price">Price per night: <?= $roomPrices[1]; ?></p>
                <a href="#booking" class="cta" data-room-id="1">Choose Budget Room</a>
            </div>

            <!-- Standard Room -->
            <div class="room-selection">
                <img src="./assets/images/standard-room.png" alt="Standard hotel room">
                <h3>Purrfect Balance of Comfort and Style</h3>
                <p>Our Standard Rooms are ideal for those who want a little extra space and luxury. Enjoy a king-sized bed, modern tropical decor, and a private balcony with stunning views of the lush island landscape. Cat lovers will appreciate the included climbing tree and cat toy set, ensuring your feline companions feel at home.</p>
                <ul>
                    <li>King-sized bed</li>
                    <li>Private balcony with tropical views</li>
                    <li>Cat climbing tree and toy set</li>
                    <li>Complimentary breakfast</li>
                </ul>
                <p class="price">Price per night: <?= $roomPrices[2]; ?></p>
                <a href="#booking" class="cta" data-room-id="2">Choose Standard Room</a>
            </div>

            <!-- Luxury Room -->
            <div class="room-selection">
                <img src="./assets/images/luxury-room.png" alt="Luxury hotel room">
                <h3>Indulge in Ultimate Feline-Friendly Luxury</h3>
                <p>For guests seeking a premium experience, our Luxury Rooms offer unmatched elegance and exclusivity. Bask in the spacious suite featuring a king-sized canopy bed, lavish tropical decor, and a personal outdoor jacuzzi. Your feline friends will adore the custom-designed cat play area and premium bedding. Treat yourself to the purrfect escape.</p>
                <ul>
                    <li>King-sized canopy bed</li>
                    <li>Indoor jacuzzi</li>
                    <li>Premium cat play area</li>
                    <li>Complimentary room service</li>
                </ul>
                <p class="price">Price per night: <?= $roomPrices[3]; ?></p>
                <a href="#booking" class="cta" data-room-id="3">Choose Luxury Room</a>
            </div>
        </div>
    </section>

    <!-- Add-ons/Features -->
    <section id="features">
        <h2>Add-ons to your stay</h2>
        <div class="features">
            <div class="features-container">
                <img src="./assets/images/rubiks-cube2.png" alt="Rubik's cube">
                <h3>Rubik's cube</h3>
                <p>Unwind and challenge your mind with our classic Rubik’s Cube! Whether you’re a beginner or a speedcubing pro, this timeless puzzle will keep you entertained during your stay. Complete the challenge and earn bonus points toward your tourist awards. It’s a fun way to relax and sharpen your skills</p>
                <p class="price">Price: <?= $addons[0]['price']; ?></p>
            </div>
            <div class="features-container">
                <img src="./assets/images/minibar.png" alt="Minibar">
                <h3>Minibar</h3>
                <p>Indulge in our premium minibar, stocked with refreshing beverages, tropical cocktails, and gourmet snacks. Perfect for a quiet evening in your room or a special celebration. Enjoy the taste of paradise at your convenience!</p>
                <p class="price">Price: <?= $addons[1]['price']; ?></p>
            </div>
            <div class="features-container">
                <img src="./assets/images/sibirian-cat.jpg" alt="Sibirian cat">
                <h3>Allergic? No problem!</h3>
                <p>Are you a cat lover but struggle with allergies? Spend time with our hypoallergenic Siberian cats in a privacy of your own room. Enjoy their playful, gentle nature without the worry of allergens. It’s a heartwarming and unique experience for cat enthusiasts!</p>
                <p class="price">Price: <?= $addons[2]['price']; ?></p>
            </div>
        </div>
    </section>

    <!-- Booking section -->
    <section id="booking">
        <h2>Book your room</h2>
        <div class="booking">
            <form method="POST" action="booking.php" class="booking-form">
                <label for="visitor_name">Your Name:</label>
                <input type="text" name="visitor_name" required>

                <label for="arrival_date">Arrival Date:</label>
                <input type="date" name="arrival_date" min="2025-01-01" max="2025-01-31" required>

                <label for="departure_date">Departure Date:</label>
                <input type="date" name="departure_date" min="2025-01-01" max="2025-01-31" required>

                <label for="room_id">Room:</label>
                <select name="room_id" required>
                    <option value="1">Budget Room - Cost: <?= $roomPrices[1]; ?></option>
                    <option value="2">Standard Room - Cost: <?= $roomPrices[2]; ?></option>
                    <option value="3">Luxury Room - Cost: <?= $roomPrices[3]; ?></option>
                </select>

                <!-- Add-ons -->
                <div class="addons">
                    <h4>Add-ons</h4>
                    <?php foreach ($addons as $addon): ?>
                        <label class="addon-label">
                            <input type="checkbox" name="addons[]" value="<?= $addon['id']; ?>" class="addon-checkbox">
                            <span class="addon-text"><?= $addon['name']; ?> (Cost: <?= $addon['price']; ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <!-- End of Add-ons-->

                <label for="transfer_code">Transfer Code:</label>
                <input type="text" name="transfer_code" required>
                <div class="total-cost">
                    <h3>Total Cost: <span id="total-cost">0</span></h3>
                </div>
                <button type="submit">Book Now</button>
            </form>

            <!-- Calender -->
            <div class="calender-container">
                <h3>Availability Budget Room</h3>
                <?php // Show the calender for room 1
                echo generateCalendar($database, 1, '2025-01-01');
                ?>
                <h3>Availability Standard Room</h3>
                <?php // Show the calender for room 2
                echo generateCalendar($database, 2, '2025-01-01');
                ?>
                <h3>Availability Luxury Room</h3>
                <?php // Show the calender for room 3
                echo generateCalendar($database, 3, '2025-01-01');
                ?>
            </div>
        </div>
    </section>
</main>
<script src="./assets/js/js-data.php"></script>
<script src="./assets/js/script.js"></script>


<?php
require __DIR__ . '/includes/footer.php';
?>