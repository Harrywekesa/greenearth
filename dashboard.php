<?php include 'php/header.php'; ?>

<!-- Check if the user is logged in -->
<?php
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

include 'php/db.php';
$user_id = $_SESSION['user_id'];
?>

<section class="dashboard">
    <h2>My Dashboard</h2>

    <!-- Profile Section -->
    <section class="profile-section">
        <h3>Profile</h3>
        <p>Name: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p><a href="profile.php" class="button">Edit Profile</a></p>
    </section>

    <!-- Registered Events -->
    <section class="registered-events">
        <h3>Registered Events</h3>
        <?php
        $sql = "SELECT e.title, e.event_date, e.location FROM event_registrations er 
                INNER JOIN events e ON er.event_id = e.id 
                WHERE er.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<ul>';
            while ($row = $result->fetch_assoc()) {
                echo '<li><strong>' . htmlspecialchars($row['title']) . '</strong> - ' . date("F j, Y", strtotime($row['event_date'])) . ' (' . htmlspecialchars($row['location']) . ')</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No registered events yet.</p>';
        }
        ?>
    </section>

    <!-- Enrolled Training Programs -->
    <section class="enrolled-programs">
        <h3>Enrolled Training Programs</h3>
        <?php
        $sql = "SELECT tp.title, tp.duration, tp.location FROM program_enrollments pe 
                INNER JOIN training_programs tp ON pe.program_id = tp.id 
                WHERE pe.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<ul>';
            while ($row = $result->fetch_assoc()) {
                echo '<li>';
                echo '<strong>' . htmlspecialchars($row['title']) . '</strong> - ' . htmlspecialchars($row['duration']) . ' (' . htmlspecialchars($row['location']) . ')';
                echo ' | <a href="certificate.php?id=' . htmlspecialchars($row['id']) . '" target="_blank">View Certificate</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No enrolled programs yet.</p>';
        }
    </section>
</section>

<?php include 'php/footer.php'; ?>