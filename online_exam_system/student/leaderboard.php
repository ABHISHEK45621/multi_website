<?php
session_start();
require_once("../config/db.php");

$data = $conn->query("
SELECT id, name, coins 
FROM users 
WHERE role='student'
ORDER BY coins DESC
LIMIT 10
");

$rows = [];
while($r = $data->fetch_assoc()) $rows[] = $r;

$current_user = $_SESSION['student'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Leaderboard</title>

<style>

body {
    margin: 0;
    font-family: 'Segoe UI';
    background: radial-gradient(circle at top, #1e293b, #020617);
    color: white;
    text-align: center;
}

/* TITLE */
h1 {
    margin-top: 20px;
}

/* TOP PLAYER CARD */
.top-player {
    width: 260px;
    margin: 40px auto;
    padding: 30px;
    border-radius: 20px;
    background: linear-gradient(135deg, #ffd700, #ffb300);
    color: black;
    box-shadow: 0 0 30px rgba(255,215,0,0.6);
    animation: glow 2s infinite alternate;
}

@keyframes glow {
    from { box-shadow: 0 0 20px rgba(255,215,0,0.5); }
    to { box-shadow: 0 0 40px rgba(255,215,0,1); }
}

/* NAME */
.top-player h2 {
    margin: 10px 0;
}

/* LIST */
.list {
    max-width: 600px;
    margin: auto;
}

/* ROW */
.row {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    margin: 10px 0;
    border-radius: 12px;
    background: rgba(255,255,255,0.05);
    transition: 0.3s;
}

.row:hover {
    transform: scale(1.02);
    background: linear-gradient(45deg, #6366f1, #06b6d4);
}

/* YOU HIGHLIGHT */
.you {
    border: 2px solid #06b6d4;
}

/* BADGE */
.badge {
    font-weight: bold;
}

/* BACK */
.back {
    margin-top: 30px;
    display: inline-block;
    color: #06b6d4;
    text-decoration: none;
}

</style>
</head>

<body>

<h1>🏆 Leaderboard</h1>

<!-- TOP PLAYER -->
<?php if(isset($rows[0])) { ?>
<div class="top-player">
    🥇 Top Performer
    <h2><?php echo $rows[0]['name']; ?></h2>
    <p>💰 <?php echo $rows[0]['coins']; ?> Coins</p>
</div>
<?php } ?>

<!-- LIST -->
<div class="list">

<?php for($i=1; $i<count($rows); $i++){ 

$class = ($rows[$i]['id'] == $current_user) ? "row you" : "row";

$badge = "";
if($i==1) $badge="🥈";
if($i==2) $badge="🥉";
?>

<div class="<?php echo $class; ?>">
    <span><?php echo $badge; ?> #<?php echo $i+1; ?> <?php echo $rows[$i]['name']; ?></span>
    <span>💰 <?php echo $rows[$i]['coins']; ?></span>
</div>

<?php } ?>

</div>

<a href="dashboard.php" class="back">⬅ Back to Dashboard</a>

</body>
</html>