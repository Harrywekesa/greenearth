<?php
include 'php/init.php'; // Start session and initialize configurations

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($event_id <= 0) {
    echo '<p>Invalid event ID.</p>';
    exit;
}

// Delete the event
$sql = "DELETE FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);

if ($stmt->execute()) {
    echo '<script>alert("Event deleted successfully!");</script>';
    echo '<meta http-equiv="refresh" content="0;url=admin.php">';
} else {
    echo '<p>Error deleting event: ' . htmlspecialchars($stmt->error) . '</p>';
}
?>