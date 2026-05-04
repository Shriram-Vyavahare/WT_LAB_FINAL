<?php
session_start();
require_once 'config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit();
}

$complaint_id = $_GET['id'];
$success = '';
$error = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $new_status = trim($_POST['status']);
    
    if (!empty($new_status)) {
        $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
        if ($stmt->execute([$new_status, $complaint_id])) {
            $success = 'Complaint status updated successfully!';
        } else {
            $error = 'Failed to update status. Please try again.';
        }
    }
}

// Get complaint details
$stmt = $conn->prepare("
    SELECT c.*, s.name as student_name, s.student_id, s.department, s.year, s.email
    FROM complaints c 
    JOIN students s ON c.student_id = s.id 
    WHERE c.id = ?
");
$stmt->execute([$complaint_id]);
$complaint = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$complaint) {
    header('Location: admin_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaint - Admin Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Complaint Management</h1>
            <div class="nav">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card" style="max-width: 900px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Complaint #<?php echo $complaint['id']; ?></h2>
                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $complaint['status'])); ?>">
                    <?php echo $complaint['status']; ?>
                </span>
            </div>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
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
                    <p><strong>Current Status:</strong> 
                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $complaint['status'])); ?>">
                            <?php echo $complaint['status']; ?>
                        </span>
                    </p>
                    <p><strong>Submitted:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['created_at'])); ?></p>
                    <p><strong>Last Updated:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['updated_at'])); ?></p>
                </div>
                
                <div>
                    <h3 style="color: #4a5568; margin-bottom: 1rem;">Student Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($complaint['student_name']); ?></p>
                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($complaint['student_id']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($complaint['email']); ?></p>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($complaint['department']); ?></p>
                    <p><strong>Year:</strong> <?php echo $complaint['year']; ?></p>
                </div>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <h3 style="color: #4a5568; margin-bottom: 1rem;">Description</h3>
                <div style="background: #f7fafc; padding: 1.5rem; border-radius: 5px; border-left: 4px solid #667eea;">
                    <?php echo nl2br(htmlspecialchars($complaint['description'])); ?>
                </div>
            </div>
            
            <!-- Status Update Form -->
            <div style="background: #f7fafc; padding: 1.5rem; border-radius: 5px; margin-bottom: 2rem;">
                <h3 style="color: #4a5568; margin-bottom: 1rem;">Update Status</h3>
                <form method="POST" action="" style="display: flex; gap: 1rem; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <label for="status">New Status:</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="Pending" <?php echo ($complaint['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="In Progress" <?php echo ($complaint['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Resolved" <?php echo ($complaint['status'] == 'Resolved') ? 'selected' : ''; ?>>Resolved</option>
                            <option value="Closed" <?php echo ($complaint['status'] == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-success">Update Status</button>
                </form>
            </div>
            
            <div style="text-align: center;">
                <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>