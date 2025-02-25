<?php
include 'php/db.php';

// Step 1: Delete the existing admin account
$delete_sql = "DELETE FROM users WHERE role = 'admin'";
$delete_stmt = $conn->prepare($delete_sql);

if ($delete_stmt->execute()) {
    echo "Existing admin account deleted successfully.<br>";
} else {
    echo "Error deleting admin account: " . $delete_stmt->error . "<br>";
}

// Step 2: Add a new admin account
$new_email = 'admin@gmail.com';
$new_password = 'admin123'; // Change this to your desired password
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT); // Hash the password securely

$insert_sql = "INSERT INTO users (email, password, role, first_name, last_name) VALUES (?, ?, 'admin', 'Admin', 'User')";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("ss", $new_email, $hashed_password);

if ($stmt->execute()) {
    echo "New admin account created successfully!<br>";
    echo "Email: " . htmlspecialchars($new_email) . "<br>";
    echo "Password: " . htmlspecialchars($new_password) . "<br>";
    echo "You can now log in with these credentials.";
} else {
    echo "Error creating new admin account: " . $stmt->error;
}
?>