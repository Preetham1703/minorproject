<?php
$servername = "localhost"; 
$username = "root"; 
$password = "Root"; 
$dbname = "demo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, category, city, event_date, event_time, proof_of_conduction FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events</title>
</head>
<body>
    <h2>List of Events</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>City</th>
            <th>Date</th>
            <th>Time</th>
            <th>Proof</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["category"]; ?></td>
                <td><?php echo $row["city"]; ?></td>
                <td><?php echo $row["event_date"]; ?></td>
                <td><?php echo $row["event_time"]; ?></td>
                <td><a href="<?php echo $row["proof_of_conduction"]; ?>" download>Download</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
