<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';


$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $town = trim($_POST['town']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
        // Set username to email if not provided
    $username = !empty($_POST['username']) ? trim($_POST['username']) : $email;

    // Rest of the code remains the same...

    // Basic validation
    if (
        empty($title) || empty($first_name) || empty($last_name) ||
        empty($email) || empty($phone) || empty($town) ||
        empty($password) || empty($confirm_password)
    ) {
        $error_message = 'All fields are required!';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match!';
    } else {
        include 'php/db.php';

        // Check if the email already exists
        $check_sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Email already registered. Please use a different email.';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user into the database
            $insert_sql = "INSERT INTO users (title, first_name, last_name, email, phone, town, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'user')";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sssssss", $title, $first_name, $last_name, $email, $phone, $town, $hashed_password);

            if ($stmt->execute()) {
                $success_message = 'Account created successfully! Please log in.';
                $_SESSION['redirect_url'] = 'login.php';
            } else {
                $error_message = 'Error creating account. Please try again later.';
            }
        }
    }
}

echo '<section class="register">';
echo '<h2>Register</h2>';

if (!empty($error_message)) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}

if (!empty($success_message)) {
    echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
}

echo '<form method="POST">';
echo '<label for="title">Title:</label>';
echo '<select id="title" name="title" required>';
echo '<option value="Mr">Mr</option>';
echo '<option value="Mrs">Mrs</option>';
echo '<option value="Ms">Ms</option>';
echo '<option value="Dr">Dr</option>';
echo '<option value="Prof">Prof</option>';
echo '</select>';

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

echo '<label for="confirm_password">Confirm Password:</label>';
echo '<input type="password" id="confirm_password" name="confirm_password" required>';

echo '<div class="form-actions">';
echo '<button type="submit">Register</button>';
echo '</div>';

echo '<p>Already have an account? <a href="login.php">Log in here</a>.</p>';
echo '</form>';
echo '</section>';

include 'php/footer.php';