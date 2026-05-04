<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Session timeout (5 min)
if (isset($_SESSION["last_activity"])) {
    if (time() - $_SESSION["last_activity"] > 300) {
        session_destroy();
        echo "Session expired. <a href='index.php'>Login again</a>";
        exit();
    }
}

$_SESSION["last_activity"] = time();
?>

<h2>Welcome User</h2>
<p>Session Active</p>

<a href="logout.php">Logout</a>