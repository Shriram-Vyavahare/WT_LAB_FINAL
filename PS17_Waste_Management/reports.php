<?php
require_once 'config.php';

// Get all reports
try {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT * FROM waste_reports ORDER BY created_at DESC");
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error fetching reports: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Reports - Waste Collection System</title>
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
        
        .no-reports {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Waste Reports</h1>
            <p>View all submitted waste collection reports</p>
        </div>
        
        <div class="nav">
            <a href="index.php">Report Waste</a>
            <a href="reports.php" class="active">View Reports</a>
            <a href="admin.php">Admin Panel</a>
        </div>
        
        <div class="content">
            <h2>All Waste Reports</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php elseif (empty($reports)): ?>
                <div class="no-reports">
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
                                <th>Waste Type</th>
                                <th>Priority</th>
                                <th>Reporter</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td>#<?php echo $report['id']; ?></td>
                                    <td><?php echo htmlspecialchars($report['location']); ?></td>
                                    <td><span class="waste-type"><?php echo htmlspecialchars($report['waste_type']); ?></span></td>
                                    <td><span class="priority priority-<?php echo $report['priority']; ?>"><?php echo ucfirst($report['priority']); ?></span></td>
                                    <td><?php echo htmlspecialchars($report['reporter_name']); ?></td>
                                    <td><?php echo htmlspecialchars($report['reporter_phone']); ?></td>
                                    <td><span class="status status-<?php echo $report['status']; ?>"><?php echo ucfirst($report['status']); ?></span></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($report['created_at'])); ?></td>
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