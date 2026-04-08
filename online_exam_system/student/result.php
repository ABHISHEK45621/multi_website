<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['student'];

$result = $conn->query("SELECT * FROM results WHERE user_id=$user_id ORDER BY id DESC LIMIT 1");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Result</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI';
    background: #020617;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.card {
    padding: 30px;
    border-radius: 15px;
    background: rgba(255,255,255,0.05);
    text-align: center;
}
h1 {
    font-size: 40px;
}
</style>

</head>
<body>

<div class="card">
    <h2>🎉 Exam Completed</h2>
    <h1><?php echo $data['score']; ?></h1>
    <p>Your Score</p>

    <a href="dashboard.php" style="color:white;">Go to Dashboard</a>
</div>

</body>
</html>