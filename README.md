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


## 🗂️ Database Schema

Overview of the database structure:

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

## 🛠️ Reflections and Areas for Improvement

This project was created as part of a school assignment, with the main focus on backend development, API integration, and maintaining a clear code structure. I'm proud of some
 
- Organized file structure and modular, well-organised code   
- Meaningful version control with consistent and descriptive commit history

At the same time, there are a few areas I would improve if I had more time:

- **Accessibility (a11y):** Color contrasts could be optimised for better readability  
- **Visual design:** Layout and color choices could be refined for a more polished and modern look  
- **Responsiveness:** This site is made for desktop only, so that it could be improved to work on smaller screens as well.  

While I’m currently focusing on other projects, I’m aware of these issues and will carry those lessons forward into future work.
