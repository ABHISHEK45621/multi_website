<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['student'];

// Get latest session
$session = $conn->query("SELECT * FROM exam_sessions WHERE user_id=$user_id ORDER BY id DESC LIMIT 1")->fetch_assoc();
$session_id = $session['id'];

$sql = "SELECT q.*, sa.answer 
        FROM questions q
        LEFT JOIN student_answers sa 
        ON q.id = sa.question_id AND sa.session_id = $session_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Review</title>

<style>
body {
    background: #020617;
    color: white;
    font-family: 'Segoe UI';
    padding: 20px;
}

.card {
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 10px;
    background: rgba(255,255,255,0.05);
}

.correct {
    color: lime;
}

.wrong {
    color: red;
}
</style>
</head>
<body>

<h2>📜 Answer Review</h2>

<?php while($q = $result->fetch_assoc()) { ?>

<div class="card">
    <h3><?php echo $q['question']; ?></h3>

    <p>Your Answer: 
        <span class="<?php echo ($q['answer'] == $q['correct_answer']) ? 'correct' : 'wrong'; ?>">
            <?php echo $q['answer'] ?? 'Not Answered'; ?>
        </span>
    </p>

    <p>Correct Answer: <span class="correct"><?php echo $q['correct_answer']; ?></span></p>
</div>

<?php } ?>

</body>
</html>