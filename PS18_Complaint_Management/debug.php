<?php
require_once 'config.php';

echo "<h2>Database Debug</h2>";

try {
    // Check if users table exists and show all users
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll();
    
    echo "<h3>Users in database:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Full Name</th><th>Role</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['password'] . "</td>";
        echo "<td>" . $user['full_name'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test login for user
    echo "<h3>Testing user login:</h3>";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute(['user', 'user123']);
    $result = $stmt->fetch();
    
    if ($result) {
        echo "✅ User login works! Found user: " . $result['full_name'];
    } else {
        echo "❌ User login failed!";
        
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute(['user']);
        $userCheck = $stmt->fetch();
        
        if ($userCheck) {
            echo "<br>User exists but password doesn't match.";
            echo "<br>Expected: 'user123'";
            echo "<br>Found: '" . $userCheck['password'] . "'";
        } else {
            echo "<br>User doesn't exist in database!";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>