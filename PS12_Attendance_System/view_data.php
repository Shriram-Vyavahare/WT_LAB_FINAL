<?php
include 'config/database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Data</title>
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
        h3 { color: #333; margin-top: 30px; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Database Data View</h2>
    
    <h3>📚 Students Data</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Roll No</th>
            <th>Name</th>
        </tr>
        <?php
        $students_sql = "SELECT * FROM students ORDER BY roll_no";
        $students_result = mysqli_query($conn, $students_sql);
        while ($row = mysqli_fetch_assoc($students_result)):
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['roll_no']; ?></td>
            <td><?php echo $row['name']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <h3>📋 Attendance Data</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Roll No</th>
            <th>Name</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php
        $attendance_sql = "SELECT * FROM attendance ORDER BY date DESC, roll_no";
        $attendance_result = mysqli_query($conn, $attendance_sql);
        while ($row = mysqli_fetch_assoc($attendance_result)):
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['roll_no']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td style="color: <?php echo $row['status'] == 'present' ? 'green' : 'red'; ?>;">
                <?php echo $row['status']; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="index.php">← Back to Home</a>
    </p>
</body>
</html>