<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Complaint Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        .header { background: #dc3545; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 4px; }
        .header a:hover { background: rgba(255,255,255,0.3); }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .stats { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); flex: 1; text-align: center; }
        .stat-number { font-size: 32px; font-weight: bold; color: #007cba; }
        .stat-label { color: #666; margin-top: 5px; }
        .complaints-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 25px; }
        .complaint-item { border: 1px solid #eee; margin-bottom: 15px; border-radius: 6px; overflow: hidden; }
        .complaint-header { background: #f8f9fa; padding: 15px; border-bottom: 1px solid #eee; display: flex; justify-content: between; align-items: center; }
        .complaint-info { flex: 1; }
        .complaint-subject { font-weight: bold; color: #333; margin-bottom: 5px; }
        .complaint-meta { color: #666; font-size: 14px; }
        .complaint-actions { margin-left: 20px; }
        .complaint-body { padding: 15px; }
        .complaint-desc { color: #555; margin-bottom: 10px; }
        .status-form { display: flex; gap: 10px; align-items: center; }
        .status-select { padding: 6px 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-sm { padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .btn-sm:hover { background: #218838; }
        .status-pending { color: #856404; }
        .status-resolved { color: #155724; }
        .status-rejected { color: #721c24; }
        .success { color: green; background: #e6ffe6; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .no-complaints { text-align: center; color: #666; padding: 40px; }
    </style>
</head>
<body>
    <?php
    require_once 'config.php';
    
    if (!isLoggedIn() || !isAdmin()) {
        redirect('index.php');
    }
    
    $success = '';
    
    // Handle status update
    if ($_POST && isset($_POST['update_status'])) {
        $complaint_id = $_POST['complaint_id'];
        $new_status = $_POST['status'];
        
        $stmt = $pdo->prepare("UPDATE complaints SET status = ? WHERE id = ?");
        if ($stmt->execute([$new_status, $complaint_id])) {
            $success = 'Complaint status updated successfully!';
        }
    }
    
    // Get statistics
    $total_complaints = $pdo->query("SELECT COUNT(*) FROM complaints")->fetchColumn();
    $pending_complaints = $pdo->query("SELECT COUNT(*) FROM complaints WHERE status = 'pending'")->fetchColumn();
    $resolved_complaints = $pdo->query("SELECT COUNT(*) FROM complaints WHERE status = 'resolved'")->fetchColumn();
    
    // Get all complaints
    $stmt = $pdo->query("SELECT c.*, o.name as org_name, u.full_name as user_name 
                         FROM complaints c 
                         JOIN organizations o ON c.organization_id = o.id 
                         JOIN users u ON c.user_id = u.id 
                         ORDER BY c.created_at DESC");
    $complaints = $stmt->fetchAll();
    ?>
    
    <div class="header">
        <h1>Admin Panel</h1>
        <div>
            Welcome, <?php echo $_SESSION['full_name']; ?> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_complaints; ?></div>
                <div class="stat-label">Total Complaints</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pending_complaints; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $resolved_complaints; ?></div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>
        
        <!-- Complaints Management -->
        <div class="complaints-container">
            <h2>Manage Complaints</h2>
            
            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (empty($complaints)): ?>
                <div class="no-complaints">No complaints found.</div>
            <?php else: ?>
                <?php foreach ($complaints as $complaint): ?>
                    <div class="complaint-item">
                        <div class="complaint-header">
                            <div class="complaint-info">
                                <div class="complaint-subject"><?php echo htmlspecialchars($complaint['subject']); ?></div>
                                <div class="complaint-meta">
                                    By: <?php echo htmlspecialchars($complaint['user_name']); ?> | 
                                    Organization: <?php echo htmlspecialchars($complaint['org_name']); ?> | 
                                    Date: <?php echo date('M d, Y H:i', strtotime($complaint['created_at'])); ?> |
                                    Status: <span class="status-<?php echo $complaint['status']; ?>"><?php echo strtoupper($complaint['status']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="complaint-body">
                            <div class="complaint-desc"><?php echo htmlspecialchars($complaint['description']); ?></div>
                            
                            <form method="POST" class="status-form">
                                <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
                                <label>Update Status:</label>
                                <select name="status" class="status-select">
                                    <option value="pending" <?php echo $complaint['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="resolved" <?php echo $complaint['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    <option value="rejected" <?php echo $complaint['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                <button type="submit" name="update_status" class="btn-sm">Update</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>