-- Sample data for the library management system
-- Run this after the application has created the database and table

USE library_db;

-- Insert sample books
INSERT INTO books (title, author, year) VALUES
('To Kill a Mockingbird', 'Harper Lee', 1960),
('1984', 'George Orwell', 1949),
('Pride and Prejudice', 'Jane Austen', 1813),
('The Great Gatsby', 'F. Scott Fitzgerald', 1925),
('Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', 1997),
('The Catcher in the Rye', 'J.D. Salinger', 1951),
('Lord of the Flies', 'William Golding', 1954),
('The Chronicles of Narnia', 'C.S. Lewis', 1950),
('Brave New World', 'Aldous Huxley', 1932),
('The Hobbit', 'J.R.R. Tolkien', 1937);

-- Verify the data
SELECT * FROM books ORDER BY created_at DESC;