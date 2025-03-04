<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 
?>

<section class="tall-trees">
    <h1>Tall Trees</h1>
    <p>Here are some tall tree species suitable for reforestation:</p>

    <div class="tree-cards">
        <?php
        include 'php/db.php';

        $sql = "SELECT * FROM seedlings WHERE height = 'tall'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="tree-card">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No tall trees found.</p>';
        }
        ?>
    </div>
</section>

<?php include 'php/footer.php'; ?>