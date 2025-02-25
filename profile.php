<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 

?>

<!-- Check if the user is logged in -->
<?php
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

include 'php/db.php';
$user_id = $_SESSION['user_id'];

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
?>

<section class="profile">
    <h2>My Profile</h2>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $town = $_POST['town'];

        // Update user details
        $update_sql = "UPDATE users SET title = ?, first_name = ?, last_name = ?, email = ?, phone = ?, town = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssssi", $title, $first_name, $last_name, $email, $phone, $town, $user_id);

        if ($stmt->execute()) {
            echo '<script>showModal("Profile updated successfully!");</script>';
            $user['title'] = $title;
            $user['first_name'] = $first_name;
            $user['last_name'] = $last_name;
            $user['email'] = $email;
            $user['phone'] = $phone;
            $user['town'] = $town;
        } else {
            echo '<script>showModal("Error updating profile. Please try again later.");</script>';
        }
    }
    ?>

    <form action="profile.php" method="POST">
        <label for="title">Title (Mr/Mrs):</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($user['title']); ?>" required>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

        <label for="town">Town of Residence:</label>
        <input type="text" id="town" name="town" value="<?php echo htmlspecialchars($user['town']); ?>" required>

        <button type="submit">Update Profile</button>
    </form>
</section>

<?php include 'php/footer.php'; ?>