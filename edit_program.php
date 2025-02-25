<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

// Get program ID from URL
$program_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($program_id <= 0) {
    echo '<p>Invalid program ID.</p>';
    exit;
}

// Fetch program details
$sql = "SELECT * FROM training_programs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $program_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $program = $result->fetch_assoc();
} else {
    echo '<p>Program not found.</p>';
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $duration = trim($_POST['duration']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);

    // Basic validation
    if (empty($title) || empty($duration) || empty($location)) {
        $error_message = 'Title, duration, and location are required!';
    } else {
        // Update program details
        $update_sql = "UPDATE training_programs SET title = ?, duration = ?, location = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $title, $duration, $location, $description, $program_id);

        if ($stmt->execute()) {
            $success_message = 'Training program updated successfully!';
        } else {
            $error_message = 'Error updating program: ' . $stmt->error;
        }
    }
}

echo '<section class="edit-program">';
echo '<h2>Edit Training Program</h2>';

if (!empty($error_message)) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}

if (!empty($success_message)) {
    echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
}

echo '<form method="POST">';
echo '<label for="title">Title:</label>';
echo '<input type="text" id="title" name="title" value="' . htmlspecialchars($program['title'] ?? '') . '" required>';

echo '<label for="duration">Duration:</label>';
echo '<input type="text" id="duration" name="duration" value="' . htmlspecialchars($program['duration'] ?? '') . '" required>';

echo '<label for="location">Location:</label>';
echo '<input type="text" id="location" name="location" value="' . htmlspecialchars($program['location'] ?? '') . '" required>';

echo '<label for="description">Description:</label>';
echo '<textarea id="description" name="description" required>' . htmlspecialchars($program['description'] ?? '') . '</textarea>';

echo '<button type="submit" class="button">Save Changes</button>';
echo '<a href="admin.php" class="button">Cancel</a>';
echo '</form>';
echo '</section>';

include 'php/footer.php';