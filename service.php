<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVENTHIVE</title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="library/bootstrap.min.css">
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
            animation: fadeIn 1s ease-in-out;
        }
        #title h1 {
            font-weight: bold;
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
        footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            width: 100%;
            border-radius: 8px;
        }
        #footer a {
            text-decoration: none;
            color: dimgray;
        }
        #footer a:hover {
            color: #bbbbbb;
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
    </style>
    <script>
        function validateForm() {
            let isValid = true;

            // Validate Name
            let fullname = document.getElementById("name").value.trim();
            let nameRegex = /^[A-Za-z\s]+$/;
            if (fullname === "") {
                document.getElementById("result1").innerText = "Name is required.";
                document.getElementById("result1").style.color = "red";
                isValid = false;
            } else if (!nameRegex.test(fullname)) {
                document.getElementById("result1").innerText = "Name must contain only letters.";
                document.getElementById("result1").style.color = "red";
                isValid = false;
            } else {
                document.getElementById("result1").innerText = "";
            }

            
            let mobile = document.getElementById("mobile").value.trim();
            let mobileRegex = /^[0-9]{10}$/;
            if (mobile === "") {
                document.getElementById("result2").innerText = "Mobile number is required.";
                document.getElementById("result2").style.color = "red";
                isValid = false;
            } else if (!mobileRegex.test(mobile)) {
                document.getElementById("result2").innerText = "Mobile number must contain exactly 10 digits.";
                document.getElementById("result2").style.color = "red";
                isValid = false;
            } else {
                document.getElementById("result2").innerText = "";
            }

            
            let city = document.getElementById("city").value;
            if (city === "") {
                document.getElementById("cityError").innerText = "Please select a city.";
                isValid = false;
            } else {
                document.getElementById("cityError").innerText = "";
            }

            
            let checkboxes = document.querySelectorAll(".service-checkbox");
            let isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            let checkboxError = document.getElementById("checkbox-error");

            if (!isChecked) {
                checkboxError.style.display = "block";
                isValid = false;
            } else {
                checkboxError.style.display = "none";
            }

            
            let otherCheckbox = document.getElementById("check5");
            let otherServiceText = document.getElementById("otherServiceText");
            if (otherCheckbox.checked && otherServiceText.value.trim() === "") {
                alert("Please specify the 'Other' service.");
                isValid = false;
            }

            return isValid;
        }

       
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("check5").addEventListener("change", function () {
                let otherInput = document.getElementById("otherServiceInput");
                if (this.checked) {
                    otherInput.style.display = "block";
                    document.getElementById("otherServiceText").setAttribute("required", "true");
                } else {
                    otherInput.style.display = "none";
                    document.getElementById("otherServiceText").removeAttribute("required");
                }
            });
        });
    </script>
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
                            <a class="nav-link" href="demo.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#footer">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="mainbar" class="container p-3">
        <h2 style="text-align: center;">ADD YOUR SERVICE</h2>
        <?php
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            $type = isset($_GET['type']) && $_GET['type'] === 'success' ? 'success' : 'error';
            echo "<div class='message $type'>$message</div>";
        }
        ?>

        <form action="save_service.php" method="POST" class="bg-light p-5 rounded" id="eventForm" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
                <span id="result1" class="text-danger"></span>
            </div>

            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile</label>
                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your Mobile number">
                <span id="result2" class="text-danger"></span>
            </div>

            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <select name="city" id="city" class="form-select">
                    <option value="">Select City</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Hyderabad">Hyderabad</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Vizag">Vizag</option>
                    <option value="Karimnagar">Karimnagar</option>
                </select>
                <span id="cityError" class="text-danger"></span>
            </div>

            <label for="service" class="form-label mt-3">Select your service (at least one)</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input service-checkbox" id="check1" name="service[]" value="Sound System">
                <label class="form-check-label" for="check1">Sound System</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input service-checkbox" id="check2" name="service[]" value="Decoration">
                <label class="form-check-label" for="check2">Decoration</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input service-checkbox" id="check3" name="service[]" value="Fireworks">
                <label class="form-check-label" for="check3">Fireworks</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input service-checkbox" id="check4" name="service[]" value="Technicians">
                <label class="form-check-label" for="check4">Technicians</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input service-checkbox" id="check5" name="service[]" value="Other">
                <label class="form-check-label" for="check5">Others</label>
            </div>

            <div id="otherServiceInput" class="mt-2" style="display: none;">
                <label for="otherServiceText" class="form-label">Specify Other Service</label>
                <input type="text" class="form-control" id="otherServiceText" name="otherServiceText" placeholder="Enter other service">
            </div>

            <div class="text-danger mt-2" id="checkbox-error" style="display: none;">Please select at least one service.</div>

            <button type="submit" class="btn btn-primary mt-3">Add</button>
        </form>
    </div>
    <footer id="footer">
        <p> &copy; 2025 EventHive. All rights reserved.</p>
        <p>Email: <a href="mailto:preethambk0111@gmail.com" style="color: #ffcc00;">preethambk0111@gmail.com</a></p>
        <p>📞<a href="tel:+916281175531" style="text-decoration: none; color: white;">+916281175531</a></p>
    </footer>
</body>
</html>