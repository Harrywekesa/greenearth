<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/db.php';
include 'php/header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Save the current page URL
    header("Location: login.php");
    exit;
}

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

    // Insert enrollment into the database
    $user_id = $_SESSION['user_id'];
    $enrollment_sql = "INSERT INTO program_enrollments (user_id, program_id, enrolled_at) VALUES (?, ?, NOW())";
    $enrollment_stmt = $conn->prepare($enrollment_sql);
    $enrollment_stmt->bind_param("ii", $user_id, $program_id);

    if ($enrollment_stmt->execute()) {
        echo '<p>You have successfully enrolled in the program:</p>';
        echo '<h3>' . htmlspecialchars($program['title']) . '</h3>';
        echo '<p><strong>Duration:</strong> ' . htmlspecialchars($program['duration']) . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($program['location']) . '</p>';
        echo '<a href="training.php">Back to Training Programs</a>';
    } else {
        echo '<p>Error enrolling in the program. Please try again later.</p>';
    }
} else {
    echo '<p>Program not found.</p>';
}
?>