<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    exit("Unauthorized");
}

$user_id = $_SESSION['student'];
$exam_id = $_POST['exam_id'];
$session_id = $_POST['session_id'];

// Get answers
$sql = "SELECT sa.question_id, sa.answer, q.correct_answer 
        FROM student_answers sa
        JOIN questions q ON sa.question_id = q.id
        WHERE sa.session_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();

$score = 0;
$total = 0;

while ($row = $result->fetch_assoc()) {
    $total++;

    if ($row['answer'] == $row['correct_answer']) {
        $score++;
    }
}

// Save result
$stmt = $conn->prepare("INSERT INTO results (user_id, exam_id, score) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $user_id, $exam_id, $score);
$stmt->execute();

// Update session
$conn->query("UPDATE exam_sessions SET status='submitted' WHERE id=$session_id");

echo json_encode([
    "score" => $score,
    "total" => $total
]);
?>