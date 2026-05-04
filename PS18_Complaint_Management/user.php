<!DOCTYPE html>
<html>
<head>
    <title>User Panel - Complaint Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        .header { background: #007cba; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 4px; }
        .header a:hover { background: rgba(255,255,255,0.3); }
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="text"], select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 100px; resize: vertical; }
        .btn { padding: 12px 25px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #005a87; }
        .success { color: green; background: #e6ffe6; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .error { color: red; background: #ffe6e6; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .complaints-list { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 30px; }
        .complaint-item { border-bottom: 1px solid #eee; padding: 15px 0; }
        .complaint-item:last-child { border-bottom: none; }
        .complaint-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .complaint-subject { font-weight: bold; color: #333; }
        .complaint-status { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-resolved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .complaint-org { color: #666; font-size: 14px; }
        .complaint-desc { color: #777; margin-top: 5px; }
        .complaint-date { color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <?php
    require_once 'config.php';
    
    if (!isLoggedIn() || isAdmin()) {
        redirect('index.php');
    }
    
    $success = '';
    $error = '';
    
    // Handle complaint submission
    if ($_POST && isset($_POST['submit_complaint'])) {
        $organization_id = $_POST['organization_id'];
        $subject = $_POST['subject'];
        $description = $_POST['description'];
        
        if ($organization_id && $subject && $description) {
            $stmt = $pdo->prepare("INSERT INTO complaints (user_id, organization_id, subject, description) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$_SESSION['user_id'], $organization_id, $subject, $description])) {
                $success = 'Complaint submitted successfully!';
            } else {
                $error = 'Failed to submit complaint. Please try again.';
            }
        } else {
            $error = 'Please fill all required fields.';
        }
    }
    
    // Get organizations
    $orgs = $pdo->query("SELECT * FROM organizations")->fetchAll();
    
    // Get user's complaints
    $stmt = $pdo->prepare("SELECT c.*, o.name as org_name FROM complaints c JOIN organizations o ON c.organization_id = o.id WHERE c.user_id = ? ORDER BY c.created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $complaints = $stmt->fetchAll();
    ?>
    
    <div class="header">
        <h1>User Panel</h1>
        <div>
            Welcome, <?php echo $_SESSION['full_name']; ?> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <!-- Submit Complaint Form -->
        <div class="form-container">
            <h2>Submit New Complaint</h2>
            
            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Organization *:</label>
                    <select name="organization_id" required>
                        <option value="">Select Organization</option>
                        <?php foreach ($orgs as $org): ?>
                            <option value="<?php echo $org['id']; ?>"><?php echo $org['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Subject *:</label>
                    <input type="text" name="subject" placeholder="Brief description of your complaint" required>
                </div>
                
                <div class="form-group">
                    <label>Description *:</label>
                    <textarea name="description" placeholder="Detailed description of your complaint..." required></textarea>
                </div>
                
                <button type="submit" name="submit_complaint" class="btn">Submit Complaint</button>
            </form>
        </div>
        
        <!-- My Complaints -->
        <div class="complaints-list">
            <h2>My Complaints (<?php echo count($complaints); ?>)</h2>
            
            <?php if (empty($complaints)): ?>
                <p style="color: #666; text-align: center; padding: 20px;">No complaints submitted yet.</p>
            <?php else: ?>
                <?php foreach ($complaints as $complaint): ?>
                    <div class="complaint-item">
                        <div class="complaint-header">
                            <div class="complaint-subject"><?php echo htmlspecialchars($complaint['subject']); ?></div>
                            <div class="complaint-status status-<?php echo $complaint['status']; ?>">
                                <?php echo strtoupper($complaint['status']); ?>
                            </div>
                        </div>
                        <div class="complaint-org"><?php echo htmlspecialchars($complaint['org_name']); ?></div>
                        <div class="complaint-desc"><?php echo htmlspecialchars($complaint['description']); ?></div>
                        <div class="complaint-date">Submitted: <?php echo date('M d, Y H:i', strtotime($complaint['created_at'])); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>