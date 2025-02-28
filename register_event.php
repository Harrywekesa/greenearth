<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/db.php';
include 'php/header.php';

// Check if the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Save the current page URL
        header("Location: login.php");
        exit;
    }
}

// Get event ID from URL
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($event_id <= 0) {
    echo '<p>Invalid event ID.</p>';
    exit;
}

// Fetch event details
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();

    // Insert registration into the database
    $user_id = $_SESSION['user_id'];
    $registration_sql = "INSERT INTO event_registrations (user_id, event_id, registered_at) VALUES (?, ?, NOW())";
    $registration_stmt = $conn->prepare($registration_sql);
    $registration_stmt->bind_param("ii", $user_id, $event_id);

    if ($registration_stmt->execute()) {
        echo '<p>You have successfully registered for the event:</p>';
        echo '<h3>' . htmlspecialchars($event['title']) . '</h3>';
        echo '<p><strong>Date:</strong> ' . date("F j, Y", strtotime($event['event_date'])) . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($event['location']) . '</p>';
        echo '<a href="events.php">Back to Events</a>';
    } else {
        echo '<p>Error registering for the event. Please try again later.</p>';
    }
} else {
    echo '<p>Event not found.</p>';
}
?>