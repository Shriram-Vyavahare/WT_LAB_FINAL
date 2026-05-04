<?php
session_start();

$sessionsFile = "sessions.txt";
$sessions = file($sessionsFile, FILE_IGNORE_NEW_LINES);

$currentSid = session_id();
$newSessions = [];

foreach ($sessions as $line) {
    list($sid, $time) = explode("|", $line);

    if ($sid != $currentSid) {
        $newSessions[] = $line;
    }
}

file_put_contents($sessionsFile, implode("\n", $newSessions));

session_destroy();

echo "Logged out. <a href='index.php'>Login again</a>";