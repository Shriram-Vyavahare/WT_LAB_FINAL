<?php
echo "<h2>Debug Script - Checking Database</h2>";

// Test database connection
echo "<h3>1. Testing Database Connection</h3>";
try {
    $host = 'localhost:3307';
    $username = 'root';
    $password = '';
    $database = 'student_complaints';

    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection: SUCCESS<br><br>";
} catch(PDOException $e) {
    echo "❌ Database connection: FAILED<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "<strong>Try changing port to 3306 in config/database.php</strong><br><br>";
    
    // Try with port 3306
    try {
        $host = 'localhost:3306';
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        echo "✅ Database connection with port 3306: SUCCESS<br>";
        echo "<strong>Update your config/database.php to use port 3306</strong><br><br>";
    } catch(PDOException $e2) {
        echo "❌ Database connection with port 3306: FAILED<br>";
        echo "Make sure XAMPP MySQL is running<br><br>";
        exit;
    }
}

// Check if tables exist
echo "<h3>2. Checking Tables</h3>";
$tables = ['admin', 'students', 'complaints'];
foreach($tables as $table) {
    try {
        $stmt = $conn->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "✅ Table '$table': EXISTS ($count records)<br>";
    } catch(PDOException $e) {
        echo "❌ Table '$table': NOT FOUND<br>";
        echo "You need to import database.sql file<br>";
    }
}

// Check admin data
echo "<br><h3>3. Checking Admin Data</h3>";
try {
    $stmt = $conn->query("SELECT username, password, name FROM admin");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($admins)) {
        echo "❌ No admin records found<br>";
        echo "Inserting admin record...<br>";
        $stmt = $conn->prepare("INSERT INTO admin (username, password, name) VALUES (?, ?, ?)");
        $stmt->execute(['admin', 'admin123', 'System Administrator']);
        echo "✅ Admin record created<br>";
    } else {
        echo "Admin records found:<br>";
        foreach($admins as $admin) {
            echo "- Username: <strong>{$admin['username']}</strong>, Password: <strong>{$admin['password']}</strong><br>";
        }
    }
} catch(PDOException $e) {
    echo "❌ Error checking admin: " . $e->getMessage() . "<br>";
}

// Check student data
echo "<br><h3>4. Checking Student Data</h3>";
try {
    $stmt = $conn->query("SELECT student_id, password, name FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($students)) {
        echo "❌ No student records found<br>";
        echo "Inserting student records...<br>";
        $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, password, department, year) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['STU001', 'John Doe', 'john@college.edu', 'password123', 'Computer Science', 2]);
        $stmt->execute(['STU002', 'Jane Smith', 'jane@college.edu', 'password123', 'Electronics', 3]);
        echo "✅ Student records created<br>";
    } else {
        echo "Student records found:<br>";
        foreach($students as $student) {
            echo "- Student ID: <strong>{$student['student_id']}</strong>, Password: <strong>{$student['password']}</strong><br>";
        }
    }
} catch(PDOException $e) {
    echo "❌ Error checking students: " . $e->getMessage() . "<br>";
}

// Test login simulation
echo "<br><h3>5. Testing Login Logic</h3>";

// Test admin login
echo "<strong>Testing Admin Login:</strong><br>";
$test_username = 'admin';
$test_password = 'admin123';

$stmt = $conn->prepare("SELECT id, username, name, password FROM admin WHERE username = ?");
$stmt->execute([$test_username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    echo "Admin found: {$admin['username']}<br>";
    echo "Stored password: '{$admin['password']}'<br>";
    echo "Test password: '$test_password'<br>";
    
    if ($test_password == $admin['password']) {
        echo "✅ Admin login: SUCCESS<br>";
    } else {
        echo "❌ Admin login: FAILED - Password mismatch<br>";
    }
} else {
    echo "❌ Admin not found<br>";
}

// Test student login
echo "<br><strong>Testing Student Login:</strong><br>";
$test_student_id = 'STU001';
$test_password = 'password123';

$stmt = $conn->prepare("SELECT id, student_id, name, password FROM students WHERE student_id = ?");
$stmt->execute([$test_student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if ($student) {
    echo "Student found: {$student['student_id']}<br>";
    echo "Stored password: '{$student['password']}'<br>";
    echo "Test password: '$test_password'<br>";
    
    if ($test_password == $student['password']) {
        echo "✅ Student login: SUCCESS<br>";
    } else {
        echo "❌ Student login: FAILED - Password mismatch<br>";
    }
} else {
    echo "❌ Student not found<br>";
}

echo "<br><h3>6. Next Steps</h3>";
echo "If all tests pass, try logging in again.<br>";
echo "If tests fail, the issue is identified above.<br>";
echo "<br><a href='index.php'>Go to Application</a>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
</style>