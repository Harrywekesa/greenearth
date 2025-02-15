<?php
include 'php/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include 'php/db.php';

    $sql = "SELECT * FROM users WHERE email = ? AND password = PASSWORD(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: marketplace.php");
        exit;
    } else {
        echo '<p>Invalid email or password.</p>';
    }
}

echo '<section class="login">';
echo '<h2>Login</h2>';
echo '<form method="POST">';
echo '<label for="email">Email:</label>';
echo '<input type="email" id="email" name="email" required>';
echo '<label for="password">Password:</label>';
echo '<input type="password" id="password" name="password" required>';
echo '<button type="submit">Login</button>';
echo '</form>';
echo '<p>Don\'t have an account? <a href="register.php">Register here</a>.</p>';
echo '</section>';

include 'php/footer.php';
?>