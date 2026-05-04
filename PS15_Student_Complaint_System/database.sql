-- Student Complaint Management System Database
-- Create database and tables

CREATE DATABASE IF NOT EXISTS student_complaints;
USE student_complaints;

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Complaints table
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    status ENUM('Pending', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Insert sample admin user (username: admin, password: admin123)
INSERT INTO admin (username, password, name) VALUES 
('admin', 'admin123', 'System Administrator');

-- Insert sample students (password: password123 for all)
INSERT INTO students (student_id, name, email, password, department, year) VALUES 
('STU001', 'John Doe', 'john@college.edu', 'password123', 'Computer Science', 2),
('STU002', 'Jane Smith', 'jane@college.edu', 'password123', 'Electronics', 3);

-- Insert sample complaints
INSERT INTO complaints (student_id, title, description, category, priority) VALUES 
(1, 'Library WiFi Issues', 'The WiFi in the library is very slow and frequently disconnects', 'Infrastructure', 'High'),
(2, 'Cafeteria Food Quality', 'The food quality in the cafeteria has deteriorated recently', 'Food Services', 'Medium');