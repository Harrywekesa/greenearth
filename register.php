<?php
include 'php/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $town = $_POST['town'];
    $password = $_POST['password'];

    if (empty($title) || empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($town) || empty($password)) {
        echo '<p>All fields are required!</p>';
        exit;
    }

    include 'php/db.php';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (title, first_name, last_name, email, phone, town, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'user')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $title, $first_name, $last_name, $email, $phone, $town, $hashed_password);

    if ($stmt->execute()) {
        echo '<script>showModal("Account created successfully! Please log in.");</script>';
        echo '<meta http-equiv="refresh" content="2;url=login.php">';
    } else {
        echo '<p>Error creating account. Please try again later.</p>';
    }
}

echo '<section class="register">';
echo '<h2>Register</h2>';
echo '<form method="POST">';
echo '<label for="title">Title (Mr/Mrs):</label>';
echo '<input type="text" id="title" name="title" required>';
echo '<label for="first_name">First Name:</label>';
echo '<input type="text" id="first_name" name="first_name" required>';
echo '<label for="last_name">Last Name:</label>';
echo '<input type="text" id="last_name" name="last_name" required>';
echo '<label for="email">Email Address:</label>';
echo '<input type="email" id="email" name="email" required>';
echo '<label for="phone">Phone Number:</label>';
echo '<input type="text" id="phone" name="phone" required>';
echo '<label for="town">Town of Residence:</label>';
echo '<input type="text" id="town" name="town" required>';
echo '<label for="password">Password:</label>';
echo '<input type="password" id="password" name="password" required>';
echo '<button type="submit">Register</button>';
echo '</form>';
echo '</section>';

include 'php/footer.php';