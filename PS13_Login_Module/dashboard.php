<?php
require_once 'config.php';

// Check if user is logged in via session or cookie
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['remember_user'])) {
        // Validate cookie and restore session
        $cookie_data = base64_decode($_COOKIE['remember_user']);
        list($user_id, $username) = explode(':', $cookie_data);
        
        // Verify user exists in database
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            // Invalid cookie, redirect to login
            setcookie('remember_user', '', time() - 3600, '/');
            header('Location: login.php');
            exit();
        }
    } else {
        header('Location: login.php');
        exit();
    }
}

// Get user information
$stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PHP Login System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            line-height: 1.6;
        }
        
        .header {
            background-color: #007bff;
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .nav {
            display: flex;
            gap: 1rem;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .nav a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .welcome-card h1 {
            color: #333;
            margin-bottom: 1rem;
        }
        
        .user-info {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .user-info h2 {
            color: #333;
            margin-bottom: 1rem;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 1rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">PHP Login System</div>
            <nav class="nav">
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <div class="welcome-card">
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <p>You have successfully logged into the PHP Login System. This dashboard shows your account information and session status.</p>
        </div>
        
        <div class="user-info">
            <h2>User Information</h2>
            
            <div class="info-row">
                <div class="info-label">Username:</div>
                <div class="info-value"><?php echo htmlspecialchars($user['username']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Member Since:</div>
                <div class="info-value"><?php echo date('F j, Y', strtotime($user['created_at'])); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Session Status:</div>
                <div class="info-value">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span style="color: #28a745;">Active Session</span>
                    <?php endif; ?>
                    <?php if (isset($_COOKIE['remember_user'])): ?>
                        <span style="color: #007bff;"> | Remember Me Active</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">User ID:</div>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['user_id']); ?></div>
            </div>
        </div>
    </div>
</body>
</html>