<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/db.php';
include 'php/header.php';

// Check if an admin account already exists
$check_sql = "SELECT * FROM users WHERE role = 'admin'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    echo '<p class="error-message">An admin account already exists. You cannot register another admin.</p>';
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    // Basic validation
    if (empty($email) || empty($password) || empty($confirm_password) || empty($first_name) || empty($last_name)) {
        $error_message = 'All fields are required!';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match!';
    } else {
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert the admin account
        $insert_sql = "INSERT INTO users (email, password, role, first_name, last_name) VALUES (?, ?, 'admin', ?, ?)";
        $stmt = $conn->prepare($insert_sql);

        if (!$stmt) {
            $error_message = 'Error preparing SQL statement: ' . $conn->error;
        } else {
            $stmt->bind_param("ssss", $email, $hashed_password, $first_name, $last_name);

            if ($stmt->execute()) {
                $success_message = 'Admin account created successfully! You can now log in.';
                header("Refresh:2; url=login.php"); // Redirect after success
                exit;
            } else {
                $error_message = 'Error creating admin account: ' . $stmt->error;
            }
        }
    }
}

echo '<section class="admin-register">';
echo '<h2>Register Admin Account</h2>';

if (!empty($error_message)) {
    echo '<p class="error-message">' . htmlspecialchars($error_message) . '</p>';
}

if (!empty($success_message)) {
    echo '<p class="success-message">' . htmlspecialchars($success_message) . '</p>';
    echo '<p><a href="login.php" class="button">Log In</a></p>';
    exit;
}

echo '<form method="POST">';
echo '<label for="first_name">First Name:</label>';
echo '<input type="text" id="first_name" name="first_name" required>';

echo '<label for="last_name">Last Name:</label>';
echo '<input type="text" id="last_name" name="last_name" required>';

echo '<label for="email">Email Address:</label>';
echo '<input type="email" id="email" name="email" required>';

echo '<label for="password">Password:</label>';
echo '<input type="password" id="password" name="password" required>';

echo '<label for="confirm_password">Confirm Password:</label>';
echo '<input type="password" id="confirm_password" name="confirm_password" required>';

echo '<button type="submit" class="button">Register Admin</button>';
echo '</form>';
echo '</section>';

include 'php/footer.php';