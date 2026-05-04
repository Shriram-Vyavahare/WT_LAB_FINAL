# Simple Complaint Management System

A minimal, professional complaint management system with user and admin panels.

## Features

### User Panel
- Login with hardcoded credentials
- Submit complaints to organizations (PMC, PMT, MSEB, Water Dept)
- View submitted complaints with status

### Admin Panel  
- View all complaints from all users
- Update complaint status (Pending/Resolved/Rejected)
- Dashboard with statistics

## Setup

1. **Database Setup:**
   ```bash
   mysql -u root -p -P 3307 < database.sql
   ```

2. **Access System:**
   - Open browser to your web server
   - Use login credentials shown on login page

## Login Credentials

**Admin:**
- Username: `admin`
- Password: `admin123`

**User:**
- Username: `user` 
- Password: `user123`

## Files Structure

```
├── index.php          # Login page
├── config.php         # Database config & functions
├── user.php           # User panel (submit & view complaints)
├── admin.php          # Admin panel (manage complaints)
├── logout.php         # Logout handler
├── database.sql       # Database schema
└── README.md          # This file
```

## How It Works

1. **Login** → Redirects to appropriate panel based on role
2. **User Panel** → Submit complaints & view status
3. **Admin Panel** → Manage all complaints & update status

Simple, clean, and functional!