<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVENTHIVE</title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="library/bootstrap.min.css">
    <script src="library/bootstrap.bundle.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #e3f2fd;
            font-family: 'Poppins', sans-serif;
        }
        #title {
            margin-top: 5px;
            display: flex;
            align-items: center;
            padding: 15px;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            background: linear-gradient(135deg, #4a90e2, #007aff);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            flex-wrap: wrap;
            background-image: url(images/backg.jpg);
        }
        #title-img {
            height: 60px;
            width: 60px;
            border-radius: 50%;
        }
        #mainbar {
            margin-top: 3px;
            width: 100%;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        #available-services {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            max-height: 200px;
            overflow-y: auto;
        }
        #available-services ul {
            list-style-type: none;
            padding-left: 0;
        }
        #available-services li {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #available-services li strong {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-3">
        <div id="title" class="shadow">
            <img src="images/logo.jpg" alt="EventHive" id="title-img">
            <h1>EVENT HIVE</h1>
        </div>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top mt-1" style="border-radius:10px;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">EventHive</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div id="mainbar">
            <h2 style="text-align: center;">ADD YOUR EVENT</h2>
            <?php
            if (isset($_GET['message'])) {
                $message = htmlspecialchars($_GET['message']);
                $type = isset($_GET['type']) && $_GET['type'] === 'success' ? 'success' : 'error';
                echo "<div class='message $type'>$message</div>";
            }
            ?>

            <form action="save_event.php" method="POST" class="needs-validation bg-light p-5 rounded" enctype="multipart/form-data" novalidate id="eventForm">
                <div class="input-group mb-3">
                    <span class="input-group-text">Name</span>
                    <input type="text" class="form-control" name="name" placeholder="Enter Event Name" required>
                </div>
                <div class="form-floating mt-4">
                    <select name="category" class="form-select" required>
                        <option value="Music">Music</option>
                        <option value="Drama">Drama</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Dance">Dance</option>
                        <option value="Exhibitions">Exhibitions</option>
                        <option value="MagicShow">Magic Show</option>
                    </select>
                    <label for="category">Select your Category</label>
                </div>
                <div class="form-floating mt-4">
                    <select name="city" id="city" class="form-select" required>
                        <option value="">Select a city</option>
                        <option value="Bangalore">Bangalore</option>
                        <option value="Hyderabad">Hyderabad</option>
                        <option value="Mumbai">Mumbai</option>
                        <option value="Vizag">Vizag</option>
                        <option value="Karimnagar">Karimnagar</option>
                    </select>
                    <label for="city">Select your City</label>
                </div>
                <div id="available-services">
                    <p>Select a city to see available services.</p>
                </div>
                <br>
                <div class="input-group mb-3">
                    <span class="input-group-text">Date</span>
                    <input type="date" class="form-control" name="event_date" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Time</span>
                    <input type="time" class="form-control" name="event_time" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">PINCODE</span>
                    <input type="text" class="form-control" name="pincode" placeholder="Enter Pincode" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Enter full Address</span>
                    <textarea name="address" rows="3" class="form-control" required></textarea>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Mail ID</span>
                    <input type="email" class="form-control" name="email" value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>" readonly required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Proof of conduction</span>
                    <input type="file" class="form-control" name="file" required>
                </div>
                <button class="btn btn-info" type="submit">Add Event</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("eventForm").addEventListener("submit", function(event) {
    let name = document.querySelector("[name='name']").value.trim();
    let email = document.querySelector("[name='email']").value.trim();
    let pincode = document.querySelector("[name='pincode']").value.trim();
    let eventDate = document.querySelector("[name='event_date']").value;
    let eventTime = document.querySelector("[name='event_time']").value;
    let fileInput = document.querySelector("[name='file']");
    let nameRegex = /^[A-Za-z\s]+$/;
    if (!nameRegex.test(name) || name.length < 2) {
        alert("Name should contain only letters and be at least 2 characters long.");
        event.preventDefault();
        return;
    }
    let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(email)) {
        alert("Enter a valid email address.");
        event.preventDefault();
        return;
    }
    if (!/^\d{6}$/.test(pincode)) {
        alert("Pincode must be a 6-digit number.");
        event.preventDefault();
        return;
    }
    let now = new Date(); 
    let selectedDate = new Date(eventDate + "T" + eventTime); 
    if (selectedDate < now) {
        alert("Event date and time cannot be in the past.");
        event.preventDefault();
        return;
    }
    if (fileInput.files.length === 0) {
        alert("Please upload a proof of conduction.");
        event.preventDefault();
        return;
    }
});
        document.getElementById("city").addEventListener("change", function() {
            let city = this.value;
            let serviceDiv = document.getElementById("available-services");

            if (city === "") {
                serviceDiv.innerHTML = "<p>Select a city to see available services.</p>";
                return;
            }
            fetch("get_services.php?city=" + encodeURIComponent(city))
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let html = "<h5>Available Services in " + city + ":</h5><ul>";
                        data.forEach(provider => {
                            html += "<li>";
                            html += "<strong>Name:</strong> " + provider.name + "<br>";
                            html += "<strong>Mobile:</strong> " + provider.mobile + "<br>";
                            html += "<strong>Services:</strong> " + provider.services.join(", ") + "</li>";
                        });
                        html += "</ul>";
                        serviceDiv.innerHTML = html;
                    } else {
                        serviceDiv.innerHTML = "<p>No services available in " + city + ".</p>";
                    }
                })
                .catch(error => {
                    serviceDiv.innerHTML = "<p>Error fetching services.</p>";
                    console.error("Error:", error);
                });
        });
    </script>
</body>
</html>