<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    exit("Unauthorized");
}

$user_id = $_SESSION['student'];

$session_id = $_POST['session_id'];
$question_id = $_POST['question_id'];
$answer = $_POST['answer'];

// Check if already exists
$check = $conn->prepare("SELECT id FROM student_answers WHERE session_id=? AND question_id=?");
$check->bind_param("ii", $session_id, $question_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    // Update
    $stmt = $conn->prepare("UPDATE student_answers SET answer=? WHERE session_id=? AND question_id=?");
    $stmt->bind_param("sii", $answer, $session_id, $question_id);
} else {
    // Insert
    $stmt = $conn->prepare("INSERT INTO student_answers (session_id, question_id, answer) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $session_id, $question_id, $answer);
}

$stmt->execute();
echo "saved";
?>