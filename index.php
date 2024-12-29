<?php

require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/functions.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// //Show the calender for room 1
// echo generateCalendar($database, 1, '2025-01-01');
// //Show the calender for room 2
// echo generateCalendar($database, 2, '2025-01-01');
// //Show the calender for room 3
// echo generateCalendar($database, 3, '2025-01-01');
?>

<main>
    <!-- Hero -->
    <section class="hero">
        <video autoplay muted loop playsinline class="background-video">
            <source src="./assets/images/whisker-haven-retreat.mp4" type="video/mp4">
        </video>
        <div class="hero-content">
            <h1>Welcome to Whisker Haven Retreat</h1>
            <p>Your purrfect getaway awaits!</p>
        </div>
        <div class="book-now">
            <a href="#booking" class="cta">Book Now</a>
        </div>
    </section>

    <!-- About section -->
    <section id="about">
        <h2>About the Whisker Haven Retreat</h2>
        <div class="about-container">
            <img src="./assets/images/about-placeholder.jpg" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit earum hic in deserunt quae aliquid obcaecati laudantium enim voluptatum voluptas esse autem ab, rerum ipsum vel nam architecto repellendus iste?</p>
        </div>
        <div class="about-container middle">
            <img src="./assets/images/about-placeholder.jpg" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit earum hic in deserunt quae aliquid obcaecati laudantium enim voluptatum voluptas esse autem ab, rerum ipsum vel nam architecto repellendus iste?</p>
        </div>
        <div class="about-container">
            <img src="./assets/images/about-placeholder.jpg" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit earum hic in deserunt quae aliquid obcaecati laudantium enim voluptatum voluptas esse autem ab, rerum ipsum vel nam architecto repellendus iste?</p>
        </div>
    </section>

    <!-- Rooms-section -->
    <section id="rooms">
        <h2>Our rooms</h2>
        <div class="rooms-container">
            <div class="room-selection">
                <img src="./assets/images/placeholder-room.jpg" alt="Budget hotel room">
                <h3>Budget</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel eius nostrum facilis laboriosam accusamus cupiditate quam, consequuntur aspernatur tempore placeat, soluta officia molestiae sit alias? Assumenda numquam aut sapiente quam!</p>
                <a href="#booking" class="cta">Book Now</a>
            </div>
            <div class="room-selection">
                <img src="/assets/images/placeholder-room.jpg" alt="Standard hotel room">
                <h3>Standard</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel eius nostrum facilis laboriosam accusamus cupiditate quam, consequuntur aspernatur tempore placeat, soluta officia molestiae sit alias? Assumenda numquam aut sapiente quam!</p>
                <a href="#booking" class="cta">Book Now</a>
            </div>
            <div class="room-selection">
                <img src="/assets/images/placeholder-room.jpg" alt="Luxury hotel room">
                <h3>Luxury</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel eius nostrum facilis laboriosam accusamus cupiditate quam, consequuntur aspernatur tempore placeat, soluta officia molestiae sit alias? Assumenda numquam aut sapiente quam!</p>
                <a href="#booking" class="cta">Book Now</a>
            </div>
        </div>

    </section>

    <!-- Add-ons/Features -->
    <section id="features">
        <h2>Add-ons to your stay</h2>
        <div class="features">
            <div class="features-container">
                <h3>Feature</h3>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloribus alias tempora est incidunt, excepturi nobis, harum similique exercitationem at animi quasi nemo inventore sint ex iure eius iusto, reiciendis consequuntur!</p>
            </div>
            <div class="features-container">
                <h3>Feature</h3>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloribus alias tempora est incidunt, excepturi nobis, harum similique exercitationem at animi quasi nemo inventore sint ex iure eius iusto, reiciendis consequuntur!</p>
            </div>
            <div class="features-container">
                <h3>Feature</h3>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloribus alias tempora est incidunt, excepturi nobis, harum similique exercitationem at animi quasi nemo inventore sint ex iure eius iusto, reiciendis consequuntur!</p>
            </div>
        </div>
    </section>

    <!-- Booking section -->
    <section id="booking">
        <h2>Book your room</h2>
        <form method="POST" action="booking.php">
            <label for="visitor_name">Your Name:</label>
            <input type="text" name="visitor_name" required>

            <label for="arrival_date">Arrival Date:</label>
            <input type="date" name="arrival_date" min="2025-01-01" max="2025-01-31" required>

            <label for="departure_date">Departure Date:</label>
            <input type="date" name="departure_date" min="2025-01-01" max="2025-01-31" required>

            <label for="room_id">Room:</label>
            <select name="room_id" required>
                <option value="1">Budget Room</option>
                <option value="2">Standard Room</option>
                <option value="3">Luxury Room</option>
            </select>

            <label for="transfer_code">Transfer Code:</label>
            <input type="text" name="transfer_code">
            <!-- Add required for transfer code later!! -->
            <button type="submit">Book Now</button>
        </form>



        </form>
    </section>
</main>


<?php
require __DIR__ . '/includes/footer.php';
?>