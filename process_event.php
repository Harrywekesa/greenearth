<?php
include 'php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $event_date = $_POST['date'];

    $sql = "INSERT INTO events (title, description, location, event_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $location, $event_date);

    if ($stmt->execute()) {
        echo '<script>showModal("Event added successfully!");</script>';
    } else {
        echo '<script>showModal("Error adding event. Please try again later.");</script>';
    }
}
?>