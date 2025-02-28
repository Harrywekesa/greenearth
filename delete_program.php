<?php
include 'php/init.php'; // Start session and initialize configurations

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$program_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($program_id <= 0) {
    echo '<p>Invalid program ID.</p>';
    exit;
}

// Delete the training program
$sql = "DELETE FROM training_programs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $program_id);

if ($stmt->execute()) {
    echo '<script>alert("Program deleted successfully!");</script>';
    echo '<meta http-equiv="refresh" content="0;url=admin.php">';
} else {
    echo '<p>Error deleting program: ' . htmlspecialchars($stmt->error) . '</p>';
}
?>