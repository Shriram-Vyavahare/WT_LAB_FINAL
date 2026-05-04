<?php
$page_title = 'Login';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $query = "SELECT id, username, email, password, full_name, role FROM users WHERE username = ? OR email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Login</h2>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="username">Username or Email:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn">Login</button>
        <a href="register.php" class="btn btn-secondary">Register</a>
    </div>
</form>

<div style="background: #e6f7ff; padding: 15px; margin: 20px 0; border: 1px solid #91d5ff;">
    <h3>Demo Login Credentials</h3>
    <p><strong>Admin:</strong> username = admin, password = admin123</p>
    <p><strong>User:</strong> username = user1, password = user123</p>
    <p><strong>User:</strong> username = user2, password = user123</p>
    <p><em>Or register a new account with any username/password (3+ characters)</em></p>
</div>

<?php require_once 'includes/footer.php'; ?>