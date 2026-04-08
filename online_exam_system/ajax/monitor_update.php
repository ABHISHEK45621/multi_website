<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['teacher'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$sql = "
SELECT 
    es.id,
    u.name AS student_name,
    e.title AS exam_name,
    es.status,
    es.start_time,
    es.tab_switches,
    (
        SELECT MAX(event_time) 
        FROM monitor_logs 
        WHERE session_id = es.id
    ) AS last_activity
FROM exam_sessions es
JOIN users u ON es.user_id = u.id
JOIN exams e ON es.exam_id = e.id
ORDER BY es.id DESC
";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>