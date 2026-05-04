<?php
include 'config/database.php';

$message = '';
$today = date('Y-m-d');

// Handle form submission
if ($_POST && isset($_POST['attendance'])) {
    foreach ($_POST['attendance'] as $roll_no => $status) {
        $name = $_POST['names'][$roll_no];
        
        // Delete existing record for today
        $delete_sql = "DELETE FROM attendance WHERE roll_no = '$roll_no' AND date = '$today'";
        mysqli_query($conn, $delete_sql);
        
        // Insert new record
        $sql = "INSERT INTO attendance (roll_no, name, date, status) VALUES ('$roll_no', '$name', '$today', '$status')";
        mysqli_query($conn, $sql);
    }
    $message = "Attendance saved successfully!";
}

// Get all students
$students_sql = "SELECT * FROM students ORDER BY roll_no";
$students_result = mysqli_query($conn, $students_sql);

// Get today's attendance
$attendance_sql = "SELECT * FROM attendance WHERE date = '$today'";
$attendance_result = mysqli_query($conn, $attendance_sql);
$attendance_data = [];
while ($row = mysqli_fetch_assoc($attendance_result)) {
    $attendance_data[$row['roll_no']] = $row['status'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Take Attendance</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 10px; 
            text-align: left; 
        }
        th { background: #f8f9fa; }
        .checkbox { text-align: center; }
        button { 
            background: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
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
    <h2 style="text-align: center;">Take Attendance - <?php echo date('d-m-Y'); ?></h2>
    
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>
    
    <form method="POST">
        <table>
            <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>Present</th>
            </tr>
            
            <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
            <tr>
                <td><?php echo $student['roll_no']; ?></td>
                <td><?php echo $student['name']; ?></td>
                <td class="checkbox">
                    <input type="checkbox" 
                           name="attendance[<?php echo $student['roll_no']; ?>]" 
                           value="present"
                           <?php echo (isset($attendance_data[$student['roll_no']]) && $attendance_data[$student['roll_no']] == 'present') ? 'checked' : ''; ?>>
                    <input type="hidden" name="names[<?php echo $student['roll_no']; ?>]" value="<?php echo $student['name']; ?>">
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <div style="text-align: center;">
            <button type="submit">Save Attendance</button>
        </div>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="index.php">← Back to Home</a>
    </p>
</body>
</html>