<?php
session_start();

$user = "student"; // dummy user

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sessionsFile = "sessions.txt";
    $sessions = file_exists($sessionsFile) ? file($sessionsFile, FILE_IGNORE_NEW_LINES) : [];

    $currentTime = time();
    $validSessions = [];

    // Remove expired sessions (5 minutes = 300 sec)
    foreach ($sessions as $line) {
        list($sid, $timestamp) = explode("|", $line);

        if ($currentTime - $timestamp < 300) {
            $validSessions[] = $line;
        }
    }

    // Limit check (max 3 sessions)
    if (count($validSessions) >= 3) {
        echo "❌ Maximum 3 sessions allowed. Try later.";
        exit();
    }

    // Create new session
    $_SESSION["user"] = $user;
    $sid = session_id();

    $validSessions[] = $sid . "|" . $currentTime;

    file_put_contents($sessionsFile, implode("\n", $validSessions));

    header("Location: dashboard.php");
    exit();
}
?>

<form method="post">
    <button type="submit">Login</button>
</form>