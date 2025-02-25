<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

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
    $duration = trim($_POST['duration']);
    $location = trim($_POST['location']);

    if (empty($title) || empty($description) || empty($duration) || empty($location)) {
        $error_message = 'All fields are required!';
    } else {
        include 'php/db.php';

        // Insert new program
        $insert_sql = "INSERT INTO training_programs (title, description, duration, location) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $title, $description, $duration, $location);

        if ($stmt->execute()) {
            $success_message = 'Training program added successfully!';
        } else {
            $error_message = 'Error adding program: ' . $stmt->error;
        }
    }
}

echo '<section class="add-program">';
echo '<h2>Add New Training Program</h2>';

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

echo '<label for="duration">Duration:</label>';
echo '<input type="text" id="duration" name="duration" required>';

echo '<label for="location">Location:</label>';
echo '<input type="text" id="location" name="location" required>';

echo '<button type="submit">Add Program</button>';
echo '</form>';
echo '</section>';

include 'php/footer.php';