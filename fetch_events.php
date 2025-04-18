<?php
$servername = "localhost";  // Change if needed
$username = "root";  // Change if needed
$password = "Root";  // Change if needed
$dbname = "demo";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get category and city from AJAX request
$category = isset($_POST['category']) ? $_POST['category'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';

// Validate inputs
if (empty($category) || empty($city)) {
    echo "<p style='color: red;'>Invalid request. Please select a city.</p>";
    exit();
}

// Prepare SQL query to fetch upcoming or present events
$sql = "SELECT * FROM events WHERE category = ? AND city = ? AND event_date >= CURDATE() ORDER BY event_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $category, $city);
$stmt->execute();
$result = $stmt->get_result();

// Check if events exist
if ($result->num_rows > 0) {
    echo "<h4>Upcoming Events in " . htmlspecialchars($city) . " (" . htmlspecialchars($category) . ")</h4>";
    echo "<ul class='list-group'>";
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item'>";
        echo "<strong>" . htmlspecialchars($row['name']) . "</strong> - " . htmlspecialchars($row['event_date']) . " at " . htmlspecialchars($row['event_time']);
        echo "<br><small>Location: " . htmlspecialchars($row['address']) . " (Pincode: " . htmlspecialchars($row['pincode']) . ")</small>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>No upcoming events found for the selected category and city.</p>";
}

// Close connection
$stmt->close();
$conn->close();
?>