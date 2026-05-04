-- Waste Collection Management System Database
-- Run this script to create the database and tables

CREATE DATABASE IF NOT EXISTS waste_management;
USE waste_management;

-- Table for waste reports
CREATE TABLE waste_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(255) NOT NULL,
    waste_type ENUM('plastic', 'paper', 'glass', 'metal', 'organic', 'electronic', 'other') NOT NULL,
    description TEXT,
    reporter_name VARCHAR(100) NOT NULL,
    reporter_phone VARCHAR(15) NOT NULL,
    status ENUM('pending', 'assigned', 'collected', 'completed') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for authorities/collectors
CREATE TABLE authorities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100),
    area VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample authorities
INSERT INTO authorities (name, phone, email, area) VALUES
('City Waste Management', '123-456-7890', 'city@waste.com', 'Downtown'),
('Green Clean Services', '123-456-7891', 'green@clean.com', 'Suburbs'),
('Eco Collectors', '123-456-7892', 'eco@collectors.com', 'Industrial Area');

-- Table for assignments
CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    authority_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (report_id) REFERENCES waste_reports(id) ON DELETE CASCADE,
    FOREIGN KEY (authority_id) REFERENCES authorities(id) ON DELETE CASCADE
);