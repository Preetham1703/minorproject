<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$hardcoded_email = "admin123@gmail.com";
$hardcoded_password = "Admin";
$hardcoded_name ="Admin";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and Password are required!'); window.location.href = 'login.html';</script>";
        exit();
    }

    if ($email === $hardcoded_email && $password === $hardcoded_password) {
        $_SESSION["user_id"] = 1;
        $_SESSION["user_name"] = $hardcoded_name;
        $_SESSION["user_email"] = $hardcoded_email;

        echo "<script>alert('Login successful!'); window.location.href = 'admindash.php';</script>";
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href = 'adminlogin.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href = 'adminlogin.html';</script>";
}
?>