const mysql = require('mysql2');

const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'shree',
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