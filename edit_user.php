<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

// Get user ID from URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    echo '<p>Invalid user ID.</p>';
    exit;
}

// Fetch user details
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo '<p>User not found.</p>';
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $town = trim($_POST['town']);

    // Basic validation
    if (empty($title) || empty($first_name) || empty($last_name) || empty($email)) {
        $error_message = 'All fields are required!';
    } else {
        // Update user details
        $update_sql = "UPDATE users SET title = ?, first_name = ?, last_name = ?, email = ?, phone = ?, town = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssssi", $title, $first_name, $last_name, $email, $phone, $town, $user_id);

        if ($stmt->execute()) {
            $success_message = 'User updated successfully!';
        } else {
            $error_message = 'Error updating user: ' . $stmt->error;
        }
    }
}

echo '<section class="edit-user">';
echo '<h2>Edit User</h2>';

if (!empty($error_message)) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}

if (!empty($success_message)) {
    echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
}

echo '<form method="POST">';
echo '<label for="title">Title:</label>';
echo '<select id="title" name="title" required>';
echo '<option value="Mr" ' . ($user['title'] === 'Mr' ? 'selected' : '') . '>Mr</option>';
echo '<option value="Mrs" ' . ($user['title'] === 'Mrs' ? 'selected' : '') . '>Mrs</option>';
echo '<option value="Ms" ' . ($user['title'] === 'Ms' ? 'selected' : '') . '>Ms</option>';
echo '<option value="Dr" ' . ($user['title'] === 'Dr' ? 'selected' : '') . '>Dr</option>';
echo '<option value="Prof" ' . ($user['title'] === 'Prof' ? 'selected' : '') . '>Prof</option>';
echo '</select>';

echo '<label for="first_name">First Name:</label>';
echo '<input type="text" id="first_name" name="first_name" value="' . htmlspecialchars($user['first_name'] ?? '') . '" required>';

echo '<label for="last_name">Last Name:</label>';
echo '<input type="text" id="last_name" name="last_name" value="' . htmlspecialchars($user['last_name'] ?? '') . '" required>';

echo '<label for="email">Email Address:</label>';
echo '<input type="email" id="email" name="email" value="' . htmlspecialchars($user['email'] ?? '') . '" required>';

echo '<label for="phone">Phone Number:</label>';
echo '<input type="text" id="phone" name="phone" value="' . htmlspecialchars($user['phone'] ?? '') . '">';

echo '<label for="town">Town of Residence:</label>';
echo '<input type="text" id="town" name="town" value="' . htmlspecialchars($user['town'] ?? '') . '">';

echo '<button type="submit" class="button">Save Changes</button>';
echo '<a href="admin.php" class="button">Cancel</a>';
echo '</form>';
echo '</section>';

include 'php/footer.php';