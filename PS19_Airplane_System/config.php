<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_NAME', 'airplane_booking');
define('DB_USER', 'root');
define('DB_PASS', '');

// Database connection
function getConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Get all seats for an airplane
function getSeats($airplaneId = 1) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM seats WHERE airplane_id = ? ORDER BY row_number, seat_letter");
    $stmt->execute([$airplaneId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Book a seat
function bookSeat($seatId, $passengerName, $passengerEmail) {
    $pdo = getConnection();
    
    // Check if seat is already booked
    $stmt = $pdo->prepare("SELECT is_booked FROM seats WHERE id = ?");
    $stmt->execute([$seatId]);
    $seat = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($seat['is_booked']) {
        return false; // Seat already booked
    }
    
    // Book the seat
    $stmt = $pdo->prepare("UPDATE seats SET is_booked = 1, passenger_name = ?, passenger_email = ?, booking_time = NOW() WHERE id = ?");
    return $stmt->execute([$passengerName, $passengerEmail, $seatId]);
}

// Cancel booking
function cancelBooking($seatId) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("UPDATE seats SET is_booked = 0, passenger_name = NULL, passenger_email = NULL, booking_time = NULL WHERE id = ?");
    return $stmt->execute([$seatId]);
}

// Get airplane info
function getAirplaneInfo($airplaneId = 1) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM airplanes WHERE id = ?");
    $stmt->execute([$airplaneId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>