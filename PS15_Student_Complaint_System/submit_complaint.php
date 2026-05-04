<?php
session_start();
require_once 'config/database.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $priority = trim($_POST['priority']);
    
    if (empty($title) || empty($description) || empty($category) || empty($priority)) {
        $error = 'Please fill in all fields';
    } else {
        $stmt = $conn->prepare("
            INSERT INTO complaints (student_id, title, description, category, priority) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$_SESSION['student_id'], $title, $description, $category, $priority])) {
            $success = 'Complaint submitted successfully! You will be redirected to your dashboard.';
            echo "<script>setTimeout(function(){ window.location.href = 'student_dashboard.php'; }, 2000);</script>";
        } else {
            $error = 'Failed to submit complaint. Please try again.';
        }
    }
}

$categories = [
    'Academic Issues',
    'Infrastructure',
    'Food Services',
    'Transportation',
    'Library Services',
    'Hostel/Accommodation',
    'Administrative Issues',
    'Faculty Related',
    'Examination Issues',
    'Other'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - Student Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Submit Complaint</h1>
            <div class="nav">
                <a href="student_dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <h2 style="margin-bottom: 2rem; color: #4a5568;">Submit New Complaint</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Complaint Title:</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                           placeholder="Brief description of your complaint" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat; ?>" 
                                    <?php echo (isset($_POST['category']) && $_POST['category'] == $cat) ? 'selected' : ''; ?>>
                                <?php echo $cat; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="priority">Priority:</label>
                    <select id="priority" name="priority" class="form-control" required>
                        <option value="">Select priority</option>
                        <option value="Low" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                        <option value="Medium" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="High" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Detailed Description:</label>
                    <textarea id="description" name="description" class="form-control" rows="6" 
                              placeholder="Please provide detailed information about your complaint..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Submit Complaint</button>
                    <a href="student_dashboard.php" class="btn" style="background: #e2e8f0; color: #4a5568;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>