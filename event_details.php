<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    include 'php/db.php';

    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo '<p>Event not found.</p>';
        exit;
    }
} else {
    echo '<p>No event ID provided.</p>';
    exit;
}
?>

<section class="event-details">
    <div class="details-header">
        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
        <p><strong>Date:</strong> <?php echo date("F j, Y", strtotime($event['event_date'])); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
    </div>
    <div class="details-content">
        <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        <button onclick="registerForEvent(<?php echo $event['id']; ?>)">Register for Event</button>
    </div>
</section>

<script>
    function registerForEvent(eventId) {
        const confirmMessage = "Are you sure you want to register for this event?";
        if (confirm(confirmMessage)) {
            window.location.href = 'register_event.php?id=' + eventId;
        }
    }
</script>

<?php include 'php/footer.php'; ?>