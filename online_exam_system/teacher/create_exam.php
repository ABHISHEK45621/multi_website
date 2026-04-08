<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $duration = intval($_POST['duration']);
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $teacher_id = $_SESSION['teacher'];

    // Validation
    if (empty($title) || empty($duration) || empty($start_time) || empty($end_time)) {
        $error = "All fields are required!";
    } elseif ($start_time >= $end_time) {
        $error = "End time must be greater than start time!";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO exams (title, duration, created_by, start_time, end_time)
            VALUES (?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            $error = "Prepare failed: " . $conn->error;
        } else {

            $stmt->bind_param("siiss", $title, $duration, $teacher_id, $start_time, $end_time);

            if ($stmt->execute()) {
                $exam_id = $conn->insert_id;

                // Redirect to add questions
                header("Location: add_questions.php?exam_id=" . $exam_id);
                exit();
            } else {
                $error = "Database Error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Exam</title>

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
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.card {
    width: 400px;
    padding: 30px;
    border-radius: 20px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
}

/* INPUT */
.input-group {
    margin-bottom: 15px;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: rgba(255,255,255,0.08);
    color: white;
}

/* BUTTON */
.btn {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(45deg, #6366f1, #06b6d4);
    color: white;
    cursor: pointer;
}

/* MESSAGE */
.error {
    color: #ff4d4d;
    margin-bottom: 10px;
}
.success {
    color: lime;
    margin-bottom: 10px;
}

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

<div class="card">

    <h2>Create Exam</h2>

    <?php if ($error) { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>

    <form method="POST">

        <div class="input-group">
            <input type="text" name="title" placeholder="Exam Title" required>
        </div>

        <div class="input-group">
            <input type="number" name="duration" placeholder="Duration (minutes)" required>
        </div>

        <div class="input-group">
            <input type="datetime-local" name="start_time" required>
        </div>

        <div class="input-group">
            <input type="datetime-local" name="end_time" required>
        </div>

        <button class="btn">Create Exam</button>

    </form>

</div>

</div>

</body>
</html>