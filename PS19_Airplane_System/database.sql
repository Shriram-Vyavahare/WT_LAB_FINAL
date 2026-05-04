-- Airplane Seat Booking System Database
CREATE DATABASE IF NOT EXISTS airplane_booking;
USE airplane_booking;

-- Airplanes table
CREATE TABLE IF NOT EXISTS airplanes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    total_rows INT NOT NULL,
    seats_per_row INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seats table
CREATE TABLE IF NOT EXISTS seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    airplane_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    row_number INT NOT NULL,
    seat_letter CHAR(1) NOT NULL,
    is_booked BOOLEAN DEFAULT FALSE,
    passenger_name VARCHAR(100) NULL,
    passenger_email VARCHAR(100) NULL,
    booking_time TIMESTAMP NULL,
    FOREIGN KEY (airplane_id) REFERENCES airplanes(id),
    UNIQUE KEY unique_seat (airplane_id, seat_number)
);

-- Insert sample airplane
INSERT INTO airplanes (name, total_rows, seats_per_row) VALUES 
('Boeing 737', 30, 6);

-- Generate seats for the airplane (A, B, C, D, E, F)
INSERT INTO seats (airplane_id, seat_number, row_number, seat_letter) 
SELECT 
    1 as airplane_id,
    CONCAT(row_num, seat_letter) as seat_number,
    row_num,
    seat_letter
FROM 
    (SELECT 1 as row_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION 
     SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION
     SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION
     SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION
     SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION
     SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30) as row_numbers
CROSS JOIN 
    (SELECT 'A' as seat_letter UNION SELECT 'B' UNION SELECT 'C' UNION 
     SELECT 'D' UNION SELECT 'E' UNION SELECT 'F') as seat_letters
ORDER BY row_num, seat_letter;