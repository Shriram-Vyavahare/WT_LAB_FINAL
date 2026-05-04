<?php
session_start();

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

echo "<h2>Form Data Received</h2>";
echo "Name: " . $name . "<br>";
echo "Email: " . $email . "<br>";


if(filter_var($email, FILTER_VALIDATE_EMAIL))
{
    echo "Email format is VALID <br>";
}
else
{
    echo "Invalid Email Format";
    exit();
}


setcookie("username", $name, time()+3600); // cookie valid for 1 hour


$_SESSION['user'] = $name;

echo "<br>Cookie and Session Created Successfully<br>";

echo "<br><a href='dashboard.php'>Go to Dashboard</a>";

?>