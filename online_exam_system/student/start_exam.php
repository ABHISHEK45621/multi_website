<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['exam_id'])) {
    die("Exam ID missing!");
}

$user_id = $_SESSION['student'];
$exam_id = $_GET['exam_id'];

// Check if session already exists
$check = $conn->prepare("
    SELECT id FROM exam_sessions 
    WHERE user_id=? AND exam_id=? AND status='writing'
");
$check->bind_param("ii", $user_id, $exam_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    // Resume session
    $row = $res->fetch_assoc();
    $session_id = $row['id'];
} else {
    // Create new session
    $stmt = $conn->prepare("
        INSERT INTO exam_sessions (user_id, exam_id, start_time, status)
        VALUES (?, ?, NOW(), 'writing')
    ");
    $stmt->bind_param("ii", $user_id, $exam_id);
    $stmt->execute();

    $session_id = $conn->insert_id;
}

// Redirect to exam page with session
header("Location: exam.php?exam_id=$exam_id&session_id=$session_id");
exit();
?>