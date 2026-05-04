<?php
require_once 'config.php';

echo "<h2>Database Reset</h2>";

try {
    // Drop and recreate users table
    $pdo->exec("DROP TABLE IF EXISTS complaints");
    $pdo->exec("DROP TABLE IF EXISTS users");
    $pdo->exec("DROP TABLE IF EXISTS organizations");
    
    // Create users table
    $pdo->exec("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(100) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user'
    )");
    
    // Create organizations table
    $pdo->exec("CREATE TABLE organizations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )");
    
    // Create complaints table
    $pdo->exec("CREATE TABLE complaints (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        organization_id INT NOT NULL,
        subject VARCHAR(200) NOT NULL,
        description TEXT NOT NULL,
        status ENUM('pending', 'resolved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (organization_id) REFERENCES organizations(id)
    )");
    
    // Insert users
    $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin123', 'Administrator', 'admin']);
    $stmt->execute(['user', 'user123', 'Regular User', 'user']);
    
    // Insert organizations
    $stmt = $pdo->prepare("INSERT INTO organizations (name) VALUES (?)");
    $stmt->execute(['PMC - Pune Municipal Corporation']);
    $stmt->execute(['PMT - Pune Mahanagar Parivahan']);
    $stmt->execute(['MSEB - Maharashtra Electricity Board']);
    $stmt->execute(['Water Supply Department']);
    
    echo "✅ Database reset successfully!<br>";
    echo "✅ Users created: admin/admin123 and user/user123<br>";
    echo "✅ Organizations created<br>";
    echo "<br><a href='index.php'>Go to Login Page</a>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>