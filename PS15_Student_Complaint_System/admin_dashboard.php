<?php
session_start();
require_once 'config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get all complaints with student information
$stmt = $conn->prepare("
    SELECT c.*, s.name as student_name, s.student_id, s.department, s.year, s.email
    FROM complaints c 
    JOIN students s ON c.student_id = s.id 
    ORDER BY c.created_at DESC
");
$stmt->execute();
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats_stmt = $conn->prepare("
    SELECT 
        COUNT(*) as total_complaints,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved,
        SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) as closed
    FROM complaints
");
$stats_stmt->execute();
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Complaint Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <div class="nav">
                <span style="color: #4a5568;">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div class="card" style="text-align: center; padding: 1.5rem;">
                <h3 style="color: #667eea; font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $stats['total_complaints']; ?></h3>
                <p style="color: #4a5568;">Total Complaints</p>
            </div>
            <div class="card" style="text-align: center; padding: 1.5rem;">
                <h3 style="color: #d69e2e; font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $stats['pending']; ?></h3>
                <p style="color: #4a5568;">Pending</p>
            </div>
            <div class="card" style="text-align: center; padding: 1.5rem;">
                <h3 style="color: #3182ce; font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $stats['in_progress']; ?></h3>
                <p style="color: #4a5568;">In Progress</p>
            </div>
            <div class="card" style="text-align: center; padding: 1.5rem;">
                <h3 style="color: #38a169; font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $stats['resolved']; ?></h3>
                <p style="color: #4a5568;">Resolved</p>
            </div>
        </div>

        <!-- Complaints Table -->
        <div class="card">
            <h2 style="margin-bottom: 2rem;">All Complaints</h2>
            
            <?php if (empty($complaints)): ?>
                <div style="text-align: center; padding: 2rem; color: #718096;">
                    <p>No complaints have been submitted yet.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($complaints as $complaint): ?>
                                <tr>
                                    <td>#<?php echo $complaint['id']; ?></td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($complaint['student_name']); ?></strong><br>
                                            <small style="color: #718096;"><?php echo htmlspecialchars($complaint['student_id']); ?></small>
                                        </div>
                                    </td>
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
                                        <a href="admin_view_complaint.php?id=<?php echo $complaint['id']; ?>" 
                                           class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">
                                           View & Update
                                        </a>
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