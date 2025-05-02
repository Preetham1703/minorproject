<?php
$host = "localhost"; 
$username = "root"; 
$password = "Root";
$dbname = "demo"; 
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    header("Location: service.php?message=Database connection failed&type=error");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $city = $_POST['city'];
    $services = isset($_POST['service']) ? implode(", ", $_POST['service']) : '';
    $other_service = isset($_POST['otherServiceText']) ? trim($_POST['otherServiceText']) : '';

    $errors = [];
    if (empty($name) || !preg_match("/^[A-Za-z\s]+$/", $name)) {
        $errors[] = "Name must contain only letters and cannot be empty.";
    }
    if (empty($mobile) || !preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile must be a 10-digit number.";
    }
    if (empty($city)) {
        $errors[] = "Please select a city.";
    }
    if (empty($services)) {
        $errors[] = "Please select at least one service.";
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO services (name, mobile, city, services, other_service) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $mobile, $city, $services, $other_service);

        if ($stmt->execute()) {
            header("Location: service.php?message=Service added successfully!&type=success");
        } else {
            header("Location: service.php?message=Error saving service: " . $stmt->error . "&type=error");
        }
        $stmt->close();
    } else {
        
        $error_message = implode(" ", $errors);
        header("Location: service.php?message=" . urlencode($error_message) . "&type=error");
    }
}



$conn->close();
exit();
?>