<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$exam_id = $_GET['exam_id'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $question = $_POST['question'];
    $type = $_POST['type'];
    $correct = $_POST['correct_answer'];

    $a = $_POST['option_a'] ?? null;
    $b = $_POST['option_b'] ?? null;
    $c = $_POST['option_c'] ?? null;
    $d = $_POST['option_d'] ?? null;

    $stmt = $conn->prepare("
    INSERT INTO questions 
    (exam_id, question, type, option_a, option_b, option_c, option_d, correct_answer)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("isssssss", $exam_id, $question, $type, $a, $b, $c, $d, $correct);
    $stmt->execute();

    header("Location: add_questions.php?exam_id=".$exam_id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Questions</title>

<style>
body {
    margin:0;
    font-family:Segoe UI;
    background:#020617;
    color:white;
}

.container {
    width:500px;
    margin:auto;
    margin-top:50px;
    background:rgba(255,255,255,0.05);
    padding:20px;
    border-radius:12px;
}

input, textarea, select {
    width:100%;
    margin:10px 0;
    padding:10px;
    border:none;
    border-radius:8px;
}

.btn {
    width:100%;
    padding:10px;
    background:#06b6d4;
    border:none;
    color:white;
    cursor:pointer;
}

.nav {
    display:flex;
    justify-content:space-between;
    margin-top:10px;
}
</style>
</head>

<body>

<div class="container">

<h2>Add Question</h2>

<form method="POST" id="qForm">

<textarea name="question" placeholder="Enter question" required></textarea>

<select name="type" id="type" onchange="toggleOptions()">
    <option value="mcq">MCQ</option>
    <option value="short">Short Answer</option>
    <option value="long">Long Answer</option>
</select>

<div id="options">
    <input name="option_a" placeholder="Option A">
    <input name="option_b" placeholder="Option B">
    <input name="option_c" placeholder="Option C">
    <input name="option_d" placeholder="Option D">
</div>

<input name="correct_answer" placeholder="Correct Answer" required>

<button type="submit" class="btn">Save Question</button>

</form>

<div class="nav">
    <button onclick="prevQ()">⬅ Previous</button>
    <button onclick="nextQ()">Next ➡</button>
</div>

<a href="dashboard.php" style="color:#06b6d4;">Finish</a>

</div>

<script>

// PREVENT ENTER SUBMIT
document.getElementById("qForm").addEventListener("keypress", function(e){
    if(e.key === "Enter") e.preventDefault();
});

// TOGGLE OPTIONS
function toggleOptions(){
    let type = document.getElementById("type").value;
    document.getElementById("options").style.display = (type=="mcq") ? "block" : "none";
}

// NAVIGATION (UI only)
let index = 0;

function nextQ(){
    alert("Next Question Form (UI only, data saved per submit)");
}

function prevQ(){
    alert("Previous Question (You can manage from DB if needed)");
}

</script>

</body>
</html>