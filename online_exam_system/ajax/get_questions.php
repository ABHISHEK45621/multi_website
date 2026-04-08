<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

if (!isset($_GET['exam_id'])) {
    echo json_encode(["error" => "Exam ID missing"]);
    exit();
}

$exam_id = $_GET['exam_id'];

// Fetch randomized questions
$stmt = $conn->prepare("
    SELECT id, question, type, option_a, option_b, option_c, option_d 
    FROM questions 
    WHERE exam_id=? 
    ORDER BY RAND()
");

$stmt->bind_param("i", $exam_id);
$stmt->execute();

$result = $stmt->get_result();

$questions = [];

while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

// Return JSON
echo json_encode($questions);
?>