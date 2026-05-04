<?php
$page_title = 'Register';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'All required fields must be filled.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 3) {
        $error = 'Password must be at least 3 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        $query = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $error = 'Username or email already exists.';
        } else {
            $query = "INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            
            if ($stmt->execute([$username, $email, $password, $full_name, $phone])) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Register New Account</h2>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success">
        <?php echo $success; ?>
        <br><a href="login.php">Click here to login</a>
    </div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="full_name">Full Name *:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="username">Username *:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email Address *:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
    </div>
    
    <div class="form-group">
        <label for="password">Password * (min 3 characters):</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div class="form-group">
        <label for="confirm_password">Confirm Password *:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn">Register</button>
        <a href="login.php" class="btn btn-secondary">Back to Login</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>