<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

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
} else {
    echo '<p>Event not found.</p>';
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $event_date = trim($_POST['event_date']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);

    // Basic validation
    if (empty($title) || empty($event_date) || empty($location)) {
        $error_message = 'Title, date, and location are required!';
    } else {
        // Update event details
        $update_sql = "UPDATE events SET title = ?, event_date = ?, location = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $title, $event_date, $location, $description, $event_id);

        if ($stmt->execute()) {
            $success_message = 'Event updated successfully!';
        } else {
            $error_message = 'Error updating event: ' . $stmt->error;
        }
    }
}

echo '<section class="edit-event">';
echo '<h2>Edit Event</h2>';

if (!empty($error_message)) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}

if (!empty($success_message)) {
    echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
}

echo '<form method="POST">';
echo '<label for="title">Title:</label>';
echo '<input type="text" id="title" name="title" value="' . htmlspecialchars($event['title'] ?? '') . '" required>';

echo '<label for="event_date">Event Date:</label>';
echo '<input type="date" id="event_date" name="event_date" value="' . htmlspecialchars(date("Y-m-d", strtotime($event['event_date'] ?? ''))) . '" required>';

echo '<label for="location">Location:</label>';
echo '<input type="text" id="location" name="location" value="' . htmlspecialchars($event['location'] ?? '') . '" required>';

echo '<label for="description">Description:</label>';
echo '<textarea id="description" name="description" required>' . htmlspecialchars($event['description'] ?? '') . '</textarea>';

echo '<button type="submit" class="button">Save Changes</button>';
echo '<a href="admin.php" class="button">Cancel</a>';
echo '</form>';
echo '</section>';

include 'php/footer.php';