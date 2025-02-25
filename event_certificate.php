<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

include 'php/db.php';
$user_id = $_SESSION['user_id'];
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($event_id <= 0) {
    echo '<p>Invalid event ID.</p>';
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

// Fetch event details
$event_sql = "SELECT * FROM events WHERE id = ?";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();

if ($event_result->num_rows > 0) {
    $event = $event_result->fetch_assoc();
} else {
    echo '<p>Event not found.</p>';
    exit;
}
?>

<section class="certificate">
    <header class="certificate-header">
        <!-- Logos -->
        <img src="images/logo.png" alt="GreenEarth Logo" class="greenearth-logo">
        <img src="images/kenya-logo.png" alt="Republic of Kenya Logo" class="kenya-logo">
    </header>

    <h1>Certificate of Participation</h1>
    <p>This certifies that</p>
    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name'] ?? 'Guest'); ?></h2>
    <p>participated in the event:</p>
    <h3><?php echo htmlspecialchars($event['title'] ?? 'Unknown Event'); ?></h3>
    <p><strong>Date:</strong> <?php echo htmlspecialchars(date("F j, Y", strtotime($event['event_date'] ?? ''))); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location'] ?? 'Not specified'); ?></p>
    <p><strong>Date Participated:</strong> <?php echo date("F j, Y"); ?></p>
    <p>Issued by GreenEarth in collaboration with the Republic of Kenya.</p>
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
        max-width: 800px;
        font-family: Arial, sans-serif;
        position: relative; /* For logo positioning */
    }

    .certificate-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .greenearth-logo, .kenya-logo {
        width: 100px; /* Set fixed size for logos */
        height: auto;
    }

    .greenearth-logo {
        margin-left: 20px;
    }

    .kenya-logo {
        margin-right: 20px;
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
        showModal("Your participation certificate has been generated!");
    };
</script>

<?php include 'php/footer.php'; ?>