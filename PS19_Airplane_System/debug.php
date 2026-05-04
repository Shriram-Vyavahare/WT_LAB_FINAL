<?php
require_once 'config.php';

echo "<h2>Debug Information</h2>";

if ($_POST) {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    $action = $_POST['action'] ?? 'none';
    echo "<p><strong>Action detected:</strong> " . htmlspecialchars($action) . "</p>";
    
    if ($action === 'book') {
        echo "<p style='color: green;'>✅ BOOKING ACTION DETECTED</p>";
        
        $seatId = $_POST['seat_id'] ?? '';
        $passengerName = $_POST['passenger_name'] ?? '';
        $passengerEmail = $_POST['passenger_email'] ?? '';
        
        echo "<p>Seat ID: " . htmlspecialchars($seatId) . "</p>";
        echo "<p>Passenger Name: " . htmlspecialchars($passengerName) . "</p>";
        echo "<p>Passenger Email: " . htmlspecialchars($passengerEmail) . "</p>";
        
        if ($seatId && $passengerName && $passengerEmail) {
            $success = bookSeat($seatId, $passengerName, $passengerEmail);
            if ($success) {
                echo "<p style='color: green; font-weight: bold;'>✅ SEAT BOOKED SUCCESSFULLY!</p>";
            } else {
                echo "<p style='color: red; font-weight: bold;'>❌ BOOKING FAILED!</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Missing required fields</p>";
        }
    } elseif ($action === 'cancel') {
        echo "<p style='color: orange;'>⚠️ CANCEL ACTION DETECTED</p>";
    } else {
        echo "<p style='color: red;'>❌ NO VALID ACTION DETECTED</p>";
    }
} else {
    echo "<p>No POST data received yet.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Booking</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { background: #f5f5f5; padding: 20px; border-radius: 5px; margin: 20px 0; }
        input, button { margin: 5px 0; padding: 10px; }
        button { background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Debug Booking Form</h1>
    
    <div class="form-container">
        <h3>Test Booking Form</h3>
        <form method="POST">
            <input type="hidden" name="action" value="book">
            <input type="hidden" name="seat_id" value="1">
            
            <p>Passenger Name: <input type="text" name="passenger_name" value="Test User" required></p>
            <p>Email: <input type="email" name="passenger_email" value="test@example.com" required></p>
            
            <button type="submit">Book Seat (ID: 1)</button>
        </form>
    </div>
    
    <div class="form-container">
        <h3>Test Cancel Form</h3>
        <form method="POST">
            <input type="hidden" name="action" value="cancel">
            <input type="hidden" name="seat_id" value="1">
            
            <button type="submit">Cancel Booking (ID: 1)</button>
        </form>
    </div>
    
    <p><a href="index.php">← Back to Main Application</a></p>
</body>
</html>