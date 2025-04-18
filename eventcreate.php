<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "Root";
$dbname = "demo";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and Password are required!'); window.location.href = 'login.html';</script>";
        exit();
    }

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashedPassword);
        $stmt->fetch();

        
        if ($password === $hashedPassword) { 
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_email"] = $email;
            echo "<script>alert('Login successful!'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Invalid password!'); window.location.href = 'login.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email!'); window.location.href = 'login.html';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request!'); window.location.href = 'login.html';</script>";
}

$conn->close();
?>
<?php
session_start();
$_SESSION['user_email'] = $email; // Assuming $email is fetched from the database upon successful login
?>
