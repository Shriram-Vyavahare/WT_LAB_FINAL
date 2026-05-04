# Airplane Seat Booking System

A simple and professional PHP-based airplane seat booking system with MySQL database.

## Features

- **Visual Seating Chart**: Interactive airplane seating layout with cockpit
- **Real-time Booking**: Click to select and book available seats
- **Professional UI**: Modern, responsive design with smooth animations
- **Seat Management**: Book and cancel seat reservations
- **Passenger Information**: Store passenger name and email
- **Visual Indicators**: Color-coded seats (Available/Booked/Selected)
- **Mobile Responsive**: Works on desktop and mobile devices

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher (running on port 3307)
- Web server (Apache/Nginx)

## Installation

1. **Database Setup**:
   ```bash
   # Import the database schema
   mysql -u root -p -P 3307 < database.sql
   ```

2. **Configuration**:
   - Update database credentials in `config.php` if needed
   - Default settings use MySQL on port 3307

3. **Web Server**:
   - Place files in your web server directory
   - Access via `http://localhost/airplane-booking/`

## File Structure

```
airplane-booking/
├── index.php          # Main application file
├── config.php         # Database configuration and functions
├── database.sql       # Database schema and sample data
└── README.md          # This file
```

## Database Schema

### Tables

1. **airplanes**
   - `id`: Primary key
   - `name`: Airplane model name
   - `total_rows`: Number of seat rows
   - `seats_per_row`: Seats per row (6 for A-F)

2. **seats**
   - `id`: Primary key
   - `airplane_id`: Foreign key to airplanes
   - `seat_number`: Seat identifier (e.g., "1A", "15F")
   - `row_number`: Row number
   - `seat_letter`: Seat letter (A-F)
   - `is_booked`: Booking status
   - `passenger_name`: Passenger name (if booked)
   - `passenger_email`: Passenger email (if booked)
   - `booking_time`: Booking timestamp

## Usage

1. **View Seating Chart**: The main page displays the airplane seating layout
2. **Select Seat**: Click on any green (available) seat
3. **Book Seat**: Enter passenger details and click "Book Seat"
4. **View Booking**: Click on red (booked) seats to view booking details
5. **Cancel Booking**: Use the cancel button in booking details

## Seating Layout

- **30 rows** with **6 seats per row** (A, B, C | D, E, F)
- **Aisle** between seats C and D
- **Color coding**:
  - 🟢 Green: Available seats
  - 🔴 Red: Booked seats
  - 🔵 Blue: Currently selected seat

## Customization

### Adding More Airplanes

```sql
INSERT INTO airplanes (name, total_rows, seats_per_row) VALUES 
('Airbus A320', 28, 6);
```

### Changing Seat Configuration

Modify the seat generation query in `database.sql` to change the number of rows or seats per row.

### Styling

All CSS is included in `index.php`. Modify the `<style>` section to customize the appearance.

## Security Features

- **SQL Injection Protection**: Uses prepared statements
- **Input Validation**: Server-side validation for all inputs
- **XSS Protection**: HTML escaping for all output

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## License

This project is open source and available under the MIT License.