<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php';

$sql = "SELECT * FROM student_results ORDER BY id DESC";
$result = $conn->query($sql);

$records = array();

while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

echo json_encode($records);

$conn->close();
?>