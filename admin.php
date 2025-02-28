<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';
?>

<section class="admin-dashboard">
    <h2>Admin Dashboard</h2>

    <!-- Manage Users -->
    <section class="manage-users">
        <h3>Manage Users</h3>
        <a href="add_user.php" class="button">Add New User</a>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Town</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, title, first_name, last_name, email, town FROM users ORDER BY created_at DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['title'] . ' ' . $row['first_name'] . ' ' . $row['last_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['town'] ?? 'Not specified') . '</td>';
                        echo '<td>';
                        echo '<a href="edit_user.php?id=' . $row['id'] . '" class="action-link">Edit</a> | ';
                        echo '<a href="delete_user.php?id=' . $row['id'] . '" class="action-link" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No users available.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Manage Events -->
    <section class="manage-events">
        <h3>Manage Events</h3>
        <a href="add_event.php" class="button">Add New Event</a>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM events ORDER BY event_date DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                        echo '<td>' . date("F j, Y", strtotime($row['event_date'])) . '</td>';
                        echo '<td>' . htmlspecialchars($row['location']) . '</td>';
                        echo '<td>';
                        echo '<a href="edit_event.php?id=' . $row['id'] . '" class="action-link">Edit</a> | ';
                        echo '<a href="delete_event.php?id=' . $row['id'] . '" class="action-link" onclick="return confirm(\'Are you sure you want to delete this event?\')">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No events available.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Manage Training Programs -->
    <section class="manage-programs">
        <h3>Manage Training Programs</h3>
        <a href="add_program.php" class="button">Add New Program</a>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM training_programs ORDER BY created_at DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['duration']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['location'] ?? 'Not specified') . '</td>';
                        echo '<td>';
                        echo '<a href="edit_program.php?id=' . $row['id'] . '" class="action-link">Edit</a> | ';
                        echo '<a href="delete_program.php?id=' . $row['id'] . '" class="action-link" onclick="return confirm(\'Are you sure you want to delete this program?\')">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No training programs available.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <section class="manage-seedlings">
    <h3>Manage Seedlings</h3>
    <a href="add_seedling.php" class="button">Add New Seedling</a>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Region</th>
                <th>Height</th>
                <th>Fruiting</th>
                <th>Purpose</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM seedlings ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>KES ' . number_format($row['price'], 2) . '</td>';
                    echo '<td>' . htmlspecialchars($row['region']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['height']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['fruit']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['purpose']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['stock']) . '</td>'; // Use 'stock' field
                    echo '<td><img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width: 50px; height: auto;"></td>';
                    echo '<td>';
                    echo '<a href="edit_seedling.php?id=' . $row['id'] . '" class="action-link">Edit</a> | ';
                    echo '<a href="delete_seedling.php?id=' . $row['id'] . '" class="action-link" onclick="return confirm(\'Are you sure you want to delete this seedling?\')">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="9">No seedlings available.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</section>
</section>

<?php include 'php/footer.php'; ?>