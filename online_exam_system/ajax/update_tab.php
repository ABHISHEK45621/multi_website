<?php
require_once("../config/db.php");

$session_id = $_POST['session_id'];

$conn->query("UPDATE exam_sessions 
SET tab_switches = tab_switches + 1 
WHERE id = $session_id");
?>