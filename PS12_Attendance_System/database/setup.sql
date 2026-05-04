-- Step 1: Create database
CREATE DATABASE IF NOT EXISTS attendance_system;
USE attendance_system;

-- Step 2: Create students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL
);

-- Step 3: Create attendance table
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    status VARCHAR(10) NOT NULL
); 