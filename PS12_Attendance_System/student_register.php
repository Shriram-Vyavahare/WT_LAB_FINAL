<?php
include 'config/database.php';

$message = '';

if ($_POST) {
    $roll_no = $_POST['roll_no'];
    $name = $_POST['name'];
    
    if ($roll_no && $name) {
        $sql = "INSERT INTO students (roll_no, name) VALUES ('$roll_no', '$name')";
        if (mysqli_query($conn, $sql)) {
            $message = "Student registered successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        .form { max-width: 400px; margin: 0 auto; }
        input, button { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
        }
        button { 
            background: #28a745; 
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        .message { 
            color: green; 
            text-align: center; 
            margin: 20px 0; 
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Student Registration</h2>
    
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>
    
    <div class="form">
        <form method="POST">
            <input type="text" name="roll_no" placeholder="Roll Number" required>
            <input type="text" name="name" placeholder="Student Name" required>
            <button type="submit">Register</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php">← Back to Home</a>
        </p>
    </div>
</body>
</html>