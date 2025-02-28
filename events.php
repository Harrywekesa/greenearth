<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 
?>

<!-- Events Banner -->
<section class="events-banner">
    <h1>Upcoming Reforestation Events</h1>
    <p>Join us in planting trees and restoring nature across Kenya!</p>
</section>

<!-- Filter Section -->
<section class="filter-section">
    <div class="filters">
        <form id="filter-form" method="GET">
            <label for="location">Location:</label>
            <select id="location" name="location" onchange="this.form.submit()">
                <option value="all" <?php echo (!isset($_GET['location']) || $_GET['location'] == 'all') ? 'selected' : ''; ?>>All Locations</option>
                <option value="nairobi" <?php echo (isset($_GET['location']) && $_GET['location'] == 'nairobi') ? 'selected' : ''; ?>>Nairobi</option>
                <option value="kitale" <?php echo (isset($_GET['location']) && $_GET['location'] == 'kitale') ? 'selected' : ''; ?>>Kitale</option>
                <option value="eldoret" <?php echo (isset($_GET['location']) && $_GET['location'] == 'eldoret') ? 'selected' : ''; ?>>Eldoret</option>
            </select>

            <label for="date">Date Range:</label>
            <input type="date" id="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>" oninput="this.form.submit()">
        </form>
    </div>
</section>

<!-- Events Section -->
<section class="events-grid">
    <h2>Browse Upcoming Events</h2>
    <div class="event-cards">
        <?php
        include 'php/db.php';

        // Get filter parameters from URL
        $location = isset($_GET['location']) ? $_GET['location'] : 'all';
        $date = isset($_GET['date']) ? $_GET['date'] : '';

        // Pagination setup
        $items_per_page = 6;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $items_per_page;

        // Build SQL query based on filters
        $sql = "SELECT * FROM events WHERE 1=1";

        if ($location !== 'all') {
            $sql .= " AND location = '$location'";
        }

        if (!empty($date)) {
            $sql .= " AND event_date >= '$date'";
        }

        $sql .= " ORDER BY event_date ASC LIMIT $items_per_page OFFSET $offset";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="event-card">';
                echo '<h3><a href="event_details.php?id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a></h3>';
                echo '<p><strong>Date:</strong> ' . date("F j, Y", strtotime($row['event_date'])) . '</p>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<button onclick="registerForEvent(' . $row['id'] . ')">Register</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No events matching your criteria.</p>';
        }
        ?>
    </div>
</section>

<!-- Pagination -->
<section class="pagination">
    <?php
    // Count total items for pagination
    $count_sql = "SELECT COUNT(*) AS total FROM events WHERE 1=1";

    if ($location !== 'all') {
        $count_sql .= " AND location = '$location'";
    }

    if (!empty($date)) {
        $count_sql .= " AND event_date >= '$date'";
    }

    $count_result = $conn->query($count_sql);
    $total_items = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_items / $items_per_page);

    if ($total_pages > 1) {
        echo '<nav aria-label="Pagination">';
        echo '<ul class="pagination-list">';

        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo '<li class="pagination-item ' . $active . '"><a href="?location=' . $location . '&date=' . $date . '&page=' . $i . '">' . $i . '</a></li>';
        }

        echo '</ul>';
        echo '</nav>';
    }
    ?>
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