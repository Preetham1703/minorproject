<?php
session_start();
session_destroy(); // Destroy all session data
header("Location: demo.html"); // Redirect to login page
exit();
?>
