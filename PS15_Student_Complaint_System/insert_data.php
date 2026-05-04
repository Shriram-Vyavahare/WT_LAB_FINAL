<?php
echo "<h2>Inserting Fresh Data with Plain Text Passwords</h2>";

try {
    // Database connection
    $host = 'localhost:3307';
    $username = 'root';
    $password = '';
    $database = 'student_complaints';

    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to database<br><br>";
    
    // Insert admin with plain text password
    echo "1. Inserting Admin...<br>";
    $stmt = $conn->prepare("INSERT INTO admin (username, password, name) VALUES (?, ?, ?)");
    $stmt->execute(['admin', 'admin123', 'System Administrator']);
    echo "✅ Admin inserted: username = <strong>admin</strong>, password = <strong>admin123</strong><br><br>";
    
    // Insert students with plain text passwords
    echo "2. Inserting Students...<br>";
    $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, password, department, year) VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->execute(['STU001', 'John Doe', 'john@college.edu', 'password123', 'Computer Science', 2]);
    echo "✅ Student inserted: student_id = <strong>STU001</strong>, password = <strong>password123</strong><br>";
    
    $stmt->execute(['STU002', 'Jane Smith', 'jane@college.edu', 'password123', 'Electronics', 3]);
    echo "✅ Student inserted: student_id = <strong>STU002</strong>, password = <strong>password123</strong><br><br>";
    
    // Insert sample complaints
    echo "3. Inserting Sample Complaints...<br>";
    $stmt = $conn->prepare("INSERT INTO complaints (student_id, title, description, category, priority) VALUES (?, ?, ?, ?, ?)");
    
    $stmt->execute([1, 'Library WiFi Issues', 'The WiFi in the library is very slow and frequently disconnects', 'Infrastructure', 'High']);
    $stmt->execute([2, 'Cafeteria Food Quality', 'The food quality in the cafeteria has deteriorated recently', 'Food Services', 'Medium']);
    echo "✅ Sample complaints inserted<br><br>";
    
    // Verify the data
    echo "4. Verifying Data...<br>";
    
    // Check admin
    $stmt = $conn->query("SELECT username, password FROM admin");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Admin: username = '{$admin['username']}', password = '{$admin['password']}'<br>";
    
    // Check students
    $stmt = $conn->query("SELECT student_id, password FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($students as $student) {
        echo "Student: student_id = '{$student['student_id']}', password = '{$student['password']}'<br>";
    }
    
    echo "<br><h3>🎉 Data Insertion Complete!</h3>";
    echo "<p><strong>You can now login with:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> username = <code>admin</code>, password = <code>admin123</code></li>";
    echo "<li><strong>Student:</strong> student_id = <code>STU001</code>, password = <code>password123</code></li>";
    echo "<li><strong>Student:</strong> student_id = <code>STU002</code>, password = <code>password123</code></li>";
    echo "</ul>";
    echo "<p><a href='index.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Application</a></p>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Make sure XAMPP MySQL is running and database exists.<br>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
code { background: #f4f4f4; padding: 2px 4px; border-radius: 3px; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
</style>