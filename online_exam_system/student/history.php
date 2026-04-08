<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['student'];

$data = $conn->query("
SELECT e.title, r.score, r.submitted_at
FROM results r
JOIN exams e ON r.exam_id = e.id
WHERE r.user_id = $user_id
ORDER BY r.id DESC
");

if (!$data) {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>History</title>

<style>
body {
    background:#020617;
    color:white;
    font-family:'Segoe UI';
    padding:20px;
}

.card {
    background:rgba(255,255,255,0.05);
    padding:20px;
    margin:15px auto;
    border-radius:12px;
    max-width:500px;
}
</style>
</head>

<body>

<h2 style="text-align:center;">📜 My Exam History</h2>

<?php while($row = $data->fetch_assoc()) { ?>

<div class="card">
    <h3><?php echo $row['title']; ?></h3>
    <p>Score: <?php echo $row['score']; ?></p>
    <p>Date: <?php echo $row['submitted_at']; ?></p>
</div>

<?php } ?>

<a href="dashboard.php" style="color:#06b6d4;">⬅ Back</a>

</body>
</html>