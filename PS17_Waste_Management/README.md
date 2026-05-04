# Waste Collection Management System

A simple PHP-based web application for managing waste collection reports. Citizens can report waste locations, and authorities can manage and track collection activities.

## Features

- **Report Waste**: Citizens can submit waste reports with location, type, and priority
- **View Reports**: Public view of all submitted reports
- **Admin Panel**: Administrative interface to manage reports and assign authorities
- **Status Tracking**: Track waste collection from pending to completed
- **Authority Assignment**: Assign specific authorities to handle waste collection

## Setup Instructions

### Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB server running on port 3307
- Web server (Apache/Nginx) or PHP built-in server

### Installation Steps

1. **Database Setup**
   ```bash
   # Import the database schema
   mysql -u root -p -P 3307 < database.sql
   ```

2. **Configuration**
   - Update database credentials in `config.php` if needed
   - Default settings use:
     - Host: localhost
     - Port: 3307
     - Database: waste_management
     - Username: root
     - Password: (empty)

3. **Run the Application**
   ```bash
   # Using PHP built-in server
   php -S localhost:8000
   ```
   
   Then open http://localhost:8000 in your browser

## File Structure

```
├── index.php          # Main page - Report waste form
├── reports.php        # View all reports
├── admin.php          # Admin panel for managing reports
├── config.php         # Database configuration
├── database.sql       # Database schema and sample data
└── README.md          # This file
```

## Usage

### For Citizens
1. Visit the main page (index.php)
2. Fill out the waste report form with:
   - Location of waste
   - Type of waste (plastic, paper, glass, etc.)
   - Priority level
   - Your contact information
3. Submit the report

### For Administrators
1. Access the Admin Panel (admin.php)
2. View dashboard statistics
3. Manage reports by:
   - Updating status (pending → assigned → collected → completed)
   - Assigning authorities to handle collection
   - Tracking progress

## Database Schema

### Tables
- **waste_reports**: Stores all waste reports
- **authorities**: Collection agencies/authorities
- **assignments**: Links reports to assigned authorities

### Sample Authorities
The system comes with 3 pre-configured authorities:
- City Waste Management (Downtown area)
- Green Clean Services (Suburbs)
- Eco Collectors (Industrial Area)

## Security Features
- Input sanitization to prevent XSS attacks
- Prepared statements to prevent SQL injection
- Form validation on both client and server side

## Customization
- Modify waste types in the dropdown (index.php)
- Add more authorities via database or admin interface
- Customize styling in the embedded CSS
- Add email notifications for status updates

## Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Responsive design for mobile devices

## License
This project is created for educational purposes.