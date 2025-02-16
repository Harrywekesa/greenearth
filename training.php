<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 
?>

<section class="training">
    <h1>Training Programs</h1>
    <p>Enhance your skills in sustainable forestry, eco-entrepreneurship, and climate adaptation.</p>

    <div class="training-programs">
        <?php
        include 'php/db.php';

        $sql = "SELECT * FROM training_programs ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="program-card">';
                echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                echo '<p><strong>Duration:</strong> ' . htmlspecialchars($row['duration']) . '</p>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<button onclick="enrollInProgram(' . $row['id'] . ')">Enroll Now</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No training programs available at the moment.</p>';
        }
        ?>
    </div>
</section>

<script>
    function enrollInProgram(programId) {
        const confirmMessage = "Are you sure you want to enroll in this program?";
        if (confirm(confirmMessage)) {
            window.location.href = 'enroll_program.php?id=' + programId;
        }
    }
</script>

<?php include 'php/footer.php'; ?>