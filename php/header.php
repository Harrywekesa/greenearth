<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenEarth</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo-and-title">
            <div class="logo">
                <img src="images/logo.png" alt="GreenEarth Logo">
            </div>
            <div class="site-title">
                <h1>GreenEarth</h1>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="marketplace.php">Marketplace</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="training.php">Training</a></li>
                <li><a href="contacts.php">Contact</a></li>
                <!-- Login/Logout Button -->
                <?php
                if (isset($_SESSION['user_id'])) {
                    // User is logged in
                    $username = $_SESSION['username'];
                    echo '<li><a href="dashboard.php">Dashboard</a></li>';
                    echo '<li><a href="logout.php">Logout (' . htmlspecialchars($username) . ')</a></li>';
                } else {
                    // User is not logged in
                    echo '<li><a href="login.php">Login</a></li>';
                    echo '<li><a href="register.php">Register</a></li>';
                }
                ?>
            </ul>
        </nav>
        <div id="success-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="success-message"></p>
    </div>
</div>
    </header>
    