<?php
include 'php/init.php'; // Start session and initialize configurations

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    echo '<p>Invalid user ID.</p>';
    exit;
}

// Delete the user
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo '<script>alert("User deleted successfully!");</script>';
    echo '<meta http-equiv="refresh" content="0;url=admin.php">';
} else {
    echo '<p>Error deleting user: ' . htmlspecialchars($stmt->error) . '</p>';
}
?>