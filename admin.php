<?php include 'php/header.php'; ?>

<!-- Check if the user is an admin -->
<?php
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
        <table border="1" cellpadding="10">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Town</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT id, title, first_name, last_name, email, town FROM users ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['title'] . ' ' . $row['first_name'] . ' ' . $row['last_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['town']) . '</td>';
                    echo '<td>';
                    echo '<a href="edit_user.php?id=' . $row['id'] . '">Edit</a> | ';
                    echo '<a href="delete_user.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No users available.</td></tr>';
            }
            ?>
        </table>
    </section>

    <!-- Manage Events -->
    <section class="manage-events">
        <h3>Manage Events</h3>
        <a href="add_event.php" class="button">Add New Event</a>
        <table border="1" cellpadding="10">
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
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
                    echo '<a href="edit_event.php?id=' . $row['id'] . '">Edit</a> | ';
                    echo '<a href="delete_event.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this event?\')">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No events available.</td></tr>';
            }
            ?>
        </table>
    </section>

    <!-- Manage Training Programs -->
    <section class="manage-programs">
        <h3>Manage Training Programs</h3>
        <a href="add_program.php" class="button">Add New Program</a>
        <table border="1" cellpadding="10">
            <tr>
                <th>Title</th>
                <th>Duration</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT * FROM training_programs ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['duration']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['location']) . '</td>';
                    echo '<td>';
                    echo '<a href="edit_program.php?id=' . $row['id'] . '">Edit</a> | ';
                    echo '<a href="delete_program.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this program?\')">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No training programs available.</td></tr>';
            }
            ?>
        </table>
    </section>
</section>

<?php include 'php/footer.php'; ?>