# Task Manager REST API

A professional Task Manager application built with Express.js REST API and a modern, responsive UI.

## Features

✅ **Complete REST API**
- Create new tasks (POST)
- Retrieve all tasks (GET)
- Retrieve single task by ID (GET)
- Update task status - pending/completed (PUT/PATCH)
- Delete tasks (DELETE)
- JSON response format

✅ **Professional UI**
- Modern, gradient design
- Real-time statistics dashboard
- Filter tasks (All, Pending, Completed)
- Responsive design for mobile and desktop
- Toast notifications
- Smooth animations

## Installation

1. Install dependencies:
```bash
npm install
```

2. Start the server:
```bash
npm start
```

3. Open your browser and navigate to:
```
http://localhost:3000
```

## API Endpoints

### Get All Tasks
```
GET /api/tasks
```

### Get Single Task
```
GET /api/tasks/:id
```

### Create New Task
```
POST /api/tasks
Content-Type: application/json

{
  "title": "Task title",
  "description": "Task description (optional)"
}
```

### Update Task
```
PUT /api/tasks/:id
Content-Type: application/json

{
  "title": "Updated title",
  "description": "Updated description",
  "status": "completed"
}
```

### Update Task Status Only
```
PATCH /api/tasks/:id/status
Content-Type: application/json

{
  "status": "completed"
}
```

### Delete Task
```
DELETE /api/tasks/:id
```

## API Response Format

All API responses follow this format:

```json
{
  "success": true,
  "message": "Operation message",
  "data": { /* task data */ }
}
```

## Task Object Structure

```json
{
  "id": 1,
  "title": "Task title",
  "description": "Task description",
  "status": "pending",
  "createdAt": "2026-05-04T10:30:00.000Z",
  "updatedAt": "2026-05-04T11:00:00.000Z"
}
```

## Technologies Used

- **Backend**: Node.js, Express.js
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Icons**: Font Awesome 6
- **Storage**: In-memory (can be easily extended to use a database)

## Project Structure

```
task-manager/
├── public/
│   ├── index.html      # Main HTML file
│   ├── styles.css      # Styling
│   └── app.js          # Frontend JavaScript
├── server.js           # Express server & API routes
├── package.json        # Dependencies
└── README.md          # Documentation
```

## Features Implemented

1. ✅ Create API routes for adding tasks
2. ✅ Retrieve all tasks using GET requests
3. ✅ Update task status (completed or pending)
4. ✅ Delete tasks when completed
5. ✅ Return task data in JSON format
6. ✅ Professional, modern UI

## Future Enhancements

- Add database integration (MongoDB, PostgreSQL)
- User authentication
- Task categories/tags
- Due dates and reminders
- Search functionality
- Task priority levels
- Export tasks to CSV/PDF

## License

MIT License
