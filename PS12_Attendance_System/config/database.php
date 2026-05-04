<?php
$host = 'localhost';
$port = '3307';
$dbname = 'attendance_system';
$username = 'root';
$password = '';

$conn = mysqli_connect($host.':'.$port, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>