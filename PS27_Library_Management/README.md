# Library Management System

A simple Node.js application for managing book records in a library. Librarians can add books and view all available books through a clean web interface.

## Features

- ✅ Add new books with title, author, and publication year
- ✅ View all books in the library
- ✅ Delete books from the collection
- ✅ Responsive web interface
- ✅ MySQL database storage on port 3307
- ✅ RESTful API endpoints

## Database Schema

### Books Table
- `book_id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `title` (VARCHAR(255), NOT NULL)
- `author` (VARCHAR(255), NOT NULL)
- `year` (INT, NOT NULL)
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)

## Prerequisites

- Node.js (v14 or higher)
- MySQL Server running on port 3307
- npm or yarn package manager

## Installation

1. Clone or download this project
2. Install dependencies:
   ```bash
   npm install
   ```

3. Configure MySQL:
   - Ensure MySQL is running on port 3307
   - Update the database credentials in `database.js` if needed:
     ```javascript
     const connection = mysql.createConnection({
       host: 'localhost',
       user: 'root',
       password: '', // Update with your MySQL password
       database: 'library_db',
       port: 3307
     });
     ```

4. Start the application:
   ```bash
   npm start
   ```
   
   For development with auto-restart:
   ```bash
   npm run dev
   ```

## Usage

1. Open your browser and navigate to `http://localhost:3001`
2. Use the form on the left to add new books
3. View all books in the library on the right side
4. Delete books using the delete button on each book card

## API Endpoints

### GET /api/books
Retrieve all books in the library
- **Response**: Array of book objects

### POST /api/books
Add a new book to the library
- **Body**: JSON object with `title`, `author`, and `year`
- **Response**: The created book object

### GET /api/books/:id
Get a specific book by ID
- **Response**: Book object or 404 if not found

### DELETE /api/books/:id
Delete a book by ID
- **Response**: Success message or 404 if not found

## Project Structure

```
library-management-system/
├── server.js              # Main server file
├── database.js            # Database connection and setup
├── package.json           # Project dependencies
├── public/                # Static files
│   ├── index.html        # Main HTML page
│   ├── styles.css        # CSS styles
│   └── script.js         # Frontend JavaScript
└── README.md             # This file
```

## Database Setup

The application automatically creates the database and table on startup:
- Database: `library_db`
- Table: `books`

Make sure your MySQL server is running on port 3307 before starting the application.

## Technologies Used

- **Backend**: Node.js, Express.js
- **Database**: MySQL (port 3307)
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Styling**: Modern CSS with gradients and animations

## License

MIT License