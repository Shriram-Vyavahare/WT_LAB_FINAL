const mysql = require('mysql2');

const connection = mysql.createConnection({
    host: 'localhost',
    port: 3307,  // Updated to match your MySQL port
    user: 'root',
    password: '',  // Try empty password - UPDATE THIS with your actual MySQL password
    database: 'studentdb'
});

connection.connect((err) => {
    if (err) {
        console.log('Database connection failed');
        console.log(err);
    } else {
        console.log('Connected to MySQL Database');
    }
});

module.exports = connection;