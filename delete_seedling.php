<?php
include 'php/init.php'; // Start session and initialize configurations

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$seedling_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($seedling_id <= 0) {
    echo '<p>Invalid seedling ID.</p>';
    exit;
}

// Fetch seedling details
$sql = "SELECT image FROM seedlings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seedling_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $seedling = $result->fetch_assoc();

    // Delete the image file
    $image_path = $seedling['image'];
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    // Delete the seedling from the database
    $delete_sql = "DELETE FROM seedlings WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $seedling_id);

    if ($delete_stmt->execute()) {
        echo '<p>Seedling deleted successfully!</p>';
        echo '<meta http-equiv="refresh" content="2;url=admin.php">';
    } else {
        echo '<p>Error deleting seedling: ' . $delete_stmt->error . '</p>';
    }
} else {
    echo '<p>Seedling not found.</p>';
}
?>