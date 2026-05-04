// API Base URL
const API_BASE = '/api';

// DOM Elements
const addBookForm = document.getElementById('addBookForm');
const booksTableContainer = document.getElementById('booksTableContainer');
const refreshButton = document.getElementById('refreshBooks');
const messageContainer = document.getElementById('messageContainer');
const bookCountElement = document.getElementById('bookCount');
const searchResults = document.getElementById('searchResults');

// Modal elements
const deleteModal = document.getElementById('deleteModal');
const confirmDeleteBtn = document.getElementById('confirmDelete');
const cancelDeleteBtn = document.getElementById('cancelDelete');
let bookToDelete = null;

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    showSection('add-book'); // Show add book section by default
});

// Setup event listeners
function setupEventListeners() {
    addBookForm.addEventListener('submit', handleAddBook);
    refreshButton.addEventListener('click', loadBooks);
    
    // Modal event listeners
    cancelDeleteBtn.addEventListener('click', closeDeleteModal);
    confirmDeleteBtn.addEventListener('click', confirmDelete);
    
    // Close modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });
    
    // Search inputs
    document.getElementById('searchTitle').addEventListener('input', debounce(searchBooks, 300));
    document.getElementById('searchAuthor').addEventListener('input', debounce(searchBooks, 300));
    document.getElementById('searchYear').addEventListener('input', debounce(searchBooks, 300));
}

// Navigation function to show different sections
function showSection(sectionName) {
    // Hide all sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.classList.remove('active'));
    
    // Remove active class from all nav buttons
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(btn => btn.classList.remove('active'));
    
    // Show selected section
    const targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    // Add active class to corresponding nav button
    const activeButton = document.getElementById('show' + sectionName.split('-').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)).join('') + 'Btn');
    if (activeButton) {
        activeButton.classList.add('active');
    }
    
    // Load books when viewing books section
    if (sectionName === 'view-books') {
        loadBooks();
    }
}

// Handle adding a new book
async function handleAddBook(event) {
    event.preventDefault();
    
    const formData = new FormData(addBookForm);
    const bookData = {
        title: formData.get('title').trim(),
        author: formData.get('author').trim(),
        year: parseInt(formData.get('year'))
    };
    
    // Validate input
    if (!bookData.title || !bookData.author || !bookData.year) {
        showMessage('Please fill in all fields', 'error');
        return;
    }
    
    if (bookData.year < 1000 || bookData.year > 2030) {
        showMessage('Please enter a valid publication year', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/books`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(bookData)
        });
        
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.error || 'Failed to add book');
        }
        
        const newBook = await response.json();
        showMessage(`📚 Book "${newBook.title}" added successfully!`, 'success');
        addBookForm.reset();
        
        // Auto-switch to view books section to show the new book
        showSection('view-books');
        
    } catch (error) {
        console.error('Error adding book:', error);
        showMessage(error.message || 'Failed to add book', 'error');
    }
}

// Load and display all books
async function loadBooks() {
    try {
        booksTableContainer.innerHTML = '<div class="loading">📚 Loading books...</div>';
        
        const response = await fetch(`${API_BASE}/books`);
        
        if (!response.ok) {
            throw new Error('Failed to fetch books');
        }
        
        const books = await response.json();
        displayBooksTable(books);
        updateBookCount(books.length);
        
    } catch (error) {
        console.error('Error loading books:', error);
        booksTableContainer.innerHTML = `
            <div class="no-books">
                <p>❌ Failed to load books</p>
                <p>${error.message}</p>
                <button onclick="loadBooks()" class="btn btn-secondary">🔄 Try Again</button>
            </div>
        `;
        updateBookCount(0);
    }
}

// Display books in table format
function displayBooksTable(books) {
    if (books.length === 0) {
        booksTableContainer.innerHTML = `
            <div class="no-books">
                <p>📚 No books in the library yet</p>
                <p>Click "Add New Book" to add your first book!</p>
                <button onclick="showSection('add-book')" class="btn btn-primary">➕ Add First Book</button>
            </div>
        `;
        return;
    }
    
    const tableHTML = `
        <table class="books-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>📚 Title</th>
                    <th>👤 Author</th>
                    <th>📅 Year</th>
                    <th>🕒 Added</th>
                    <th>⚡ Actions</th>
                </tr>
            </thead>
            <tbody>
                ${books.map(book => `
                    <tr>
                        <td><span class="book-id">${book.book_id}</span></td>
                        <td><span class="book-title">${escapeHtml(book.title)}</span></td>
                        <td><span class="book-author">${escapeHtml(book.author)}</span></td>
                        <td><span class="book-year">${book.year}</span></td>
                        <td>${formatDate(book.created_at)}</td>
                        <td>
                            <div class="book-actions">
                                <button onclick="viewBookDetails(${book.book_id})" 
                                        class="btn btn-success" title="View Details">
                                    👁️ View
                                </button>
                                <button onclick="showDeleteModal(${book.book_id}, '${escapeHtml(book.title)}')" 
                                        class="btn btn-danger" title="Delete Book">
                                    🗑️ Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    
    booksTableContainer.innerHTML = tableHTML;
}

// Search books functionality
async function searchBooks() {
    const title = document.getElementById('searchTitle').value.trim();
    const author = document.getElementById('searchAuthor').value.trim();
    const year = document.getElementById('searchYear').value.trim();
    
    if (!title && !author && !year) {
        searchResults.innerHTML = '<div class="no-results">Enter search criteria to find books</div>';
        return;
    }
    
    try {
        searchResults.innerHTML = '<div class="loading">🔍 Searching books...</div>';
        
        const response = await fetch(`${API_BASE}/books`);
        if (!response.ok) {
            throw new Error('Failed to fetch books');
        }
        
        const allBooks = await response.json();
        
        // Filter books based on search criteria
        const filteredBooks = allBooks.filter(book => {
            const titleMatch = !title || book.title.toLowerCase().includes(title.toLowerCase());
            const authorMatch = !author || book.author.toLowerCase().includes(author.toLowerCase());
            const yearMatch = !year || book.year.toString() === year;
            
            return titleMatch && authorMatch && yearMatch;
        });
        
        if (filteredBooks.length === 0) {
            searchResults.innerHTML = `
                <div class="no-results">
                    <p>🔍 No books found matching your criteria</p>
                    <p>Try different search terms or check the spelling</p>
                </div>
            `;
        } else {
            displaySearchResults(filteredBooks);
        }
        
    } catch (error) {
        console.error('Error searching books:', error);
        searchResults.innerHTML = `
            <div class="no-results">
                <p>❌ Search failed</p>
                <p>${error.message}</p>
            </div>
        `;
    }
}

// Display search results
function displaySearchResults(books) {
    const tableHTML = `
        <table class="books-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>📚 Title</th>
                    <th>👤 Author</th>
                    <th>📅 Year</th>
                    <th>⚡ Actions</th>
                </tr>
            </thead>
            <tbody>
                ${books.map(book => `
                    <tr>
                        <td><span class="book-id">${book.book_id}</span></td>
                        <td><span class="book-title">${escapeHtml(book.title)}</span></td>
                        <td><span class="book-author">${escapeHtml(book.author)}</span></td>
                        <td><span class="book-year">${book.year}</span></td>
                        <td>
                            <div class="book-actions">
                                <button onclick="viewBookDetails(${book.book_id})" 
                                        class="btn btn-success">
                                    👁️ View
                                </button>
                                <button onclick="showDeleteModal(${book.book_id}, '${escapeHtml(book.title)}')" 
                                        class="btn btn-danger">
                                    🗑️ Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
        <div style="margin-top: 15px; text-align: center; color: #666;">
            Found ${books.length} book${books.length !== 1 ? 's' : ''} matching your search
        </div>
    `;
    
    searchResults.innerHTML = tableHTML;
}

// Clear search
function clearSearch() {
    document.getElementById('searchTitle').value = '';
    document.getElementById('searchAuthor').value = '';
    document.getElementById('searchYear').value = '';
    searchResults.innerHTML = '<div class="no-results">Enter search criteria and click "Search Books"</div>';
}

// View book details
async function viewBookDetails(bookId) {
    try {
        const response = await fetch(`${API_BASE}/books/${bookId}`);
        if (!response.ok) {
            throw new Error('Book not found');
        }
        
        const book = await response.json();
        
        const details = `
📚 Title: ${book.title}
👤 Author: ${book.author}
📅 Year: ${book.year}
🆔 Book ID: ${book.book_id}
🕒 Added: ${formatDate(book.created_at)}
        `.trim();
        
        alert(details);
        
    } catch (error) {
        showMessage('Failed to load book details', 'error');
    }
}

// Delete modal functions
function showDeleteModal(bookId, bookTitle) {
    bookToDelete = { id: bookId, title: bookTitle };
    document.getElementById('deleteMessage').textContent = 
        `Are you sure you want to delete "${bookTitle}"? This action cannot be undone.`;
    deleteModal.style.display = 'block';
}

function closeDeleteModal() {
    deleteModal.style.display = 'none';
    bookToDelete = null;
}

async function confirmDelete() {
    if (!bookToDelete) return;
    
    try {
        const response = await fetch(`${API_BASE}/books/${bookToDelete.id}`, {
            method: 'DELETE'
        });
        
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.error || 'Failed to delete book');
        }
        
        showMessage(`🗑️ Book "${bookToDelete.title}" deleted successfully!`, 'success');
        closeDeleteModal();
        
        // Refresh current view
        const activeSection = document.querySelector('.content-section.active');
        if (activeSection.id === 'view-books-section') {
            loadBooks();
        } else if (activeSection.id === 'search-book-section') {
            searchBooks();
        }
        
    } catch (error) {
        console.error('Error deleting book:', error);
        showMessage(error.message || 'Failed to delete book', 'error');
        closeDeleteModal();
    }
}

// Update book count display
function updateBookCount(count) {
    if (bookCountElement) {
        bookCountElement.textContent = `${count} book${count !== 1 ? 's' : ''}`;
    }
}

// Show success/error messages
function showMessage(text, type = 'success') {
    const message = document.createElement('div');
    message.className = `message ${type}`;
    message.textContent = text;
    
    messageContainer.appendChild(message);
    
    // Auto-remove message after 5 seconds
    setTimeout(() => {
        message.classList.add('fade-out');
        setTimeout(() => {
            if (message.parentNode) {
                message.parentNode.removeChild(message);
            }
        }, 300);
    }, 5000);
}

// Utility functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Handle network status
window.addEventListener('online', function() {
    showMessage('🌐 Connection restored', 'success');
});

window.addEventListener('offline', function() {
    showMessage('📡 Connection lost. Some features may not work.', 'error');
});