const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const path = require('path');
const db = require('./database');

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

// Routes

// Serve the main HTML page
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// API Routes

// Get all books
app.get('/api/books', (req, res) => {
  const query = 'SELECT * FROM books ORDER BY created_at DESC';
  
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error fetching books:', err);
      res.status(500).json({ error: 'Failed to fetch books' });
      return;
    }
    res.json(results);
  });
});

// Add a new book
app.post('/api/books', (req, res) => {
  const { title, author, year } = req.body;
  
  // Validate input
  if (!title || !author || !year) {
    res.status(400).json({ error: 'Title, author, and year are required' });
    return;
  }

  const query = 'INSERT INTO books (title, author, year) VALUES (?, ?, ?)';
  
  db.query(query, [title, author, year], (err, result) => {
    if (err) {
      console.error('Error adding book:', err);
      res.status(500).json({ error: 'Failed to add book' });
      return;
    }
    
    // Return the newly created book
    const newBookId = result.insertId;
    db.query('SELECT * FROM books WHERE book_id = ?', [newBookId], (err, book) => {
      if (err) {
        console.error('Error fetching new book:', err);
        res.status(500).json({ error: 'Book added but failed to retrieve' });
        return;
      }
      res.status(201).json(book[0]);
    });
  });
});

// Get a specific book by ID
app.get('/api/books/:id', (req, res) => {
  const bookId = req.params.id;
  const query = 'SELECT * FROM books WHERE book_id = ?';
  
  db.query(query, [bookId], (err, results) => {
    if (err) {
      console.error('Error fetching book:', err);
      res.status(500).json({ error: 'Failed to fetch book' });
      return;
    }
    
    if (results.length === 0) {
      res.status(404).json({ error: 'Book not found' });
      return;
    }
    
    res.json(results[0]);
  });
});

// Delete a book
app.delete('/api/books/:id', (req, res) => {
  const bookId = req.params.id;
  const query = 'DELETE FROM books WHERE book_id = ?';
  
  db.query(query, [bookId], (err, result) => {
    if (err) {
      console.error('Error deleting book:', err);
      res.status(500).json({ error: 'Failed to delete book' });
      return;
    }
    
    if (result.affectedRows === 0) {
      res.status(404).json({ error: 'Book not found' });
      return;
    }
    
    res.json({ message: 'Book deleted successfully' });
  });
});

// Start server
app.listen(PORT, () => {
  console.log(`Library Management Server running on http://localhost:${PORT}`);
  console.log(`API endpoints available at http://localhost:${PORT}/api/books`);
});