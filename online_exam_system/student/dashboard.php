<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];

// Fetch exams
$exams = $conn->query("SELECT * FROM exams ORDER BY id DESC");

// Fetch coins
$user = $conn->query("SELECT coins FROM users WHERE id=".$_SESSION['student'])->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>

<style>

/* BODY */
body {
    margin: 0;
    font-family: 'Segoe UI';
    background: radial-gradient(circle at top, #1e293b, #020617);
    color: white;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    padding: 20px;
}

/* TOP CARDS */
.top-cards {
    display: flex;
    gap: 20px;
    padding: 20px;
}

/* CARD BOX */
.card-box {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border-radius: 15px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    text-decoration: none;
    color: white;
    transition: 0.3s;
}

/* ICON */
.icon {
    font-size: 30px;
}

/* HOVER */
.hover:hover {
    transform: translateY(-5px) scale(1.03);
    background: linear-gradient(45deg, #6366f1, #06b6d4);
    box-shadow: 0 0 20px rgba(99,102,241,0.6);
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
    gap: 20px;
    padding: 20px;
}

/* EXAM CARD */
.card {
    padding: 20px;
    border-radius: 15px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

/* STATUS */
.status {
    font-size: 12px;
    margin-top: 5px;
}

.green { color: lime; }
.red { color: red; }
.gray { color: gray; }

/* BUTTON */
.btn {
    margin-top: 10px;
    padding: 10px;
    width: 100%;
    border-radius: 10px;
    border: none;
    background: linear-gradient(45deg, #6366f1, #06b6d4);
    color: white;
    cursor: pointer;
}

.btn:disabled {
    background: gray;
    cursor: not-allowed;
}

</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>Welcome, <?php echo $name; ?> 👋</h2>
    <a href="../logout.php" style="color:white;">Logout</a>
</div>

<!-- TOP CARDS -->
<div class="top-cards">

    <!-- COINS -->
    <div class="card-box">
        <div class="icon">💰</div>
        <div>
            <h2><?php echo $user['coins']; ?></h2>
            <p>Total Coins</p>
        </div>
    </div>

    <!-- LEADERBOARD -->
    <a href="leaderboard.php" class="card-box hover">
        <div class="icon">🏆</div>
        <div>
            <h3>Leaderboard</h3>
            <p>View Rankings</p>
        </div>
    </a>

    <!-- HISTORY -->
    <a href="history.php" class="card-box hover">
        <div class="icon">📜</div>
        <div>
            <h3>My History</h3>
            <p>Performance</p>
        </div>
    </a>

</div>

<!-- EXAMS -->
<div class="grid">

<?php while($row = $exams->fetch_assoc()) {

    $now = date("Y-m-d H:i:s");

    $status = "";
    $disabled = "";

    if ($now < $row['start_time']) {
        $status = "<span class='red'>Not Started</span>";
        $disabled = "disabled";
    } elseif ($now > $row['end_time']) {
        $status = "<span class='gray'>Expired</span>";
        $disabled = "disabled";
    } else {
        $status = "<span class='green'>Available</span>";
    }

?>

<div class="card">
    <h3><?php echo $row['title']; ?></h3>
    <p>Duration: <?php echo $row['duration']; ?> mins</p>

    <p class="status"><?php echo $status; ?></p>

    <button class="btn"
        onclick="startExam(<?php echo $row['id']; ?>)"
        <?php echo $disabled; ?>>
        Start Exam
    </button>
</div>

<?php } ?>

</div>

<script>
function startExam(id){
    window.location.href = "start_exam.php?exam_id=" + id;
}
</script>

</body>
</html>