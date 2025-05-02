<?php

$host = "localhost"; 
$username = "root"; 
$password = "Root"; 
$dbname = "demo"; 
$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}


$city = isset($_GET['city']) ? $_GET['city'] : '';

if (empty($city)) {
    echo json_encode([]);
    exit();
}


$stmt = $conn->prepare("SELECT name, mobile, services, other_service FROM services WHERE city = ?");
$stmt->bind_param("s", $city);
$stmt->execute();
$result = $stmt->get_result();

$services_list = [];
while ($row = $result->fetch_assoc()) {
    // Split services and filter out "Other"
    $services = array_filter(explode(", ", $row['services']), function($service) {
        return $service !== "Other";
    });

    // Only add other_service if it’s not empty and not "Other"
    if (!empty($row['other_service']) && $row['other_service'] !== "Other") {
        $services[] = $row['other_service'];
    }

    
    if (!empty($services)) {
        $service_entry = [
            "name" => $row['name'],
            "mobile" => $row['mobile'],
            "services" => array_values($services)
        ];
        $services_list[] = $service_entry;
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($services_list);
exit();
?>