<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["event_name"])) {
    $event_name = $_POST["event_name"];
    $user_email = $_SESSION["user_email"];

    $sql = "DELETE FROM events WHERE name = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $event_name, $user_email);

    if ($stmt->execute()) {
        $_SESSION["message"] = "Event removed successfully.";
    } else {
        $_SESSION["message"] = "Failed to remove the event.";
    }

    $stmt->close();
    $conn->close();

    header("Location: dashboard.php");
    exit();
}
?>