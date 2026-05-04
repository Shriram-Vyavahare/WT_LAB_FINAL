# Blog Management REST API

A complete REST API for blog management built with Express.js, featuring in-memory storage and a web-based testing interface.

## Features

- ✅ Full CRUD operations for blog posts
- ✅ In-memory storage (array-based)
- ✅ JSON responses for all endpoints
- ✅ Input validation and error handling
- ✅ Professional web interface for API testing
- ✅ Responsive design
- ✅ Real-time post display

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/posts` | Get all blog posts |
| GET | `/api/posts/:id` | Get a single blog post by ID |
| POST | `/api/posts` | Create a new blog post |
| PUT | `/api/posts/:id` | Update an existing blog post |
| DELETE | `/api/posts/:id` | Delete a blog post |

## Installation & Setup

1. **Install dependencies:**
   ```bash
   npm install
   ```

2. **Start the server:**
   ```bash
   npm start
   ```
   
   Or for development with auto-restart:
   ```bash
   npm run dev
   ```

3. **Access the application:**
   - API Base URL: `http://localhost:3000/api/posts`
   - Web Interface: `http://localhost:3000`

## API Usage Examples

### Get All Posts
```bash
GET http://localhost:3000/api/posts
```

### Get Single Post
```bash
GET http://localhost:3000/api/posts/{post-id}
```

### Create New Post
```bash
POST http://localhost:3000/api/posts
Content-Type: application/json

{
  "title": "My Blog Post",
  "content": "This is the content of my blog post.",
  "author": "John Doe"
}
```

### Update Post
```bash
PUT http://localhost:3000/api/posts/{post-id}
Content-Type: application/json

{
  "title": "Updated Title",
  "content": "Updated content.",
  "author": "Jane Doe"
}
```

### Delete Post
```bash
DELETE http://localhost:3000/api/posts/{post-id}
```

## Response Format

All API responses follow this structure:

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": { ... },
  "count": 2  // Only for GET all posts
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message"
}
```

## Blog Post Schema

```json
{
  "id": "uuid-string",
  "title": "string",
  "content": "string",
  "author": "string",
  "createdAt": "ISO-8601-datetime",
  "updatedAt": "ISO-8601-datetime"
}
```

## Web Interface Features

The included web interface (`http://localhost:3000`) provides:

- **Visual API Testing**: Test all endpoints with buttons and forms
- **Real-time Response Display**: See API responses with status codes and timing
- **Post Management**: View all current posts with metadata
- **Form Validation**: Client-side validation for required fields
- **Responsive Design**: Works on desktop and mobile devices
- **Keyboard Shortcuts**: Ctrl/Cmd + Enter to submit forms
- **ID Copy Feature**: Click on post IDs to copy them

## Project Structure

```
blog-management-api/
├── server.js          # Main Express server
├── package.json       # Dependencies and scripts
├── README.md         # This file
└── public/           # Static files for web interface
    ├── index.html    # Main HTML page
    ├── styles.css    # Styling
    └── script.js     # Client-side JavaScript
```

## Development Notes

- **In-Memory Storage**: Data is stored in a JavaScript array and will be lost when the server restarts
- **UUID Generation**: Each post gets a unique UUID for identification
- **CORS Enabled**: Cross-origin requests are allowed
- **Input Validation**: Server validates required fields (title, content, author)
- **Error Handling**: Comprehensive error handling with appropriate HTTP status codes

## Testing with Postman

If you prefer using Postman instead of the web interface:

1. Import the following base URL: `http://localhost:3000/api/posts`
2. Set Content-Type header to `application/json` for POST/PUT requests
3. Use the API examples above for request bodies

## Future Enhancements

- Database integration (MongoDB, PostgreSQL, etc.)
- User authentication and authorization
- Image upload for blog posts
- Search and filtering capabilities
- Pagination for large datasets
- Rate limiting and security middleware

## License

MIT License - feel free to use this project for learning and development purposes.