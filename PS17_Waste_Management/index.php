<?php
require_once 'config.php';

// Handle form submission
if ($_POST) {
    $location = sanitize($_POST['location']);
    $waste_type = sanitize($_POST['waste_type']);
    $description = sanitize($_POST['description']);
    $reporter_name = sanitize($_POST['reporter_name']);
    $reporter_phone = sanitize($_POST['reporter_phone']);
    $priority = sanitize($_POST['priority']);
    
    if ($location && $waste_type && $reporter_name && $reporter_phone) {
        try {
            $pdo = getConnection();
            $stmt = $pdo->prepare("INSERT INTO waste_reports (location, waste_type, description, reporter_name, reporter_phone, priority) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$location, $waste_type, $description, $reporter_name, $reporter_phone, $priority]);
            
            $success = "Waste report submitted successfully! Report ID: " . $pdo->lastInsertId();
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Collection Management System</title>
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
            max-width: 800px;
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
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
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
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }
        
        .btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .required {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗂️ Waste Collection System</h1>
            <p>Report waste locations for proper collection and management</p>
        </div>
        
        <div class="nav">
            <a href="index.php" class="active">Report Waste</a>
            <a href="reports.php">View Reports</a>
            <a href="admin.php">Admin Panel</a>
        </div>
        
        <div class="content">
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <h2>Report Waste Location</h2>
            <p style="margin-bottom: 30px; color: #666;">Fill out the form below to report waste that needs collection.</p>
            
            <form method="POST">
                <div class="form-group">
                    <label for="location">Location <span class="required">*</span></label>
                    <input type="text" id="location" name="location" placeholder="Enter the exact location of waste" required>
                </div>
                
                <div class="form-group">
                    <label for="waste_type">Waste Type <span class="required">*</span></label>
                    <select id="waste_type" name="waste_type" required>
                        <option value="">Select waste type</option>
                        <option value="plastic">Plastic</option>
                        <option value="paper">Paper</option>
                        <option value="glass">Glass</option>
                        <option value="metal">Metal</option>
                        <option value="organic">Organic</option>
                        <option value="electronic">Electronic</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="priority">Priority Level</label>
                    <select id="priority" name="priority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Additional details about the waste (optional)"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="reporter_name">Your Name <span class="required">*</span></label>
                    <input type="text" id="reporter_name" name="reporter_name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="reporter_phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="reporter_phone" name="reporter_phone" placeholder="Enter your phone number" required>
                </div>
                
                <button type="submit" class="btn">Submit Report</button>
            </form>
        </div>
    </div>
</body>
</html>