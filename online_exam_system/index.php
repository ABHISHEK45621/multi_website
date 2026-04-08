<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Advanced Online Exam System</title>

<link rel="stylesheet" href="assets/css/style.css">

<style>

/* ================= BACKGROUND ================= */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: radial-gradient(circle at top, #1e293b, #020617);
    overflow: hidden;
    color: white;
}

/* Animated blobs */
.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.5;
    animation: move 15s infinite alternate;
}

.blob1 {
    width: 400px;
    height: 400px;
    background: #6366f1;
    top: -100px;
    left: -100px;
}

.blob2 {
    width: 350px;
    height: 350px;
    background: #06b6d4;
    bottom: -100px;
    right: -100px;
}

@keyframes move {
    from { transform: translate(0,0); }
    to { transform: translate(50px, 50px); }
}

/* ================= CONTAINER ================= */
.container {
    position: relative;
    z-index: 10;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* ================= CARD ================= */
.card {
    width: 380px;
    padding: 30px;
    border-radius: 20px;
    backdrop-filter: blur(20px);
    background: rgba(255,255,255,0.05);
    box-shadow: 0 0 40px rgba(0,0,0,0.6);
    text-align: center;
    animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9);}
    to { opacity: 1; transform: scale(1);}
}

/* ================= TITLE ================= */
.title {
    font-size: 28px;
    margin-bottom: 10px;
}

.subtitle {
    font-size: 14px;
    opacity: 0.7;
    margin-bottom: 25px;
}

/* ================= BUTTON ================= */
.btn {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    cursor: pointer;
    color: white;
    background: linear-gradient(45deg, #6366f1, #06b6d4);
    transition: 0.3s;
}

.btn:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px #06b6d4;
}

/* ================= TOGGLE ================= */
.toggle {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 8px 14px;
    border-radius: 10px;
    cursor: pointer;
    background: rgba(255,255,255,0.1);
}

/* LIGHT MODE */
.light {
    background: #f1f5f9;
    color: black;
}

.light .card {
    background: rgba(0,0,0,0.05);
}

</style>

</head>
<body>

<!-- Animated Background -->
<div class="blob blob1"></div>
<div class="blob blob2"></div>

<!-- Theme Toggle -->
<div class="toggle" onclick="toggleTheme()">🌙</div>

<!-- MAIN -->
<div class="container">
    <div class="card">

        <div class="title">🎓 Exam System</div>
        <div class="subtitle">Next-Gen CBT Platform</div>

        <a href="teacher/login.php">
            <button class="btn">👨‍🏫 Teacher Portal</button>
        </a>

        <a href="student/login.php">
            <button class="btn">👨‍🎓 Student Portal</button>
        </a>

        <a href="student/register.php">
            <button class="btn">📝 Register</button>
        </a>

    </div>
</div>

<script>
function toggleTheme() {
    document.body.classList.toggle("light");
}
</script>

</body>
</html>