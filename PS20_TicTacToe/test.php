<?php
echo "<h1>PHP Test</h1>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

if ($_POST) {
    echo "<h2>POST Data Received:</h2>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Test</title>
</head>
<body>
    <h2>Test Form</h2>
    <form method="POST">
        <button type="submit" name="test" value="clicked">Test Button</button>
    </form>
</body>
</html>