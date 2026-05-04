# PHP Login System

A complete PHP login system with user registration, login, session handling, cookies, and MySQL database integration.

## Features

- User Registration with validation
- User Login with username/email support
- Session management
- "Remember Me" functionality using cookies
- Password hashing for security
- Simple and clean UI
- MySQL database integration
- Logout functionality

## Files Structure

```
php-login-system/
├── config.php          # Database configuration and common functions
├── database.sql        # MySQL database creation script
├── register.php        # User registration form
├── login.php           # User login form
├── dashboard.php       # User dashboard (protected page)
├── logout.php          # Logout functionality
├── index.php           # Landing page (redirects based on login status)
└── README.md           # This file
```

## Setup Instructions

### 1. Database Setup

1. Start your MySQL server (XAMPP, WAMP, or standalone MySQL)
2. Open phpMyAdmin or MySQL command line
3. Execute the SQL commands from `database.sql` file:
   ```sql
   -- Copy and paste the contents of database.sql
   ```

### 2. Configuration

1. Update database credentials in `config.php` if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');        // Your MySQL username
   define('DB_PASS', '');            // Your MySQL password
   define('DB_NAME', 'php_login_system');
   ```

### 3. Web Server Setup

1. Place all files in your web server directory:
   - XAMPP: `htdocs/php-login-system/`
   - WAMP: `www/php-login-system/`
   - Local server: Your document root

2. Start your web server (Apache)

3. Access the application:
   ```
   http://localhost/php-login-system/
   ```

## Usage

### Registration
1. Go to `register.php` or click "Register here" from login page
2. Fill in username, email, and password
3. Password must be at least 6 characters
4. Username and email must be unique

### Login
1. Go to `login.php` or access the main page
2. Enter username/email and password
3. Check "Remember me" to stay logged in for 30 days
4. Click Login

### Dashboard
- After successful login, users are redirected to `dashboard.php`
- Shows user information and session status
- Displays whether "Remember Me" is active

### Logout
- Click "Logout" to end session and remove cookies
- Redirects back to login page

## Security Features

- **Password Hashing**: Uses PHP's `password_hash()` and `password_verify()`
- **SQL Injection Prevention**: Uses PDO prepared statements
- **XSS Protection**: Uses `htmlspecialchars()` for output
- **Session Security**: Proper session management
- **Input Validation**: Server-side validation for all inputs

## Default Test Account

- **Username**: admin
- **Email**: admin@example.com
- **Password**: password123

## Technical Details

### Session Management
- Sessions are started in `config.php`
- User ID and username stored in session
- Session checked on protected pages

### Cookie Implementation
- "Remember Me" sets a 30-day cookie
- Cookie contains base64 encoded user ID and username
- Cookie is validated against database on each request

### Database Schema
```sql
users table:
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- username (VARCHAR(50), UNIQUE)
- email (VARCHAR(100), UNIQUE)
- password (VARCHAR(255), hashed)
- created_at (TIMESTAMP)
```

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check MySQL server is running
   - Verify database credentials in `config.php`
   - Ensure database exists

2. **Session Issues**
   - Check if sessions are enabled in PHP
   - Verify write permissions for session directory

3. **Cookie Issues**
   - Ensure you're accessing via HTTP/HTTPS (not file://)
   - Check browser cookie settings

### Error Messages

- "Connection failed": Database connection issue
- "Username or email already exists": Duplicate registration
- "Invalid username or password": Login credentials incorrect
- "All fields are required": Missing form data

## Customization

### Styling
- CSS is embedded in each PHP file
- Modify the `<style>` sections to change appearance
- Colors, fonts, and layout can be easily customized

### Functionality
- Add password reset functionality
- Implement email verification
- Add user roles and permissions
- Create user profile editing

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- Web server (Apache/Nginx)
- PDO MySQL extension enabled

## License

This project is for educational purposes. Feel free to use and modify as needed.