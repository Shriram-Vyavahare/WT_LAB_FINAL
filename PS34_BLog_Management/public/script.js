// Base API URL
const API_BASE = '/api/posts';

// Utility function to display response
function displayResponse(response, status, time) {
    const responseBody = document.getElementById('responseBody');
    const responseStatus = document.getElementById('responseStatus');
    const responseTime = document.getElementById('responseTime');

    // Display formatted JSON
    responseBody.textContent = JSON.stringify(response, null, 2);

    // Display status
    responseStatus.textContent = `Status: ${status}`;
    responseStatus.className = `status-badge ${status >= 200 && status < 300 ? 'success' : 'error'}`;

    // Display response time
    responseTime.textContent = `Time: ${time}ms`;
    responseTime.className = 'time-badge';
}

// Utility function to make API requests
async function makeRequest(url, options = {}) {
    const startTime = Date.now();
    
    try {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
        
        const endTime = Date.now();
        const responseTime = endTime - startTime;
        
        const data = await response.json();
        displayResponse(data, response.status, responseTime);
        
        return { data, status: response.status, ok: response.ok };
    } catch (error) {
        const endTime = Date.now();
        const responseTime = endTime - startTime;
        
        const errorResponse = {
            success: false,
            message: 'Network error or server unavailable',
            error: error.message
        };
        
        displayResponse(errorResponse, 0, responseTime);
        return { data: errorResponse, status: 0, ok: false };
    }
}

// Clear form fields
function clearForm(prefix) {
    const fields = document.querySelectorAll(`[id^="${prefix}"]`);
    fields.forEach(field => field.value = '');
}

// Show notification
function showNotification(message, type = 'info') {
    // Simple alert for now - could be enhanced with toast notifications
    if (type === 'error') {
        alert('Error: ' + message);
    } else {
        console.log(message);
    }
}

// API Functions

// Get all posts
async function getAllPosts() {
    const result = await makeRequest(API_BASE);
    if (result.ok) {
        refreshPosts();
    }
}

// Get single post
async function getSinglePost() {
    const postId = document.getElementById('getPostId').value.trim();
    
    if (!postId) {
        showNotification('Please enter a Post ID', 'error');
        return;
    }
    
    await makeRequest(`${API_BASE}/${postId}`);
    document.getElementById('getPostId').value = '';
}

// Create new post
async function createPost() {
    const title = document.getElementById('createTitle').value.trim();
    const author = document.getElementById('createAuthor').value.trim();
    const content = document.getElementById('createContent').value.trim();
    
    if (!title || !author || !content) {
        showNotification('Please fill in all fields (Title, Author, Content)', 'error');
        return;
    }
    
    const result = await makeRequest(API_BASE, {
        method: 'POST',
        body: JSON.stringify({ title, author, content })
    });
    
    if (result.ok) {
        clearForm('create');
        refreshPosts();
    }
}

// Update post
async function updatePost() {
    const postId = document.getElementById('updateId').value.trim();
    const title = document.getElementById('updateTitle').value.trim();
    const author = document.getElementById('updateAuthor').value.trim();
    const content = document.getElementById('updateContent').value.trim();
    
    if (!postId) {
        showNotification('Please enter a Post ID', 'error');
        return;
    }
    
    if (!title || !author || !content) {
        showNotification('Please fill in all fields (Title, Author, Content)', 'error');
        return;
    }
    
    const result = await makeRequest(`${API_BASE}/${postId}`, {
        method: 'PUT',
        body: JSON.stringify({ title, author, content })
    });
    
    if (result.ok) {
        clearForm('update');
        refreshPosts();
    }
}

// Delete post
async function deletePost() {
    const postId = document.getElementById('deleteId').value.trim();
    
    if (!postId) {
        showNotification('Please enter a Post ID', 'error');
        return;
    }
    
    if (!confirm('Are you sure you want to delete this post?')) {
        return;
    }
    
    const result = await makeRequest(`${API_BASE}/${postId}`, {
        method: 'DELETE'
    });
    
    if (result.ok) {
        document.getElementById('deleteId').value = '';
        refreshPosts();
    }
}

// Refresh and display posts
async function refreshPosts() {
    try {
        const response = await fetch(API_BASE);
        const result = await response.json();
        
        const postsContainer = document.getElementById('postsContainer');
        
        if (result.success && result.data && result.data.length > 0) {
            postsContainer.innerHTML = result.data.map(post => `
                <div class="post-card">
                    <h4>${escapeHtml(post.title)}</h4>
                    <div class="post-meta">
                        <span class="post-id">ID: ${post.id}</span> | 
                        Author: ${escapeHtml(post.author)} | 
                        Created: ${new Date(post.createdAt).toLocaleString()} |
                        Updated: ${new Date(post.updatedAt).toLocaleString()}
                    </div>
                    <div class="post-content">${escapeHtml(post.content)}</div>
                </div>
            `).join('');
        } else {
            postsContainer.innerHTML = '<p style="text-align: center; color: #7f8c8d; padding: 20px;">No blog posts found. Create your first post!</p>';
        }
    } catch (error) {
        document.getElementById('postsContainer').innerHTML = '<p style="text-align: center; color: #e74c3c; padding: 20px;">Error loading posts. Please try again.</p>';
    }
}

// Utility function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Helper function to populate update form with existing post data
function populateUpdateForm(post) {
    document.getElementById('updateId').value = post.id;
    document.getElementById('updateTitle').value = post.title;
    document.getElementById('updateAuthor').value = post.author;
    document.getElementById('updateContent').value = post.content;
}

// Add click handlers for post cards to easily get IDs
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('post-id')) {
        const postId = e.target.textContent.replace('ID: ', '');
        
        // Copy to clipboard if available
        if (navigator.clipboard) {
            navigator.clipboard.writeText(postId).then(() => {
                console.log('Post ID copied to clipboard:', postId);
            });
        }
        
        // Also populate the get single post field
        document.getElementById('getPostId').value = postId;
    }
});

// Load posts when page loads
document.addEventListener('DOMContentLoaded', function() {
    refreshPosts();
});

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + Enter to submit forms
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        const activeElement = document.activeElement;
        
        if (activeElement.id.startsWith('create')) {
            createPost();
        } else if (activeElement.id.startsWith('update')) {
            updatePost();
        } else if (activeElement.id === 'getPostId') {
            getSinglePost();
        } else if (activeElement.id === 'deleteId') {
            deletePost();
        }
    }
});