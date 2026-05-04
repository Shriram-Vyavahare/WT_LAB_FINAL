-- Database Setup Script for Student Management System
-- Run this file before starting the application

-- Create the database
CREATE DATABASE IF NOT EXISTS studentdb;

-- Use the database
USE studentdb;

-- Create the students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    course VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Display success message
SELECT 'Database and table created successfully!' AS Status;

-- Show the table structure
DESCRIBE students;
