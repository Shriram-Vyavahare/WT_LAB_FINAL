<?php
require_once 'config.php';

// Destroy session
session_destroy();

// Remove remember me cookie
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, '/');
}

// Redirect to login page
header('Location: login.php?message=logged_out');
exit();
?>