<?php
require_once("../config/db.php");

$session_id = $_POST['session_id'];
$event = $_POST['event'];

$stmt = $conn->prepare("INSERT INTO monitor_logs (session_id, event_type) VALUES (?, ?)");
$stmt->bind_param("is", $session_id, $event);
$stmt->execute();
?>