<?php
session_start();
require_once("../config/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role='student'");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['student'] = $user['id'];
            $_SESSION['name'] = $user['name'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Incorrect Password!";
        }
    } else {
        $error = "⚠ User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Login</title>

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

/* INPUT */
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

/* ERROR */
.error {
    text-align: center;
    margin-bottom: 10px;
    color: #ff4d4d;
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

/* PASSWORD EYE */
.eye {
    position: absolute;
    right: 10px;
    top: 12px;
    cursor: pointer;
}

</style>
</head>
<body>

<div class="card">

    <div class="title">👨‍🎓 Student Login</div>

    <?php if ($error) { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST" autocomplete="off">

        <div class="input-group">
            <input type="email" name="email" required>
            <label>Email</label>
        </div>

        <div class="input-group">
            <input type="password" name="password" id="password" required>
            <label>Password</label>
            <span class="eye" onclick="togglePass()">👁</span>
        </div>

        <button class="btn">Login</button>

    </form>

    <div class="link">
        <a href="register.php">Create new account</a>
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