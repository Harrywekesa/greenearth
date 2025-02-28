<?php 
include 'php/init.php'; // Start session and initialize configurations

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $event_date = trim($_POST['date']);

    if (empty($title) || empty($description) || empty($location) || empty($event_date)) {
        $error_message = 'All fields are required!';
    } else {
        include 'php/db.php';

        // Insert new event
        $insert_sql = "INSERT INTO events (title, description, event_date, location) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $title, $description, $event_date, $location);

        if ($stmt->execute()) {
            $success_message = 'Event added successfully!';
            header("Refresh:2; url=admin.php"); // Redirect after success
            exit;
        } else {
            $error_message = 'Error adding event: ' . $stmt->error;
        }
    }
}

echo '<section class="add-event">';
echo '<h2>Add New Event</h2>';

if (!empty($error_message)) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}

if (!empty($success_message)) {
    echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
}

echo '<form method="POST">';
echo '<label for="title">Title:</label>';
echo '<input type="text" id="title" name="title" required>';

echo '<label for="description">Description:</label>';
echo '<textarea id="description" name="description" required></textarea>';

echo '<label for="location">Location:</label>';
echo '<input type="text" id="location" name="location" required>';

echo '<label for="date">Event Date:</label>';
echo '<input type="date" id="date" name="date" required>';

echo '<button type="submit">Add Event</button>';
echo '</form>';
echo '</section>';

include 'php/footer.php';