<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = trim($_POST['student_id']);
    $password = trim($_POST['password']);
    
    if (empty($student_id) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $stmt = $conn->prepare("SELECT id, student_id, name, password FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($student && $password == $student['password']) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_number'] = $student['student_id'];
            header('Location: student_dashboard.php');
            exit();
        } else {
            $error = 'Invalid student ID or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Complaint Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Student Login</h1>
            <div class="nav">
                <a href="index.php">Home</a>
                <a href="admin_login.php">Admin Login</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card login-card">
            <h2 style="text-align: center; margin-bottom: 2rem; color: #4a5568;">Student Portal</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="student_id">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" 
                           value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-full">Login</button>
            </form>
            
            <div style="text-align: center; margin-top: 1rem; color: #718096;">
                <small>Demo Credentials: STU001 / password123</small>
            </div>
        </div>
    </div>
</body>
</html>