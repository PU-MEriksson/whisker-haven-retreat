# Whisker Haven Retreat

## 🏝️ About the Project

**Whisker Haven Retreat** is a fictional hotel booking website built as part of a school project at YRGO. It combines luxury accommodations for humans with a sanctuary for cats.

## 🌟 Features

- **Room Management**:

  - Three distinct room types: Budget, Standard, and Luxury
  - Visual calendar display for each room type
  - Detailed room descriptions and amenities

- **Booking System**:

  - Secure booking validation and processing
  - Date range selection for January 2025
  - Conflict checking for room availability
  - JSON booking confirmation responses
  - Transfer code validation with Central Bank
  - Automated fund deposits for confirmed bookings

- **Pricing Features**:

  - Dynamic pricing for different room types
  - Add-on cost calculation
  - Total cost computation including all features
  - Add-on management system with flexible pricing

- **User Interface**:

  - Interactive booking form
  - Real-time availability calendars
  - Responsive hero section with video background
  - Visual room comparison tool
  - Rich media integration (images and video)

- **Security Features**:

  - Input sanitization
  - Environment variable protection
  - Transfer code validation
  - PDO database security

- **Integration**:
  - Yrgopelago Central Bank API integration
  - Custom calendar integration using php-calendar
  - Guzzle HTTP client for API communication
  - Environmental configuration with PHP dotenv

## 🔧 Tech Stack

- **Frontend**:
  - HTML
  - CSS
  - JavaScript (Interactive features and dynamic price calculations)
- **Backend**:
  - PHP
  - SQLite
- **Additional Tools**:
  - TablePlus (Database management)
  - Composer (PHP package manager)
  - php-calendar (Calendar visualization)
  - Guzzle (HTTP client for API requests)

## 📂 Project Structure

```plaintext
├── database/
│   ├── hotel.db         # SQLite database
├── includes/
│   ├── functions.php    # Reusable PHP functions
│   ├── header.php       # HTML header
│   ├── footer.php       # HTML footer
├── assets/
│   ├── images/          # Images used in the project
│   ├── js/              # JavaScript files
├── booking.php          # Handles booking logic
├── index.php           # Main entry point
├── LICENSE             # Project license
├── README.md           # Project documentation
```

## 🗂️ Database Schema

Here's an overview of the database structure:

### `bookings`

| Column Name      | Type    | Description                 |
| ---------------- | ------- | --------------------------- |
| `id`             | INTEGER | Primary key                 |
| `visitor_name`   | TEXT    | Name of the visitor         |
| `arrival_date`   | TEXT    | Arrival date (YYYY-MM-DD)   |
| `departure_date` | TEXT    | Departure date (YYYY-MM-DD) |
| `room_id`        | INTEGER | Room ID (1-3)               |
| `transfer_code`  | TEXT    | Code for payment            |
| `cost`           | REAL    | Total cost of booking       |

### `rooms`

| Column Name | Type    | Description              |
| ----------- | ------- | ------------------------ |
| `id`        | INTEGER | Room ID (Primary key)    |
| `type`      | TEXT    | Room type (e.g., Budget) |
| `price`     | REAL    | Room price per night     |

### `addons`

| Column Name | Type    | Description             |
| ----------- | ------- | ----------------------- |
| `id`        | INTEGER | Add-on ID (Primary key) |
| `name`      | TEXT    | Add-on name             |
| `price`     | REAL    | Add-on price            |

## 💻 How to Run Locally

1. Clone this repository:

   ```bash
   git clone https://github.com/PU-MEriksson/whisker-haven-retreat.git
   cd whisker-haven-retreat
   ```

2. Install dependencies using Composer:

   ```bash
   composer install
   ```

3. Start the PHP development server:

   ```bash
   php -S localhost:8000
   ```

4. Open http://localhost:8000 in your browser.

## 📜 License

This project is licensed under the MIT License.

## Feedback

js-data.php - Ensure files are placed in the correct folders for better organization and clarity (PHP file found in JavaScript folder).

script.js: 27 - Consider moving the calculateTotalCost() function outside the event listener for better code modularity and reusability.

js-data.php, database.php - Add declare(strict_types=1); at the top of the PHP files to enforce strict type checking. This improves code reliability and prevents unexpected type coercion.

database.php: 7, 15 - Avoid using die() for error handling. Instead, use a method like http_response_code() followed by exit; for better control over HTTP responses and improved readability.

index.php - Refrain from declaring strict_types in files that mix PHP and HTML. Strict typing is typically reserved for PHP-only files for consistency and clarity.

booking.php: 16, 18 - Input is not sanitized, posing a potential security risk (e.g., SQL injection, XSS). Implement proper sanitation and validation mechanisms.

style.css - The file is excessively long. Consider splitting it into smaller, logically grouped files (e.g., layout.css, typography.css, forms.css) to improve maintainability and readability.
