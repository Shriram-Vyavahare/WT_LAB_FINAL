const express = require('express');
const cors = require('cors');
const { v4: uuidv4 } = require('uuid');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.static('public'));

// In-memory storage for blog posts
// Using fixed IDs for initial posts so they persist across server restarts during development
let blogPosts = [
  {
    id: "91e1ddac-f51a-4e71-b3ea-8126b8ce0576",
    title: "Welcome to Our Blog",
    content: "This is the first blog post. Welcome to our amazing blog platform!",
    author: "Admin",
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  },
  {
    id: "a2b3c4d5-e6f7-8901-2345-6789abcdef01",
    title: "Getting Started with Express.js",
    content: "Express.js is a minimal and flexible Node.js web application framework that provides a robust set of features for web and mobile applications.",
    author: "John Doe",
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  }
];

// Helper function to find blog post by ID
const findBlogById = (id) => {
  return blogPosts.find(post => post.id === id);
};

// Routes

// GET / - Serve the test interface
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// GET /api/posts - Get all blog posts
app.get('/api/posts', (req, res) => {
  try {
    res.status(200).json({
      success: true,
      count: blogPosts.length,
      data: blogPosts
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Server error',
      error: error.message
    });
  }
});

// GET /api/posts/:id - Get a single blog post by ID
app.get('/api/posts/:id', (req, res) => {
  try {
    const { id } = req.params;
    const post = findBlogById(id);

    if (!post) {
      return res.status(404).json({
        success: false,
        message: 'Blog post not found'
      });
    }

    res.status(200).json({
      success: true,
      data: post
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Server error',
      error: error.message
    });
  }
});

// POST /api/posts - Create a new blog post
app.post('/api/posts', (req, res) => {
  try {
    const { title, content, author } = req.body;

    // Validation
    if (!title || !content || !author) {
      return res.status(400).json({
        success: false,
        message: 'Title, content, and author are required'
      });
    }

    const newPost = {
      id: uuidv4(),
      title: title.trim(),
      content: content.trim(),
      author: author.trim(),
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    };

    blogPosts.push(newPost);

    res.status(201).json({
      success: true,
      message: 'Blog post created successfully',
      data: newPost
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Server error',
      error: error.message
    });
  }
});

// PUT /api/posts/:id - Update a blog post
app.put('/api/posts/:id', (req, res) => {
  try {
    const { id } = req.params;
    const { title, content, author } = req.body;

    const postIndex = blogPosts.findIndex(post => post.id === id);

    if (postIndex === -1) {
      return res.status(404).json({
        success: false,
        message: 'Blog post not found'
      });
    }

    // Validation
    if (!title || !content || !author) {
      return res.status(400).json({
        success: false,
        message: 'Title, content, and author are required'
      });
    }

    // Update the post
    blogPosts[postIndex] = {
      ...blogPosts[postIndex],
      title: title.trim(),
      content: content.trim(),
      author: author.trim(),
      updatedAt: new Date().toISOString()
    };

    res.status(200).json({
      success: true,
      message: 'Blog post updated successfully',
      data: blogPosts[postIndex]
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Server error',
      error: error.message
    });
  }
});

// DELETE /api/posts/:id - Delete a blog post
app.delete('/api/posts/:id', (req, res) => {
  try {
    const { id } = req.params;
    const postIndex = blogPosts.findIndex(post => post.id === id);

    if (postIndex === -1) {
      return res.status(404).json({
        success: false,
        message: 'Blog post not found'
      });
    }

    const deletedPost = blogPosts.splice(postIndex, 1)[0];

    res.status(200).json({
      success: true,
      message: 'Blog post deleted successfully',
      data: deletedPost
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Server error',
      error: error.message
    });
  }
});

// 404 handler for API routes
app.use('/api/*', (req, res) => {
  res.status(404).json({
    success: false,
    message: 'API endpoint not found'
  });
});

// Start server
app.listen(PORT, () => {
  console.log(`🚀 Blog Management API Server running on port ${PORT}`);
  console.log(`📱 Test interface available at: http://localhost:${PORT}`);
  console.log(`🔗 API base URL: http://localhost:${PORT}/api/posts`);
});

module.exports = app;