<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Complaint Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .header { background: #333; color: white; padding: 10px; margin-bottom: 20px; }
        .header a { color: white; text-decoration: none; margin-right: 15px; }
        .header a:hover { text-decoration: underline; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { 
            width: 100%; padding: 8px; border: 1px solid #ddd; box-sizing: border-box; 
        }
        .btn { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
        .btn:hover { background: #005a87; }
        .btn-secondary { background: #666; }
        .btn-secondary:hover { background: #444; }
        .error { color: red; background: #ffe6e6; padding: 10px; border: 1px solid red; margin-bottom: 15px; }
        .success { color: green; background: #e6ffe6; padding: 10px; border: 1px solid green; margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background: #f2f2f2; }
        .status-pending { color: orange; }
        .status-resolved { color: green; }
        .status-rejected { color: red; }
        .priority-high { color: red; font-weight: bold; }
        .priority-urgent { color: darkred; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <strong>Complaint Management System</strong>
        <?php if (isLoggedIn()): ?>
            | <a href="dashboard.php">Dashboard</a>
            | <a href="submit_complaint.php">New Complaint</a>
            | <a href="my_complaints.php">My Complaints</a>
            <?php if (isAdmin()): ?>
                | <a href="admin/complaints.php">Admin</a>
            <?php endif; ?>
            | <a href="profile.php">Profile</a>
            | <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['full_name']); ?>)</a>
        <?php else: ?>
            | <a href="login.php">Login</a>
            | <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
    <div class="container">