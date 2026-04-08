<?php 
$conn = new mysqli("localhost", "root", "", "online_exam_system", 3310);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>