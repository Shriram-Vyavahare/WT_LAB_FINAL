<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["message" => "No data received"]);
    exit();
}

$student_name = $data['student_name'];
$course = $data['course'];
$prn = $data['prn'];

$subject1 = $data['subjects'][0]['name'];
$mse1 = $data['subjects'][0]['mse'];
$ese1 = $data['subjects'][0]['ese'];
$total1 = $mse1 + $ese1;
$status1 = $total1 >= 40 ? "Pass" : "Fail";

$subject2 = $data['subjects'][1]['name'];
$mse2 = $data['subjects'][1]['mse'];
$ese2 = $data['subjects'][1]['ese'];
$total2 = $mse2 + $ese2;
$status2 = $total2 >= 40 ? "Pass" : "Fail";

$subject3 = $data['subjects'][2]['name'];
$mse3 = $data['subjects'][2]['mse'];
$ese3 = $data['subjects'][2]['ese'];
$total3 = $mse3 + $ese3;
$status3 = $total3 >= 40 ? "Pass" : "Fail";

$subject4 = $data['subjects'][3]['name'];
$mse4 = $data['subjects'][3]['mse'];
$ese4 = $data['subjects'][3]['ese'];
$total4 = $mse4 + $ese4;
$status4 = $total4 >= 40 ? "Pass" : "Fail";

$sql = "INSERT INTO student_results (
    student_name, course, prn,
    subject1, mse1, ese1, total1, status1,
    subject2, mse2, ese2, total2, status2,
    subject3, mse3, ese3, total3, status3,
    subject4, mse4, ese4, total4, status4
) VALUES (
    '$student_name', '$course', '$prn',
    '$subject1', '$mse1', '$ese1', '$total1', '$status1',
    '$subject2', '$mse2', '$ese2', '$total2', '$status2',
    '$subject3', '$mse3', '$ese3', '$total3', '$status3',
    '$subject4', '$mse4', '$ese4', '$total4', '$status4'
)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Result saved successfully"]);
} else {
    echo json_encode([
        "message" => "Database Error",
        "error" => $conn->error
    ]);
}

$conn->close();
?>