<?php
session_start();

if (!isset($_SESSION['teacher']) && !isset($_SESSION['student'])) {
    header("Location: ../index.php");
    exit();
}
?>