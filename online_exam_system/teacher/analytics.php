<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

// Fetch analytics data
$data = $conn->query("
SELECT 
    u.name,
    r.exam_id,
    r.score,
    COUNT(q.id) AS total_questions,
    ROUND((r.score / COUNT(q.id)) * 100, 2) AS accuracy
FROM results r
JOIN users u ON r.user_id = u.id
JOIN questions q ON r.exam_id = q.exam_id
GROUP BY r.id
ORDER BY r.score DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Analytics Dashboard</title>

<style>

/* BODY */
body {
    margin: 0;
    font-family: 'Segoe UI';
    background: radial-gradient(circle at top, #1e293b, #020617);
    color: white;
    display: flex;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    height: 100vh;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    padding: 20px;
}

.sidebar a {
    display: block;
    margin: 10px 0;
    padding: 10px;
    color: white;
    text-decoration: none;
    border-radius: 10px;
}

.sidebar a:hover {
    background: linear-gradient(45deg, #6366f1, #06b6d4);
}

/* MAIN */
.main {
    flex: 1;
    padding: 20px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: rgba(255,255,255,0.05);
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #444;
}

th {
    background: rgba(255,255,255,0.1);
}

/* HIGHLIGHT TOP */
.top1 { color: gold; font-weight: bold; }
.top2 { color: silver; }
.top3 { color: #cd7f32; }

</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>👨‍🏫 Teacher</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="monitor.php">Monitor</a>
    <a href="analytics.php">Analytics</a>
    <a href="../logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>📊 Performance Analytics</h2>

<table>
<tr>
    <th>Rank</th>
    <th>Student</th>
    <th>Score</th>
    <th>Total Questions</th>
    <th>Accuracy %</th>
</tr>

<?php 
$rank = 1;
while($row = $data->fetch_assoc()) { 

$class = "";
if ($rank == 1) $class = "top1";
if ($rank == 2) $class = "top2";
if ($rank == 3) $class = "top3";
?>

<tr class="<?php echo $class; ?>">
    <td>#<?php echo $rank; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['score']; ?></td>
    <td><?php echo $row['total_questions']; ?></td>
    <td><?php echo $row['accuracy']; ?>%</td>
</tr>

<?php $rank++; } ?>

</table>

</div>

</body>
</html>