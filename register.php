<?php
include 'php/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    include 'php/db.php';

    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, PASSWORD(?), 'user')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo '<p>Account created successfully! <a href="login.php">Log in</a>.</p>';
    } else {
        echo '<p>Error creating account. Please try again later.</p>';
    }
}

echo '<section class="register">';
echo '<h2>Register</h2>';
echo '<form method="POST">';
echo '<label for="username">Username:</label>';
echo '<input type="text" id="username" name="username" required>';
echo '<label for="email">Email:</label>';
echo '<input type="email" id="email" name="email" required>';
echo '<label for="password">Password:</label>';
echo '<input type="password" id="password" name="password" required>';
echo '<button type="submit">Register</button>';
echo '</form>';
echo '</section>';

include 'php/footer.php';
?>