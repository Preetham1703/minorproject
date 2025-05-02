<?php
// $servername = "localhost"; 
// $username = "root"; 
// $password = "Root"; 
// $dbname = "demo";

// $conn = new mysqli($servername, $username, $password, $dbname);


// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // File upload handling
// $target_dir = "uploads/";
// if (!file_exists($target_dir)) {
//     mkdir($target_dir, 0777, true); // Create directory if not exists
// }

// $file_name = basename($_FILES["file"]["name"]);
// $target_file = $target_dir . time() . "_" . $file_name;
// $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// $allowed_types = array("jpg", "png", "pdf", "docx");

// if (!in_array($file_type, $allowed_types)) {
//     die("Invalid file type. Only JPG, PNG, PDF, and DOCX are allowed.");
// }

// if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
//     die("Error uploading file.");
// }

// // Insert data into database
// $name = $conn->real_escape_string($_POST['name']);
// $category = $conn->real_escape_string($_POST['category']);
// $city = $conn->real_escape_string($_POST['city']);
// $event_date = $_POST['event_date'];
// $event_time = $_POST['event_time'];
// $pincode = $_POST['pincode'];
// $address = $conn->real_escape_string($_POST['address']);

// $sql = "INSERT INTO events (name, category, city, event_date, event_time, pincode, address, proof_of_conduction) 
//         VALUES ('$name', '$category', '$city', '$event_date', '$event_time', '$pincode', '$address', '$target_file')";

// if ($conn->query($sql) === TRUE) {
//     echo "<script>alert('Event added successfully!');window.location.href = 'dashboard.php';</script>";
// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }

// $conn->close();
?>
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
    $email = $_SESSION['user_email'];
    $targetDir = "uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowedTypes = ['jpg', 'png', 'jpeg', 'pdf'];
    if (!in_array($fileType, $allowedTypes)) {
        die("Invalid file type. Only JPG, PNG, JPEG, and PDF allowed.");
    }
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        $sql = "INSERT INTO events (name, category, city, event_date, event_time, pincode, address, email, proof_of_conduction) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $name, $category, $city, $event_date, $event_time, $pincode, $address, $email, $targetFilePath);

        if ($stmt->execute()) {
            echo "<script>alert('Event added successfully!');</script>";
            echo "<script>window.location.href='admindash.php';</script>";
            exit(); 
        } else {
            echo "Error: " . $conn->error;
        }
        
    } else {
        echo "Failed to upload file.";
    }
}
?>

