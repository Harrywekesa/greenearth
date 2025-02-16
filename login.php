<?php
include 'php/header.php';

// Save the current page URL for redirection
if (!isset($_SESSION['redirect_url'])) {
    $_SESSION['redirect_url'] = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include 'php/db.php';

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the saved URL or homepage
            $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
            unset($_SESSION['redirect_url']); // Clear the redirect URL
            header("Location: " . $redirect_url);
            exit;
        } else {
            $error_message = 'Invalid email or password.';
        }
    } else {
        $error_message = 'User not found.';
    }
}

echo '<section class="login">';
echo '<h2>Login</h2>';
if (!empty($error_message)) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}
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