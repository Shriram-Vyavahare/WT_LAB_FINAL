<?php
require_once 'config.php';

$message = '';
$messageType = '';

// Handle booking request
if (isset($_POST['action']) && $_POST['action'] === 'book') {
    $seatId = $_POST['seat_id'] ?? '';
    $passengerName = $_POST['passenger_name'] ?? '';
    $passengerEmail = $_POST['passenger_email'] ?? '';
    
    if ($seatId && $passengerName && $passengerEmail) {
        $success = bookSeat($seatId, $passengerName, $passengerEmail);
        if ($success) {
            $message = "✅ Seat booked successfully! Passenger: " . htmlspecialchars($passengerName);
            $messageType = "success";
        } else {
            $message = "❌ Failed to book seat. It may already be taken by another passenger.";
            $messageType = "error";
        }
    } else {
        $message = "❌ Please fill in all required fields (Seat, Name, and Email).";
        $messageType = "error";
    }
}
// Handle cancellation request  
elseif (isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $seatId = $_POST['seat_id'] ?? '';
    
    if ($seatId) {
        $success = cancelBooking($seatId);
        if ($success) {
            $message = "✅ Booking cancelled successfully! Seat is now available for booking.";
            $messageType = "success";
        } else {
            $message = "❌ Failed to cancel booking. Please try again.";
            $messageType = "error";
        }
    } else {
        $message = "❌ Invalid seat selection for cancellation.";
        $messageType = "error";
    }
}

$airplane = getAirplaneInfo();
$seats = getSeats();

// Group seats by row
$seatsByRow = [];
foreach ($seats as $seat) {
    $seatsByRow[$seat['row_number']][] = $seat;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airplane Seat Booking - <?php echo htmlspecialchars($airplane['name']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .main-content {
            display: flex;
            min-height: 600px;
        }

        .seating-chart {
            flex: 2;
            padding: 30px;
            background: #f8f9fa;
        }

        .booking-panel {
            flex: 1;
            padding: 30px;
            background: white;
            border-left: 1px solid #e9ecef;
        }

        .airplane {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .cockpit {
            background: #34495e;
            height: 40px;
            border-radius: 20px 20px 0 0;
            margin-bottom: 20px;
            position: relative;
        }

        .cockpit::after {
            content: "✈️ COCKPIT";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .row {
            display: flex;
            justify-content: center;
            margin-bottom: 8px;
            align-items: center;
        }

        .row-number {
            width: 30px;
            text-align: center;
            font-weight: bold;
            color: #666;
            font-size: 14px;
        }

        .seats {
            display: flex;
            gap: 5px;
        }

        .aisle {
            width: 30px;
        }

        .seat {
            width: 35px;
            height: 35px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
            position: relative;
        }

        .seat.available {
            background: #e8f5e8;
            border-color: #28a745;
            color: #28a745;
        }

        .seat.available:hover {
            background: #28a745;
            color: white;
            transform: scale(1.1);
        }

        .seat.booked {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
            cursor: pointer;
        }

        .seat.booked:hover {
            background: #c82333;
            border-color: #c82333;
            transform: scale(1.05);
        }

        .seat.selected {
            background: #007bff;
            border-color: #007bff;
            color: white;
            transform: scale(1.1);
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .legend-seat {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid;
        }

        .booking-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .selected-seat-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .seat-info {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #e9ecef;
        }

        .seat-info h4 {
            color: #333;
            margin-bottom: 10px;
        }

        .seat-info p {
            margin: 5px 0;
            color: #666;
        }

        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            .booking-panel {
                border-left: none;
                border-top: 1px solid #e9ecef;
            }
            
            .seat {
                width: 30px;
                height: 30px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo htmlspecialchars($airplane['name']); ?></h1>
            <p>Select your preferred seat</p>
        </div>

        <div class="main-content">
            <div class="seating-chart">
                <div class="airplane">
                    <div class="cockpit"></div>
                    
                    <?php foreach ($seatsByRow as $rowNumber => $rowSeats): ?>
                        <div class="row">
                            <div class="row-number"><?php echo $rowNumber; ?></div>
                            <div class="seats">
                                <?php 
                                $seatCount = 0;
                                foreach ($rowSeats as $seat): 
                                    $seatCount++;
                                ?>
                                    <div class="seat <?php echo $seat['is_booked'] ? 'booked' : 'available'; ?>" 
                                         data-seat-id="<?php echo $seat['id']; ?>"
                                         data-seat-number="<?php echo htmlspecialchars($seat['seat_number']); ?>"
                                         data-passenger-name="<?php echo htmlspecialchars($seat['passenger_name'] ?? ''); ?>"
                                         data-passenger-email="<?php echo htmlspecialchars($seat['passenger_email'] ?? ''); ?>"
                                         data-booking-time="<?php echo htmlspecialchars($seat['booking_time'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($seat['seat_letter']); ?>
                                    </div>
                                    <?php if ($seatCount == 3): ?>
                                        <div class="aisle"></div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-seat available" style="background: #e8f5e8; border-color: #28a745;"></div>
                        <span>Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-seat booked" style="background: #dc3545; border-color: #dc3545; color: white;"></div>
                        <span>Booked</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-seat selected" style="background: #007bff; border-color: #007bff;"></div>
                        <span>Selected</span>
                    </div>
                </div>
            </div>

            <div class="booking-panel">
                <?php if (isset($message)): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div id="booking-form" style="display: none;">
                    <div class="selected-seat-info">
                        <h3>Selected Seat: <span id="selected-seat-number"></span></h3>
                        <p>Please enter passenger details</p>
                    </div>

                    <form method="POST" class="booking-form">
                        <input type="hidden" name="action" value="book">
                        <input type="hidden" name="seat_id" id="selected-seat-id">
                        
                        <div class="form-group">
                            <label for="passenger_name">Passenger Name</label>
                            <input type="text" id="passenger_name" name="passenger_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="passenger_email">Email Address</label>
                            <input type="email" id="passenger_email" name="passenger_email" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Book Seat</button>
                        <button type="button" class="btn" onclick="clearSelection()" style="background: #6c757d; color: white;">Cancel</button>
                    </form>
                </div>

                <div id="seat-info" style="display: none;">
                    <div class="seat-info">
                        <h4>Seat Information</h4>
                        <p><strong>Seat:</strong> <span id="info-seat-number"></span></p>
                        <p><strong>Passenger:</strong> <span id="info-passenger-name"></span></p>
                        <p><strong>Email:</strong> <span id="info-passenger-email"></span></p>
                        <p><strong>Booked:</strong> <span id="info-booking-time"></span></p>
                        
                        <form method="POST" style="margin-top: 15px;">
                            <input type="hidden" name="action" value="cancel">
                            <input type="hidden" name="seat_id" id="cancel-seat-id">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</button>
                        </form>
                    </div>
                </div>

                <div id="default-info">
                    <div class="seat-info">
                        <h4>How to Book</h4>
                        <p>1. Click on an available (green) seat</p>
                        <p>2. Enter passenger details</p>
                        <p>3. Click "Book Seat" to confirm</p>
                        <br>
                        <p><strong>Legend:</strong></p>
                        <p>🟢 Available seats</p>
                        <p>🔴 Booked seats</p>
                        <p>🔵 Selected seat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedSeat = null;

        document.addEventListener('DOMContentLoaded', function() {
            const seats = document.querySelectorAll('.seat');
            
            seats.forEach(seat => {
                seat.addEventListener('click', function() {
                    if (this.classList.contains('booked')) {
                        showSeatInfo(this);
                    } else if (this.classList.contains('available')) {
                        selectSeat(this);
                    }
                });
            });
        });

        function selectSeat(seatElement) {
            // Clear previous selection
            clearSelection();
            
            // Select new seat
            selectedSeat = seatElement;
            seatElement.classList.add('selected');
            seatElement.classList.remove('available');
            
            // Show booking form
            document.getElementById('booking-form').style.display = 'block';
            document.getElementById('seat-info').style.display = 'none';
            document.getElementById('default-info').style.display = 'none';
            
            // Fill form data
            document.getElementById('selected-seat-id').value = seatElement.dataset.seatId;
            document.getElementById('selected-seat-number').textContent = seatElement.dataset.seatNumber;
        }

        function showSeatInfo(seatElement) {
            clearSelection();
            
            // Show seat info
            document.getElementById('seat-info').style.display = 'block';
            document.getElementById('booking-form').style.display = 'none';
            document.getElementById('default-info').style.display = 'none';
            
            // Fill seat info
            document.getElementById('info-seat-number').textContent = seatElement.dataset.seatNumber;
            document.getElementById('info-passenger-name').textContent = seatElement.dataset.passengerName;
            document.getElementById('info-passenger-email').textContent = seatElement.dataset.passengerEmail;
            document.getElementById('info-booking-time').textContent = new Date(seatElement.dataset.bookingTime).toLocaleString();
            document.getElementById('cancel-seat-id').value = seatElement.dataset.seatId;
        }

        function clearSelection() {
            if (selectedSeat) {
                selectedSeat.classList.remove('selected');
                selectedSeat.classList.add('available');
                selectedSeat = null;
            }
            
            // Hide forms and show default info
            document.getElementById('booking-form').style.display = 'none';
            document.getElementById('seat-info').style.display = 'none';
            document.getElementById('default-info').style.display = 'block';
            
            // Clear form
            document.getElementById('passenger_name').value = '';
            document.getElementById('passenger_email').value = '';
        }
    </script>
</body>
</html>