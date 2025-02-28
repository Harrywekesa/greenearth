<?php
include 'php/init.php'; // Start session and initialize configurations

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$participation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($participation_id <= 0) {
    echo '<p class="error-message">Invalid participation ID.</p>';
    exit;
}

// Mark the user as attended
$update_sql = "UPDATE event_participation SET attended = 'yes' WHERE id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("i", $participation_id);

if ($stmt->execute()) {
    echo '<p class="success-message">User marked as attended successfully!</p>';
    header("Refresh:2; url=admin.php");
    exit;
} else {
    echo '<p class="error-message">Error marking attendance: ' . $stmt->error . '</p>';
}
?>