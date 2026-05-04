const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const path = require('path');
const db = require('./db');

const app = express();

app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Serve static frontend files
app.use(express.static(path.join(__dirname, 'public')));

// Home Route
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Add Student
app.post('/add-student', (req, res) => {
    const { name, email, course } = req.body;

    const sql = 'INSERT INTO students (name, email, course) VALUES (?, ?, ?)';

    db.query(sql, [name, email, course], (err, result) => {
        if (err) {
            console.log(err);
            res.status(500).send('Error while adding student');
        } else {
            res.send('Student Added Successfully');
        }
    });
});

// Get All Students
app.get('/students', (req, res) => {
    const sql = 'SELECT * FROM students';

    db.query(sql, (err, result) => {
        if (err) {
            console.log(err);
            res.status(500).send('Error while fetching students');
        } else {
            res.json(result);
        }
    });
});

app.listen(3000, () => {
    console.log('Server running on http://localhost:3000');
});