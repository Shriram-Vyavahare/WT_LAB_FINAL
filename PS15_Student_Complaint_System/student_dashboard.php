<?php
session_start();
require_once 'config/database.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

// Get student's complaints
$stmt = $conn->prepare("
    SELECT c.*, s.name as student_name, s.student_id 
    FROM complaints c 
    JOIN students s ON c.student_id = s.id 
    WHERE c.student_id = ? 
    ORDER BY c.created_at DESC
");
$stmt->execute([$_SESSION['student_id']]);
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Complaint Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Student Dashboard</h1>
            <div class="nav">
                <span style="color: #4a5568;">Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?></span>
                <a href="submit_complaint.php">Submit Complaint</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>My Complaints</h2>
                <a href="submit_complaint.php" class="btn btn-primary">Submit New Complaint</a>
            </div>
            
            <?php if (empty($complaints)): ?>
                <div style="text-align: center; padding: 2rem; color: #718096;">
                    <p>You haven't submitted any complaints yet.</p>
                    <a href="submit_complaint.php" class="btn btn-primary" style="margin-top: 1rem;">Submit Your First Complaint</a>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($complaints as $complaint): ?>
                                <tr>
                                    <td>#<?php echo $complaint['id']; ?></td>
                                    <td><?php echo htmlspecialchars($complaint['title']); ?></td>
                                    <td><?php echo htmlspecialchars($complaint['category']); ?></td>
                                    <td>
                                        <span class="priority-<?php echo strtolower($complaint['priority']); ?>">
                                            <?php echo $complaint['priority']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $complaint['status'])); ?>">
                                            <?php echo $complaint['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($complaint['created_at'])); ?></td>
                                    <td>
                                        <a href="view_complaint.php?id=<?php echo $complaint['id']; ?>" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>