<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

// Save the current page URL for redirection
if (!isset($_SESSION['redirect_url'])) {
    $_SESSION['redirect_url'] = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'index.php';
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    include 'php/db.php';

    // Fetch user by email
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

            // Check user role
            $_SESSION['role'] = $user['role'];

            // Set remember me cookie if checked
            if ($remember_me) {
                setcookie('user_id', $user['id'], time() + (86400 * 30), "/"); // Cookie expires in 30 days
            }

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin.php");
                exit;
            } else {
                $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
                unset($_SESSION['redirect_url']); // Clear the redirect URL
                header("Location: " . $redirect_url);
                exit;
            }
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
echo '<div class="form-actions">';
echo '<label><input type="checkbox" id="remember_me" name="remember_me"> Remember Me</label>';
echo '<button type="submit">Login</button>';
echo '</div>';
echo '<p>Don\'t have an account? <a href="register.php">Register here</a>.</p>';
echo '</form>';
echo '</section>';

include 'php/footer.php';