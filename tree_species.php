<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 
?>

<script>
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', () => {
            const details = card.querySelector('.details');
            if (details) {
                details.style.display = details.style.display === 'block' ? 'none' : 'block';
            }
        });
    });
</script>

<style>
    .details {
        display: none;
        background-color: #f9f9f9;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>

<section class="tree-species">
    <h1>Tree Species Information Hub</h1>
    <p>Explore different categories of trees and learn about their ecological benefits.</p>

    <div class="categories">
        <a href="tall_trees.php" class="category-card">
            <h2>Tall Trees</h2>
            <p>Discover trees that grow tall and provide shade.</p>
        </a>

        <a href="short_trees.php" class="category-card">
            <h2>Short Trees</h2>
            <p>Learn about small trees suitable for urban areas.</p>
        </a>

        <a href="fruiting_trees.php" class="category-card">
            <h2>Fruiting Trees</h2>
            <p>Find out which trees produce edible fruits.</p>
        </a>
    </div>
</section>

<?php include 'php/footer.php'; ?>