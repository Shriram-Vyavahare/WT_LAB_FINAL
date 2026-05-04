# Student Complaint Management System

A simple web application for managing student complaints in a college environment, built with PHP and MySQL.

## Features

### Student Portal
- **Student Login**: Simple authentication for students
- **Submit Complaints**: Easy form for registering complaints
- **View Complaints**: Track status and progress of submitted complaints
- **Dashboard**: Overview of all personal complaints

### Admin Portal
- **Admin Login**: Simple authentication for administrators
- **View All Complaints**: Complete list of all student complaints
- **Update Status**: Change complaint status (Pending, In Progress, Resolved, Closed)
- **Statistics Dashboard**: Overview of complaint statistics

## Installation Instructions

### Prerequisites
- Web server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Setup Steps

1. **Import Database**
   - Create a new MySQL database named `student_complaints`
   - Import the `database.sql` file using phpMyAdmin or MySQL command line:
     ```bash
     mysql -u root -p student_complaints < database.sql
     ```

2. **Configure Database**
   - Edit `config/database.php` if needed
   - Update database credentials (default: localhost:3307, root, no password)

3. **File Structure**
   ```
   project-root/
   ├── config/
   │   └── database.php          # Simple database connection
   ├── css/
   │   └── style.css            # Styling
   ├── database.sql             # Database schema and sample data
   ├── index.php               # Home page
   ├── student_login.php       # Student login page
   ├── admin_login.php         # Admin login page
   ├── student_dashboard.php   # Student dashboard
   ├── submit_complaint.php    # Complaint submission form
   ├── view_complaint.php      # Student complaint view
   ├── admin_dashboard.php     # Admin dashboard
   ├── admin_view_complaint.php # Admin complaint management
   ├── logout.php              # Logout functionality
   └── README.md               # This file
   ```

## Default Credentials

### Admin Login
- **Username**: admin
- **Password**: admin123

### Student Login
- **Student ID**: STU001
- **Password**: password123
- **Student ID**: STU002
- **Password**: password123

## Usage

1. Import the `database.sql` file into your MySQL database
2. Place all files in your web server directory
3. Visit `index.php` in your browser
4. Login with the credentials above

## Database Schema

- **students**: Student information and authentication (plain text passwords)
- **admin**: Administrator accounts (plain text passwords)
- **complaints**: Complaint records with status tracking

## Security Note

This is a simplified version with plain text passwords for educational purposes. For production use, implement proper password hashing and additional security measures.