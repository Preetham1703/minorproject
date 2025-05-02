<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
$user_name = $_SESSION["user_name"];
$sql = "SELECT name, category, city, address, event_date, proof_of_conduction FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVENTHIVE</title>
    <link rel="stylesheet" href="library/bootstrap.min.css">
    <script src="library/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="images/logo.jpg">
    <style>
        body { 
            background-color: #e3f2fd; 
            font-family: 'Poppins', sans-serif; 
        }
        #title { 
            margin-top:5px; padding: 15px;
             text-align: center;
              background: linear-gradient(135deg, #4a90e2, #007aff); 
              color: white;
               border-radius: 10px;
               background-image: url(images/backg.jpg);
             }
        #mainbar {
            margin-top: 3px;
            width: 100%;
            height: auto;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .event { 
            padding: 15px; 
            border-bottom: 1px solid #ddd; 
        }
        .event h3 {
            color: #007aff; 
        }
        footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            padding-bottom: 5px;
        }
        #footer a{
            text-decoration: none;
            color:dimgray;
        }
        #footer a:hover{
            color: #bbbbbb;
            text-decoration: none;
        }
        .events-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .events-section {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #f9f9f9;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            height:60vh;
            overflow-y:auto;
        }
        .events-section h4 {
            text-align: center;
            color: #007aff;
        }
    </style>
    <script>
         function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</head>
<body>

<div class="container-fluid mt-3">
    <div id="title">
        <img src="images/logo.jpg" alt="EventHive" style="height: 60px; width: 60px; border-radius: 50%;">
        <h1> EVENT HIVE</h1>
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top mt-1" style="border-radius:10px;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">EventHive</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="adminadd.php">Add Event</a></li>
                    <li class="nav-item"><a class="nav-link" style="cursor:pointer;" onclick="confirmLogout()">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="mainbar">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <p>All Events:</p>
        <div>
            <?php 
            $event_count = $result->num_rows;
            echo "<p><strong>Total Events:</strong> $event_count</p>";

            $current_date = date("Y-m-d");
            $upcoming_events = [];
            $completed_events = [];

            while ($row = $result->fetch_assoc()) {
                if ($row["event_date"] >= $current_date) {
                    $upcoming_events[] = $row;
                } else {
                    $completed_events[] = $row;
                }
            }
            ?>

            <div class="events-container">
                <div class="events-section">
                    <h4><u><b>Upcoming Events</b></u></h4>
                    <?php
                    if (count($upcoming_events) > 0) {
                        foreach ($upcoming_events as $event) {
                            echo '<div class="event">
                                    <h3>' . htmlspecialchars($event["name"]) . '</h3>
                                    <p><strong>Date:</strong> ' . htmlspecialchars($event["event_date"]) . '</p>
                                    <p><strong>Category:</strong> ' . htmlspecialchars($event["category"]) . '</p>
                                    <p><strong>City:</strong> ' . htmlspecialchars($event["city"]) . '</p>
                                    <a href="uploads/' . basename(htmlspecialchars($event["proof_of_conduction"])) . '" class="btn btn-primary btn-sm" download>Download Proof</a>
                        
                                  </div>';
                        }
                    } else {
                        echo '<p>No upcoming events found.</p>';
                    }
                    ?>
                </div>
                <div class="events-section">
                    <h4><u><b>Completed Events</b></u></h4>
                    <?php
                    if (count($completed_events) > 0) {
                        foreach ($completed_events as $event) {
                            echo '<div class="event">
                                    <h3>' . htmlspecialchars($event["name"]) . '</h3>
                                    <p><strong>Date:</strong> ' . htmlspecialchars($event["event_date"]) . '</p>
                                    <p><strong>Category:</strong> ' . htmlspecialchars($event["category"]) . '</p>
                                    <p><strong>City:</strong> ' . htmlspecialchars($event["city"]) . '</p>
                                    <a href="uploads/' . basename(htmlspecialchars($event["proof_of_conduction"])) . '" class="btn btn-primary btn-sm" download>Download Proof</a>
                                  </div>';
                        }
                    } else {
                        echo '<p>No completed events found.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer id="footer">
        <p>Copyrights &copy; 2025 EventHive. All rights reserved.</p>
        <p>Email: <a href="mailto:preethambk0111@gmail.com">preethambk0111@gmail.com</a></p>
        <p>📞 <a href="tel:+916281175531">+91 6281175531</a></p>
    </footer>
</div>

</body>
</html>