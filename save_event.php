<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_email'])) {
    die("Unauthorized access. Please log in first.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $city = $_POST['city'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $pincode = $_POST['pincode'];
    $address = $_POST['address'];
    $email = $_SESSION['user_email']; // Get email from session

    // Get price from POST
    $price = isset($_POST['price']) ? intval($_POST['price']) : 0;

    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'png', 'jpeg', 'pdf'];
    if (!in_array($fileType, $allowedTypes)) {
        die("Invalid file type. Only JPG, PNG, JPEG, and PDF allowed.");
    }

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        $sql = "INSERT INTO events (name, category, city, event_date, event_time, pincode, address, email, proof_of_conduction, price) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssi",
            $name,
            $category,
            $city,
            $event_date,
            $event_time,
            $pincode,
            $address,
            $email,
            $targetFilePath,
            $price
        );

        if ($stmt->execute()) {
            echo "<script>alert('Event added successfully!');</script>";
            echo "<script>window.location.href='dashboard.php';</script>";
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload file.";
    }
}


?>

