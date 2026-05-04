-- Database Setup Script for Result System
-- Run this file before starting the project to create the database and table

-- Create the database
CREATE DATABASE IF NOT EXISTS result_system;

-- Use the database
USE result_system;

-- Create the student_results table
CREATE TABLE IF NOT EXISTS student_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255) NOT NULL,
    course VARCHAR(255) NOT NULL,
    prn VARCHAR(50) NOT NULL,
    
    -- Subject 1
    subject1 VARCHAR(255) NOT NULL,
    mse1 INT NOT NULL,
    ese1 INT NOT NULL,
    total1 INT NOT NULL,
    status1 VARCHAR(10) NOT NULL,
    
    -- Subject 2
    subject2 VARCHAR(255) NOT NULL,
    mse2 INT NOT NULL,
    ese2 INT NOT NULL,
    total2 INT NOT NULL,
    status2 VARCHAR(10) NOT NULL,
    
    -- Subject 3
    subject3 VARCHAR(255) NOT NULL,
    mse3 INT NOT NULL,
    ese3 INT NOT NULL,
    total3 INT NOT NULL,
    status3 VARCHAR(10) NOT NULL,
    
    -- Subject 4
    subject4 VARCHAR(255) NOT NULL,
    mse4 INT NOT NULL,
    ese4 INT NOT NULL,
    total4 INT NOT NULL,
    status4 VARCHAR(10) NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Display success message
SELECT 'Database and table created successfully!' AS Message;
