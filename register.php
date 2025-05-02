<?php
// $servername = "localhost";
// $username = "root";
// $password = "Root";
// $dbname = "demo";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Check if form is submitted
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $fullname = trim($_POST['fullname']);
//     $email = trim($_POST['email']);
//     $mobile = trim($_POST['mobile']);
//     $password = $_POST['password'];  // ⚠ Plain text password (not safe!)

//     // Insert into database without hashing (UNSAFE)
//     $stmt = $conn->prepare("INSERT INTO users (name, email, password, mobile_number) VALUES (?, ?, ?, ?)");
//     $stmt->bind_param("ssss", $fullname, $email, $password, $mobile);

//     if ($stmt->execute()) {
//         echo "<script>alert('Registration successful!'); window.location.href = 'login.';</script>";
//     } else {
//         echo "Error: " . $stmt->error;
//     }

//     // $stmt->close();
//     // $conn->close();
// }

?>
<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password']; 

    $check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists! Try another.'); window.location.href = 'signup.html';</script>";
    } else {
        // Insert user details
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, mobile_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $email, $password, $mobile);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href = 'login.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $check_email->close();
    $conn->close();
}
?>
