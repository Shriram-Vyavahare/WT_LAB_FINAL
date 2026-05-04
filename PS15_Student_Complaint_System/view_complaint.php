<?php
session_start();
require_once 'config/database.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: student_dashboard.php');
    exit();
}

$complaint_id = $_GET['id'];

// Get complaint details - ensure it belongs to the logged-in student
$stmt = $conn->prepare("
    SELECT c.*, s.name as student_name, s.student_id, s.department, s.year
    FROM complaints c 
    JOIN students s ON c.student_id = s.id 
    WHERE c.id = ? AND c.student_id = ?
");
$stmt->execute([$complaint_id, $_SESSION['student_id']]);
$complaint = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$complaint) {
    header('Location: student_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaint - Student Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Complaint Details</h1>
            <div class="nav">
                <a href="student_dashboard.php">Dashboard</a>
                <a href="submit_complaint.php">Submit New</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Complaint #<?php echo $complaint['id']; ?></h2>
                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $complaint['status'])); ?>">
                    <?php echo $complaint['status']; ?>
                </span>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="color: #4a5568; margin-bottom: 1rem;">Complaint Information</h3>
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($complaint['title']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($complaint['category']); ?></p>
                    <p><strong>Priority:</strong> 
                        <span class="priority-<?php echo strtolower($complaint['priority']); ?>">
                            <?php echo $complaint['priority']; ?>
                        </span>
                    </p>
                    <p><strong>Submitted:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['created_at'])); ?></p>
                    <p><strong>Last Updated:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['updated_at'])); ?></p>
                </div>
                
                <div>
                    <h3 style="color: #4a5568; margin-bottom: 1rem;">Student Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($complaint['student_name']); ?></p>
                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($complaint['student_id']); ?></p>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($complaint['department']); ?></p>
                    <p><strong>Year:</strong> <?php echo $complaint['year']; ?></p>
                </div>
            </div>
            
            <div>
                <h3 style="color: #4a5568; margin-bottom: 1rem;">Description</h3>
                <div style="background: #f7fafc; padding: 1.5rem; border-radius: 5px; border-left: 4px solid #667eea;">
                    <?php echo nl2br(htmlspecialchars($complaint['description'])); ?>
                </div>
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <a href="student_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>