const mysql = require('mysql2');

// Create connection to MySQL server (without specifying database initially)
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '', // Update with your MySQL password
  port: 3307
});

// Connect to MySQL server
connection.connect((err) => {
  if (err) {
    console.error('Error connecting to MySQL server:', err);
    return;
  }
  console.log('Connected to MySQL server on port 3307');
  
  // Initialize database after successful connection
  initializeDatabase();
});

// Create database and table if they don't exist
const initializeDatabase = () => {
  // Create database if it doesn't exist
  connection.query('CREATE DATABASE IF NOT EXISTS library_db', (err) => {
    if (err) {
      console.error('Error creating database:', err);
      return;
    }
    console.log('Database library_db created/verified');
    
    // Use the database
    connection.query('USE library_db', (err) => {
      if (err) {
        console.error('Error selecting database:', err);
        return;
      }
      console.log('Using library_db database');
      
      // Create books table if it doesn't exist
      const createTableQuery = `
        CREATE TABLE IF NOT EXISTS books (
          book_id INT AUTO_INCREMENT PRIMARY KEY,
          title VARCHAR(255) NOT NULL,
          author VARCHAR(255) NOT NULL,
          year INT NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
      `;

      connection.query(createTableQuery, (err) => {
        if (err) {
          console.error('Error creating books table:', err);
          return;
        }
        console.log('Books table created/verified');
        console.log('Database setup complete!');
      });
    });
  });
};

module.exports = connection;