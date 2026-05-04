<!DOCTYPE html>
<html>
<head>
    <title>Complaint Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .container { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #005a87; }
        .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .credentials { background: #e6f3ff; padding: 15px; border-radius: 4px; margin-top: 20px; font-size: 14px; }
        .credentials h4 { margin: 0 0 10px 0; color: #007cba; }
    </style>
</head>
<body>
    <?php
    require_once 'config.php';
    
    if (isLoggedIn()) {
        if (isAdmin()) {
            redirect('admin.php');
        } else {
            redirect('user.php');
        }
    }
    
    $error = '';
    
    if ($_POST) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] === 'admin') {
                redirect('admin.php');
            } else {
                redirect('user.php');
            }
        } else {
            $error = 'Invalid username or password';
        }
    }
    ?>
    
    <div class="container">
        <h2>Login</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="credentials">
            <h4>Login Credentials:</h4>
            <strong>Admin:</strong> username = admin, password = admin123<br>
            <strong>User:</strong> username = user1, password = user123
        </div>
    </div>
</body>
</html>