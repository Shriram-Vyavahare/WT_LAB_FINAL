const express = require('express');
const cors = require('cors');
const path = require('path');

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.static('public'));

// In-memory task storage
let tasks = [
  {
    id: 1,
    title: 'Sample Task',
    description: 'This is a sample task',
    status: 'pending',
    createdAt: new Date().toISOString()
  }
];

let nextId = 2;

// API Routes

// GET all tasks
app.get('/api/tasks', (req, res) => {
  res.json({
    success: true,
    count: tasks.length,
    data: tasks
  });
});

// GET single task by ID
app.get('/api/tasks/:id', (req, res) => {
  const task = tasks.find(t => t.id === parseInt(req.params.id));
  
  if (!task) {
    return res.status(404).json({
      success: false,
      message: 'Task not found'
    });
  }
  
  res.json({
    success: true,
    data: task
  });
});

// POST - Create new task
app.post('/api/tasks', (req, res) => {
  const { title, description } = req.body;
  
  if (!title || title.trim() === '') {
    return res.status(400).json({
      success: false,
      message: 'Task title is required'
    });
  }
  
  const newTask = {
    id: nextId++,
    title: title.trim(),
    description: description ? description.trim() : '',
    status: 'pending',
    createdAt: new Date().toISOString()
  };
  
  tasks.push(newTask);
  
  res.status(201).json({
    success: true,
    message: 'Task created successfully',
    data: newTask
  });
});

// PUT - Update task status
app.put('/api/tasks/:id', (req, res) => {
  const taskId = parseInt(req.params.id);
  const { status, title, description } = req.body;
  
  const taskIndex = tasks.findIndex(t => t.id === taskId);
  
  if (taskIndex === -1) {
    return res.status(404).json({
      success: false,
      message: 'Task not found'
    });
  }
  
  // Update task fields
  if (status && (status === 'pending' || status === 'completed')) {
    tasks[taskIndex].status = status;
  }
  
  if (title !== undefined) {
    tasks[taskIndex].title = title.trim();
  }
  
  if (description !== undefined) {
    tasks[taskIndex].description = description.trim();
  }
  
  tasks[taskIndex].updatedAt = new Date().toISOString();
  
  res.json({
    success: true,
    message: 'Task updated successfully',
    data: tasks[taskIndex]
  });
});

// PATCH - Update only task status
app.patch('/api/tasks/:id/status', (req, res) => {
  const taskId = parseInt(req.params.id);
  const { status } = req.body;
  
  if (!status || (status !== 'pending' && status !== 'completed')) {
    return res.status(400).json({
      success: false,
      message: 'Valid status (pending or completed) is required'
    });
  }
  
  const taskIndex = tasks.findIndex(t => t.id === taskId);
  
  if (taskIndex === -1) {
    return res.status(404).json({
      success: false,
      message: 'Task not found'
    });
  }
  
  tasks[taskIndex].status = status;
  tasks[taskIndex].updatedAt = new Date().toISOString();
  
  res.json({
    success: true,
    message: 'Task status updated successfully',
    data: tasks[taskIndex]
  });
});

// DELETE - Delete task
app.delete('/api/tasks/:id', (req, res) => {
  const taskId = parseInt(req.params.id);
  const taskIndex = tasks.findIndex(t => t.id === taskId);
  
  if (taskIndex === -1) {
    return res.status(404).json({
      success: false,
      message: 'Task not found'
    });
  }
  
  const deletedTask = tasks.splice(taskIndex, 1)[0];
  
  res.json({
    success: true,
    message: 'Task deleted successfully',
    data: deletedTask
  });
});

// Serve the frontend
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Start server
app.listen(PORT, () => {
  console.log(`✅ Task Manager API is running on http://localhost:${PORT}`);
  console.log(`📋 API Endpoints:`);
  console.log(`   GET    /api/tasks          - Get all tasks`);
  console.log(`   GET    /api/tasks/:id      - Get single task`);
  console.log(`   POST   /api/tasks          - Create new task`);
  console.log(`   PUT    /api/tasks/:id      - Update task`);
  console.log(`   PATCH  /api/tasks/:id/status - Update task status`);
  console.log(`   DELETE /api/tasks/:id      - Delete task`);
});
