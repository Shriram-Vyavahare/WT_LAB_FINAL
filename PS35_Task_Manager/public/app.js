const API_URL = 'http://localhost:3000/api/tasks';

let currentFilter = 'all';

// DOM Elements
const taskForm = document.getElementById('taskForm');
const taskTitle = document.getElementById('taskTitle');
const taskDescription = document.getElementById('taskDescription');
const tasksList = document.getElementById('tasksList');
const emptyState = document.getElementById('emptyState');
const filterTabs = document.querySelectorAll('.filter-tab');

// Stats elements
const totalTasksEl = document.getElementById('totalTasks');
const pendingTasksEl = document.getElementById('pendingTasks');
const completedTasksEl = document.getElementById('completedTasks');

// Initialize app
document.addEventListener('DOMContentLoaded', () => {
    loadTasks();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    taskForm.addEventListener('submit', handleAddTask);
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            filterTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentFilter = tab.dataset.filter;
            loadTasks();
        });
    });
}

// Load tasks from API
async function loadTasks() {
    try {
        const response = await fetch(API_URL);
        const result = await response.json();
        
        if (result.success) {
            displayTasks(result.data);
            updateStats(result.data);
        }
    } catch (error) {
        showToast('Error loading tasks', 'error');
        console.error('Error:', error);
    }
}

// Display tasks
function displayTasks(tasks) {
    // Filter tasks based on current filter
    let filteredTasks = tasks;
    if (currentFilter === 'pending') {
        filteredTasks = tasks.filter(task => task.status === 'pending');
    } else if (currentFilter === 'completed') {
        filteredTasks = tasks.filter(task => task.status === 'completed');
    }
    
    // Show empty state if no tasks
    if (filteredTasks.length === 0) {
        tasksList.innerHTML = '';
        emptyState.classList.add('show');
        return;
    }
    
    emptyState.classList.remove('show');
    
    // Render tasks
    tasksList.innerHTML = filteredTasks.map(task => createTaskCard(task)).join('');
    
    // Add event listeners to task buttons
    attachTaskEventListeners();
}

// Create task card HTML
function createTaskCard(task) {
    const date = new Date(task.createdAt).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    return `
        <div class="task-card ${task.status}" data-task-id="${task.id}">
            <div class="task-content">
                <div class="task-header">
                    <span class="task-status-badge ${task.status}">
                        ${task.status === 'completed' ? '✓ Completed' : '⏱ Pending'}
                    </span>
                </div>
                <h3 class="task-title">${escapeHtml(task.title)}</h3>
                ${task.description ? `<p class="task-description">${escapeHtml(task.description)}</p>` : ''}
                <div class="task-meta">
                    <i class="fas fa-calendar"></i>
                    <span>${date}</span>
                </div>
            </div>
            <div class="task-actions">
                ${task.status === 'pending' 
                    ? `<button class="btn btn-success btn-small complete-btn" data-id="${task.id}">
                        <i class="fas fa-check"></i> Complete
                       </button>`
                    : `<button class="btn btn-primary btn-small pending-btn" data-id="${task.id}">
                        <i class="fas fa-undo"></i> Reopen
                       </button>`
                }
                <button class="btn btn-danger btn-small delete-btn" data-id="${task.id}">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    `;
}

// Attach event listeners to task buttons
function attachTaskEventListeners() {
    // Complete buttons
    document.querySelectorAll('.complete-btn').forEach(btn => {
        btn.addEventListener('click', () => updateTaskStatus(btn.dataset.id, 'completed'));
    });
    
    // Pending buttons
    document.querySelectorAll('.pending-btn').forEach(btn => {
        btn.addEventListener('click', () => updateTaskStatus(btn.dataset.id, 'pending'));
    });
    
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => deleteTask(btn.dataset.id));
    });
}

// Handle add task form submission
async function handleAddTask(e) {
    e.preventDefault();
    
    const title = taskTitle.value.trim();
    const description = taskDescription.value.trim();
    
    if (!title) {
        showToast('Please enter a task title', 'error');
        return;
    }
    
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ title, description })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Task added successfully!', 'success');
            taskForm.reset();
            loadTasks();
        } else {
            showToast(result.message || 'Error adding task', 'error');
        }
    } catch (error) {
        showToast('Error adding task', 'error');
        console.error('Error:', error);
    }
}

// Update task status
async function updateTaskStatus(taskId, status) {
    try {
        const response = await fetch(`${API_URL}/${taskId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(`Task marked as ${status}!`, 'success');
            loadTasks();
        } else {
            showToast(result.message || 'Error updating task', 'error');
        }
    } catch (error) {
        showToast('Error updating task', 'error');
        console.error('Error:', error);
    }
}

// Delete task
async function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_URL}/${taskId}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Task deleted successfully!', 'success');
            loadTasks();
        } else {
            showToast(result.message || 'Error deleting task', 'error');
        }
    } catch (error) {
        showToast('Error deleting task', 'error');
        console.error('Error:', error);
    }
}

// Update statistics
function updateStats(tasks) {
    const total = tasks.length;
    const pending = tasks.filter(t => t.status === 'pending').length;
    const completed = tasks.filter(t => t.status === 'completed').length;
    
    totalTasksEl.textContent = total;
    pendingTasksEl.textContent = pending;
    completedTasksEl.textContent = completed;
}

// Show toast notification
function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
