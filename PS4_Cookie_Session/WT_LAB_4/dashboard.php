<?php
session_start();

if(isset($_SESSION['user']))
{
    echo "<h2>Welcome " . $_SESSION['user'] . "</h2>";
    echo "Session ID: " . session_id();
}
else
{
    echo "Session not found. Please login again.";
}


if(isset($_COOKIE['username']))
{
    echo "<br>Cookie Username: " . $_COOKIE['username'];
}

echo "<br><br><a href='logout.php'>Logout</a>";

?>