<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$exam_id = $_GET['exam_id'];
$session_id = $_GET['session_id'];
$user_id = $_SESSION['student'];

// Get exam
$exam = $conn->query("SELECT * FROM exams WHERE id=$exam_id")->fetch_assoc();

// Get session start time
$session = $conn->query("SELECT * FROM exam_sessions WHERE id=$session_id")->fetch_assoc();
$start_time = strtotime($session['start_time']);

// Fetch questions
$questions = $conn->query("SELECT * FROM questions WHERE exam_id=$exam_id ORDER BY RAND()");
?>

<!DOCTYPE html>
<html>
<head>
<title>Exam</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI';
    background: #020617;
    color: white;
}

/* TOPBAR */
.topbar {
    padding: 15px;
    background: #1e293b;
    display: flex;
    justify-content: space-between;
}

/* LAYOUT */
.container {
    display: flex;
}

/* MAIN */
.main {
    flex: 3;
    padding: 20px;
}

/* NAV */
.nav {
    flex: 1;
    background: #0f172a;
    padding: 20px;
}

/* NAV BUTTON */
.qbtn {
    width: 40px;
    height: 40px;
    margin: 5px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

/* COLORS */
.answered { background: green; }
.current { background: blue; }
.notanswered { background: red; }
</style>

</head>
<body>

<div class="topbar">
    <h3><?php echo $exam['title']; ?></h3>
    <div>⏱ Time Left: <span id="timer"></span></div>
</div>

<div class="container">

<!-- QUESTIONS -->
<div class="main">

<?php
$i = 0;
while($q = $questions->fetch_assoc()) {
?>

<div class="question" id="q<?php echo $i; ?>" style="display:<?php echo $i==0?'block':'none'; ?>">

    <h3>Q<?php echo $i+1; ?>. <?php echo $q['question']; ?></h3>

    <?php if ($q['type']=='mcq') { ?>

        <?php foreach(['A','B','C','D'] as $opt) { ?>
            <label>
                <input type="radio"
                       name="q<?php echo $q['id']; ?>"
                       value="<?php echo $opt; ?>"
                       onchange="saveAnswer(<?php echo $q['id']; ?>, this.value, <?php echo $i; ?>)">
                <?php echo $q['option_'.strtolower($opt)]; ?>
            </label><br>
        <?php } ?>

    <?php } else { ?>

        <textarea onchange="saveAnswer(<?php echo $q['id']; ?>, this.value, <?php echo $i; ?>)"></textarea>

    <?php } ?>

</div>

<?php $i++; } ?>

</div>

<!-- NAVIGATION -->
<div class="nav">

<h3>Questions</h3>

<?php for($j=0;$j<$i;$j++){ ?>
    <button class="qbtn notanswered" id="btn<?php echo $j; ?>"
        onclick="showQ(<?php echo $j; ?>)">
        <?php echo $j+1; ?>
    </button>
<?php } ?>

<br><br>
<button onclick="submitExam()">Submit</button>

</div>

</div>

<script>

// TIMER SYNC
let duration = <?php echo $exam['duration']; ?> * 60;
let start = <?php echo $start_time; ?> * 1000;

function updateTimer(){
    let now = Date.now();
    let elapsed = Math.floor((now - start)/1000);
    let remaining = duration - elapsed;

    if (remaining <= 0) submitExam();

    let min = Math.floor(remaining/60);
    let sec = remaining % 60;

    document.getElementById("timer").innerText = min + ":" + sec;
}

setInterval(updateTimer,1000);

// NAVIGATION
function showQ(i){
    document.querySelectorAll(".question").forEach(q => q.style.display="none");
    document.getElementById("q"+i).style.display="block";

    document.querySelectorAll(".qbtn").forEach(b=>b.classList.remove("current"));
    document.getElementById("btn"+i).classList.add("current");
}

// SAVE ANSWER
function saveAnswer(qid, ans, index){

    fetch("../ajax/autosave.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`session_id=<?php echo $session_id; ?>&question_id=${qid}&answer=${encodeURIComponent(ans)}`
    });

    let btn = document.getElementById("btn"+index);
    btn.classList.remove("notanswered");
    btn.classList.add("answered");
}

// SUBMIT
function submitExam(){

    if (!confirm("Submit exam?")) return;

    fetch("submit_exam.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`exam_id=<?php echo $exam_id; ?>&session_id=<?php echo $session_id; ?>`
    })
    .then(res=>res.json())
    .then(data=>{
        window.location.href="result.php";
    });
}

</script>

<!-- ANTI CHEAT -->
<script src="../assets/js/anti_cheat.js"></script>

</body>
</html>