function getCategoryFromURL() {
    let path = window.location.pathname; // Get the page URL
    let page = path.split("/").pop(); // Get filename (e.g., "music.html")
    let category = page.replace(".html", ""); // Remove .html to get category name

    // Convert file name to proper category name if needed
    let categoryMapping = {
        "music": "Music",
        "comedy": "Comedy",
        "dance": "Dance",
        "exhibition": "Exhibitions",
        "magic": "MagicShow",
        "drama": "Drama"
    };

    return categoryMapping[category] || ""; // Return category or empty if not found
}

function fetchEvents() {
    let category = getCategoryFromURL();
    let city = document.getElementById("city").value;
    let resultDiv = document.getElementById("result");

    if (category === "" || city === "") {
        resultDiv.innerHTML = "<p style='color: red;'>City selection is required.</p>";
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_events.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.status === 200) {
            resultDiv.innerHTML = this.responseText;
        }
    };

    xhr.send("category=" + category + "&city=" + city);
}
