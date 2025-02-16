<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; ?>

<section class="fruiting-trees">
    <h1>Fruiting Trees</h1>
    <p>Explore trees that produce edible fruits:</p>

    <div class="tree-cards">
        <?php
        include 'php/db.php';

        $sql = "SELECT * FROM seedlings WHERE fruit = 'edible'";
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
            echo '<p>No fruiting trees found.</p>';
        }
        ?>
    </div>
</section>

<?php include 'php/footer.php'; ?>