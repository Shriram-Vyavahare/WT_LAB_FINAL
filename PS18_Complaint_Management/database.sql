-- Simple Complaint Management System Database

CREATE DATABASE IF NOT EXISTS complaint_management;
USE complaint_management;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Organizations table
CREATE TABLE organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Complaints table
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    organization_id INT NOT NULL,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'resolved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (organization_id) REFERENCES organizations(id)
);

-- Insert default users
INSERT INTO users (username, password, full_name, role) VALUES
('admin', 'admin123', 'Administrator', 'admin'),
('user', 'user123', 'Regular User', 'user');

-- Insert organizations
INSERT INTO organizations (name) VALUES
('PMC - Pune Municipal Corporation'),
('PMT - Pune Mahanagar Parivahan'),
('MSEB - Maharashtra Electricity Board'),
('Water Supply Department');