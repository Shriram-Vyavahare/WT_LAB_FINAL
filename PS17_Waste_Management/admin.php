<?php
require_once 'config.php';

// Handle status update
if ($_POST && isset($_POST['update_status'])) {
    $report_id = (int)$_POST['report_id'];
    $new_status = sanitize($_POST['status']);
    $authority_id = (int)$_POST['authority_id'];
    
    try {
        $pdo = getConnection();
        
        // Update report status
        $stmt = $pdo->prepare("UPDATE waste_reports SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $report_id]);
        
        // If assigning to authority, create assignment record
        if ($new_status === 'assigned' && $authority_id > 0) {
            $stmt = $pdo->prepare("INSERT INTO assignments (report_id, authority_id, notes) VALUES (?, ?, ?)");
            $stmt->execute([$report_id, $authority_id, "Assigned for collection"]);
        }
        
        $success = "Report status updated successfully!";
    } catch(PDOException $e) {
        $error = "Error updating status: " . $e->getMessage();
    }
}

// Get all reports with authority assignments
try {
    $pdo = getConnection();
    $stmt = $pdo->query("
        SELECT wr.*, a.name as authority_name, a.phone as authority_phone 
        FROM waste_reports wr 
        LEFT JOIN assignments ass ON wr.id = ass.report_id 
        LEFT JOIN authorities a ON ass.authority_id = a.id 
        ORDER BY wr.created_at DESC
    ");
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get all authorities
    $stmt = $pdo->query("SELECT * FROM authorities ORDER BY name");
    $authorities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error fetching data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Waste Collection System</title>
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
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
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
        
        .nav {
            background: #34495e;
            padding: 0;
        }
        
        .nav a {
            display: inline-block;
            color: white;
            text-decoration: none;
            padding: 15px 25px;
            transition: background 0.3s;
        }
        
        .nav a:hover, .nav a.active {
            background: #2c3e50;
        }
        
        .content {
            padding: 40px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-assigned {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-collected {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .priority {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .priority-low {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .priority-medium {
            background: #fff3cd;
            color: #856404;
        }
        
        .priority-high {
            background: #f8d7da;
            color: #721c24;
        }
        
        .waste-type {
            background: #e7f3ff;
            color: #0066cc;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .action-form {
            display: inline-block;
        }
        
        .action-form select, .action-form button {
            padding: 6px 12px;
            margin: 2px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .action-form button {
            background: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .action-form button:hover {
            background: #2980b9;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ Admin Panel</h1>
            <p>Manage waste collection reports and assign authorities</p>
        </div>
        
        <div class="nav">
            <a href="index.php">Report Waste</a>
            <a href="reports.php">View Reports</a>
            <a href="admin.php" class="active">Admin Panel</a>
        </div>
        
        <div class="content">
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <h2>Dashboard Statistics</h2>
            
            <?php if (!empty($reports)): ?>
                <?php
                $total = count($reports);
                $pending = count(array_filter($reports, function($r) { return $r['status'] === 'pending'; }));
                $assigned = count(array_filter($reports, function($r) { return $r['status'] === 'assigned'; }));
                $completed = count(array_filter($reports, function($r) { return $r['status'] === 'completed'; }));
                ?>
                
                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total; ?></div>
                        <div class="stat-label">Total Reports</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $pending; ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $assigned; ?></div>
                        <div class="stat-label">Assigned</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $completed; ?></div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
            <?php endif; ?>
            
            <h2>Manage Reports</h2>
            
            <?php if (empty($reports)): ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <h3>No reports found</h3>
                    <p>No waste reports have been submitted yet.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Reporter</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td>#<?php echo $report['id']; ?></td>
                                    <td><?php echo htmlspecialchars($report['location']); ?></td>
                                    <td><span class="waste-type"><?php echo htmlspecialchars($report['waste_type']); ?></span></td>
                                    <td><span class="priority priority-<?php echo $report['priority']; ?>"><?php echo ucfirst($report['priority']); ?></span></td>
                                    <td>
                                        <?php echo htmlspecialchars($report['reporter_name']); ?><br>
                                        <small><?php echo htmlspecialchars($report['reporter_phone']); ?></small>
                                    </td>
                                    <td><span class="status status-<?php echo $report['status']; ?>"><?php echo ucfirst($report['status']); ?></span></td>
                                    <td>
                                        <?php if ($report['authority_name']): ?>
                                            <?php echo htmlspecialchars($report['authority_name']); ?><br>
                                            <small><?php echo htmlspecialchars($report['authority_phone']); ?></small>
                                        <?php else: ?>
                                            <em>Not assigned</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" class="action-form">
                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                            <select name="status" required>
                                                <option value="">Update Status</option>
                                                <option value="pending" <?php echo $report['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="assigned" <?php echo $report['status'] === 'assigned' ? 'selected' : ''; ?>>Assigned</option>
                                                <option value="collected" <?php echo $report['status'] === 'collected' ? 'selected' : ''; ?>>Collected</option>
                                                <option value="completed" <?php echo $report['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            </select>
                                            <select name="authority_id">
                                                <option value="">Select Authority</option>
                                                <?php foreach ($authorities as $authority): ?>
                                                    <option value="<?php echo $authority['id']; ?>"><?php echo htmlspecialchars($authority['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" name="update_status">Update</button>
                                        </form>
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