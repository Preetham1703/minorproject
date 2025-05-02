<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_name = $_SESSION["user_name"];
$user_email = $_SESSION["user_email"];
$monthFilter = isset($_GET['month']) ? intval($_GET['month']) : 0;

if ($monthFilter > 0) {
    $sql = "SELECT name, category, city, address, event_date, proof_of_conduction 
            FROM events 
            WHERE email = ? AND MONTH(event_date) = ? 
            ORDER BY event_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $user_email, $monthFilter);
} else {
    $sql = "SELECT name, category, city, address, event_date, proof_of_conduction 
            FROM events 
            WHERE email = ? 
            ORDER BY event_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
}
$stmt->execute();
$result = $stmt->get_result();

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
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        #title {
            margin-top: 5px;
            padding: 20px;
            text-align: center;
            background: linear-gradient(135deg, #4a90e2, #007aff);
            background-image: url(images/backg.jpg);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        #title h1 {
            font-weight: bold;
        }
        #title img {
            height: 60px;
            width: 60px;
            border-radius: 50%;
        }
        #mainbar {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .events-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .events-section {
            flex: 1;
            min-width: 300px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 60vh;
        }
        .events-section h4 {
            text-align: center;
            color: #007aff;
            margin-bottom: 15px;
        }
        .event {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .event:last-child {
            border-bottom: none;
        }
        .event h3 {
            color: #007aff;
            font-size: 18px;
        }
        .event p {
            margin: 5px 0;
            font-size: 14px;
        }
        .event .btn {
            margin-right: 5px;
        }
        footer {
            margin-top: 20px;
            background: #343a40;
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
        }
        #footer a{
            text-decoration: none;
            color:dimgray;
        }
        #footer a:hover{
            color: #bbbbbb;
            text-decoration: none;
        }
        .btn-primary {
            background-color: #007aff;
            border-color: #007aff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #a71d2a;
            border-color: #a71d2a;
        }
    </style>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = 'logout.php';
            }
        }
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
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
                    <li class="nav-item"><a class="nav-link" href="Addevent.php">Add Event</a></li>
                    <li class="nav-item"><a class="nav-link" href="#footer">Contact</a></li> 
                    <li class="nav-item"><a class="nav-link" style="cursor:pointer;" onclick="confirmLogout()">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="mainbar">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <p>Your Events:</p>
        <div class="mb-3">
    <form method="GET" action="dashboard.php">
        <label for="monthFilter" class="form-label">Filter by Month:</label>
        <select name="month" id="monthFilter" class="form-select" onchange="this.form.submit()">
            <option value="">All Months</option>
            <?php
            for ($m = 1; $m <= 12; $m++) {
                $monthName = date("F", mktime(0, 0, 0, $m, 1));
                $selected = (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '';
                echo "<option value=\"$m\" $selected>$monthName</option>";
            }
            ?>
        </select>
    </form>
</div>
<div class="events-container">
    <!-- Upcoming Events Section -->
    <div class="events-section">
        <h4><u><b>Upcoming Events</b></u></h4>
        <?php
        if (count($upcoming_events) > 0) {
            foreach ($upcoming_events as $index => $event) {
                echo '<div class="event">
                        <h3>' . htmlspecialchars($event["name"]) . '</h3>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#upcomingEventModal' . $index . '">
                            View Details
                        </button>
                        <form method="POST" action="remove_event.php" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to remove this event?\');">
                            <input type="hidden" name="event_name" value="' . htmlspecialchars($event["name"]) . '">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                      </div>';

                // Modal for Upcoming Event
                echo '<div class="modal fade" id="upcomingEventModal' . $index . '" tabindex="-1" aria-labelledby="upcomingEventModalLabel' . $index . '" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="upcomingEventModalLabel' . $index . '">' . htmlspecialchars($event["name"]) . '</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Date:</strong> ' . htmlspecialchars($event["event_date"]) . '</p>
                                    <p><strong>Category:</strong> ' . htmlspecialchars($event["category"]) . '</p>
                                    <p><strong>City:</strong> ' . htmlspecialchars($event["city"]) . '</p>
                                    <p><strong>Address:</strong> ' . htmlspecialchars($event["address"]) . '</p>
                                    <a href="uploads/' . basename(htmlspecialchars($event["proof_of_conduction"])) . '" class="btn btn-primary btn-sm" download>Download Proof</a>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                      </div>';
            }
        } else {
            echo '<p>No upcoming events found.</p>';
        }
        ?>
    </div>
    

    <!-- Completed Events Section -->
    <div class="events-section">
        <h4><u><b>Completed Events</b></u></h4>
        <?php
        if (count($completed_events) > 0) {
            foreach ($completed_events as $index => $event) {
                echo '<div class="event">
                        <h3>' . htmlspecialchars($event["name"]) . '</h3>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#completedEventModal' . $index . '">
                            View Details
                        </button>
                      </div>';

                // Modal for Completed Event
                echo '<div class="modal fade" id="completedEventModal' . $index . '" tabindex="-1" aria-labelledby="completedEventModalLabel' . $index . '" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="completedEventModalLabel' . $index . '">' . htmlspecialchars($event["name"]) . '</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Date:</strong> ' . htmlspecialchars($event["event_date"]) . '</p>
                                    <p><strong>Category:</strong> ' . htmlspecialchars($event["category"]) . '</p>
                                    <p><strong>City:</strong> ' . htmlspecialchars($event["city"]) . '</p>
                                    <p><strong>Address:</strong> ' . htmlspecialchars($event["address"]) . '</p>
                                    <a href="uploads/' . basename(htmlspecialchars($event["proof_of_conduction"])) . '" class="btn btn-primary btn-sm" download>Download Proof</a>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
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
        <p>&copy; 2025 EventHive. All rights reserved.</p>
        <p>Email: <a href="mailto:preethambk0111@gmail.com">preethambk0111@gmail.com</a></p>
        <p>📞 <a href="tel:+916281175531">+91 6281175531</a></p>
    </footer>
</div>

</body>
</html>

