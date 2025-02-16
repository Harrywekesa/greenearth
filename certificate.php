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
$program_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($program_id <= 0) {
    echo '<p>Invalid program ID.</p>';
    exit;
}

// Fetch user details
$user_sql = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo '<p>User not found.</p>';
    exit;
}

// Fetch program details
$program_sql = "SELECT * FROM training_programs WHERE id = ?";
$program_stmt = $conn->prepare($program_sql);
$program_stmt->bind_param("i", $program_id);
$program_stmt->execute();
$program_result = $program_stmt->get_result();

if ($program_result->num_rows > 0) {
    $program = $program_result->fetch_assoc();
} else {
    echo '<p>Program not found.</p>';
    exit;
}
?>

<section class="certificate">
    <h1>Certificate of Completion</h1>
    <p>This certifies that</p>
    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
    <p>has successfully completed the training program:</p>
    <h3><?php echo htmlspecialchars($program['title']); ?></h3>
    <p><strong>Duration:</strong> <?php echo htmlspecialchars($program['duration']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($program['location']); ?></p>
    <p><strong>Date Completed:</strong> <?php echo date("F j, Y"); ?></p>
    <p>Issued by GreenEarth.</p>
</section>

<style>
    /* Certificate Styling */
    .certificate {
        text-align: center;
        padding: 50px;
        border: 2px solid #4CAF50;
        border-radius: 10px;
        background-color: #f9f9f9;
        margin: 50px auto;
        max-width: 600px;
        font-family: Arial, sans-serif;
    }

    .certificate h1 {
        font-size: 28px;
        color: #4CAF50;
    }

    .certificate h2, .certificate h3 {
        font-size: 24px;
        margin: 10px 0;
    }

    .certificate p {
        font-size: 16px;
        margin: 5px 0;
    }
</style>

<script>
    window.onload = function () {
        showModal("Your certificate has been generated!");
    };
</script>

<?php include 'php/footer.php'; ?>