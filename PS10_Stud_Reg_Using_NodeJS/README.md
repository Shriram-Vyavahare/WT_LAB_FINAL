# Student Management System

## Prerequisites
- Node.js installed
- MySQL Server running (default port 3307)

## Database Setup

### Step 1: Create Database and Table
Run the SQL setup file in your MySQL:

```bash
mysql -u root -p -P 3307 < setup.sql
```

Or manually in MySQL Workbench/Command Line:
1. Open MySQL client
2. Run the commands from `setup.sql` file

### Step 2: Install Dependencies
```bash
npm install
```

### Step 3: Configure Database Connection
Edit `db.js` if needed to match your MySQL credentials:
- **host**: localhost
- **port**: 3307
- **user**: root
- **password**: your_password
- **database**: studentdb

### Step 4: Start the Server
```bash
node server.js
```

Or with nodemon for auto-restart:
```bash
npx nodemon server.js
```

### Step 5: Access the Application
Open your browser and go to: `http://localhost:3000`

## Database Schema

**Table: students**
- `id` - INT (Primary Key, Auto Increment)
- `name` - VARCHAR(100)
- `email` - VARCHAR(100, Unique)
- `course` - VARCHAR(100)
- `created_at` - TIMESTAMP

## API Endpoints
- `POST /add-student` - Add a new student
- `GET /students` - Get all students
