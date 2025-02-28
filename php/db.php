<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greenearth";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Suppress errors during development (optional)
    // Use error logging instead of displaying errors directly
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed."); // Avoid detailed error messages in production
}
?>