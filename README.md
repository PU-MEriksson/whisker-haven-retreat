# Whisker Haven Retreat

## 🏝️ About the Project

**Whisker Haven Retreat** is a fictional hotel booking website built as part of a school project at YRGO. It combines luxury accommodations for humans with a sanctuary for cats.

## 🌟 Features

- **Room Booking**: Choose between Budget, Standard, and Luxury rooms
- **Add-ons**: Enhance your stay with add-ons like Rubik's cubes or Siberian cats for allergic guests
- **Calendar Availability**: View room availability for January 2025
- **Dynamic Pricing**: Total cost calculation includes room price, add-ons, and discounts

## 🔧 Tech Stack

- **Frontend**: HTML, CSS
- **Backend**: PHP
- **Database**: SQLite
- **Additional Tools**:
  - TablePlus
  - Composer
  - php-calendar
  - Guzzle

## 📂 Project Structure

````plaintext
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
├── index.php            # Main entry point
├── LICENSE              # Project license
├── README.md            # Project documentation

## 🗂️ Database Schema

Here’s an overview of the database structure:

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
   git clone https://github.com/yourusername/whisker-haven-retreat.git
   cd whisker-haven-retreat

2. Install dependencies using Composer:
   ```bash
   composer install

3. Start the PHP development server:
    ```bash
   php -S localhost:8000

4. Open http://localhost:8000 in your browser.

## 📜 License

This project is licensed under the MIT License.
````
