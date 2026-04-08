<?php
session_start();
require_once("../config/db.php");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $msg = "⚠ Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $msg = "✅ Registration Successful!";
        } else {
            $msg = "❌ Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Register</title>

<style>

/* BACKGROUND */
body {
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: radial-gradient(circle at top, #1e293b, #020617);
    font-family: 'Segoe UI';
    color: white;
}

/* CARD */
.card {
    width: 360px;
    padding: 30px;
    border-radius: 20px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    box-shadow: 0 0 40px rgba(0,0,0,0.6);
}

/* TITLE */
.title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
}

/* INPUT GROUP */
.input-group {
    position: relative;
    margin-bottom: 20px;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    outline: none;
    background: rgba(255,255,255,0.08);
    color: white;
}

/* FLOAT LABEL */
.input-group label {
    position: absolute;
    top: 12px;
    left: 12px;
    color: #aaa;
    transition: 0.3s;
    font-size: 14px;
}

.input-group input:focus + label,
.input-group input:valid + label {
    top: -8px;
    font-size: 12px;
    color: #06b6d4;
}

/* BUTTON */
.btn {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(45deg, #6366f1, #06b6d4);
    color: white;
    cursor: pointer;
    transition: 0.3s;
}

.btn:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px #06b6d4;
}

/* MESSAGE */
.msg {
    text-align: center;
    margin-bottom: 10px;
}

/* PASSWORD TOGGLE */
.eye {
    position: absolute;
    right: 10px;
    top: 12px;
    cursor: pointer;
}

/* LINK */
.link {
    text-align: center;
    margin-top: 10px;
}

.link a {
    color: #06b6d4;
    text-decoration: none;
}

</style>
</head>
<body>

<div class="card">

    <div class="title">👨‍🎓 Student Register</div>

    <div class="msg"><?php echo $msg; ?></div>

    <form method="POST" autocomplete="off">

        <div class="input-group">
            <input type="text" name="name" required>
            <label>Name</label>
        </div>

        <div class="input-group">
            <input type="email" name="email" required>
            <label>Email</label>
        </div>

        <div class="input-group">
            <input type="password" name="password" id="password" required>
            <label>Password</label>
            <span class="eye" onclick="togglePass()">👁</span>
        </div>

        <button class="btn">Register</button>

    </form>

    <div class="link">
        <a href="login.php">Already have an account? Login</a>
    </div>

</div>

<script>
function togglePass() {
    let p = document.getElementById("password");
    p.type = p.type === "password" ? "text" : "password";
}
</script>

</body>
</html>