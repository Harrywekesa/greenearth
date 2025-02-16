<?php include 'php/header.php'; ?>

<!-- Marketplace Banner -->
<section class="marketplace-banner">
    <h1>Online Tree Seedling Marketplace</h1>
    <p>Discover and purchase high-quality tree seedlings tailored to your geographical needs.</p>
</section>
<!-- View Cart Button -->
<section class="cart-button">
    <a href="cart.php" class="button">View Cart</a>
</section>

<section class="filter-section">
    <div class="filters">
        <form id="filter-form" method="GET">
            <!-- Region Filter -->
            <label for="region">Region:</label>
            <select id="region" name="region" onchange="this.form.submit()">
                <option value="all" <?php echo (!isset($_GET['region']) || $_GET['region'] == 'all') ? 'selected' : ''; ?>>All Regions</option>
                <option value="nairobi" <?php echo (isset($_GET['region']) && $_GET['region'] == 'nairobi') ? 'selected' : ''; ?>>Nairobi</option>
                <option value="kitale" <?php echo (isset($_GET['region']) && $_GET['region'] == 'kitale') ? 'selected' : ''; ?>>Kitale</option>
                <option value="eldoret" <?php echo (isset($_GET['region']) && $_GET['region'] == 'eldoret') ? 'selected' : ''; ?>>Eldoret</option>
            </select>

            <!-- Price Range Filter -->
            <label for="price">Price Range:</label>
            <input type="range" id="price" name="price" min="0" max="500" value="<?php echo isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '500'; ?>" oninput="this.form.submit()">
            <span>Selected Price: KES <?php echo isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '500'; ?></span>

            <!-- Tree Height Filter -->
            <label for="height">Tree Height:</label>
            <select id="height" name="height" onchange="this.form.submit()">
                <option value="all" <?php echo (!isset($_GET['height']) || $_GET['height'] == 'all') ? 'selected' : ''; ?>>All Heights</option>
                <option value="tall" <?php echo (isset($_GET['height']) && $_GET['height'] == 'tall') ? 'selected' : ''; ?>>Tall Trees</option>
                <option value="short" <?php echo (isset($_GET['height']) && $_GET['height'] == 'short') ? 'selected' : ''; ?>>Short Trees</option>
            </select>

            <!-- Fruiting Trees Filter -->
            <label for="fruit">Fruiting Trees:</label>
            <select id="fruit" name="fruit" onchange="this.form.submit()">
                <option value="all" <?php echo (!isset($_GET['fruit']) || $_GET['fruit'] == 'all') ? 'selected' : ''; ?>>All Types</option>
                <option value="edible" <?php echo (isset($_GET['fruit']) && $_GET['fruit'] == 'edible') ? 'selected' : ''; ?>>Edible Fruiting Trees</option>
                <option value="non-edible" <?php echo (isset($_GET['fruit']) && $_GET['fruit'] == 'non-edible') ? 'selected' : ''; ?>>Non-Edible Trees</option>
            </select>

            <!-- Purpose Filter -->
            <label for="purpose">Purpose:</label>
            <select id="purpose" name="purpose" onchange="this.form.submit()">
                <option value="all" <?php echo (!isset($_GET['purpose']) || $_GET['purpose'] == 'all') ? 'selected' : ''; ?>>All Purposes</option>
                <option value="ornamental" <?php echo (isset($_GET['purpose']) && $_GET['purpose'] == 'ornamental') ? 'selected' : ''; ?>>Ornamental</option>
                <option value="timber" <?php echo (isset($_GET['purpose']) && $_GET['purpose'] == 'timber') ? 'selected' : ''; ?>>Timber</option>
                <option value="shade" <?php echo (isset($_GET['purpose']) && $_GET['purpose'] == 'shade') ? 'selected' : ''; ?>>Shade</option>
            </select>
        </form>
    </div>
</section>

<section class="seedlings">
    <h2>Browse Available Seedlings</h2>
    <div class="seedlings-grid">
        <?php
        include 'php/db.php';

        // Get filter parameters from URL
        $region = isset($_GET['region']) ? $_GET['region'] : 'all';
        $price = isset($_GET['price']) ? (int)$_GET['price'] : 500;
        $height = isset($_GET['height']) ? $_GET['height'] : 'all';
        $fruit = isset($_GET['fruit']) ? $_GET['fruit'] : 'all';
        $purpose = isset($_GET['purpose']) ? $_GET['purpose'] : 'all';

        // Build SQL query based on filters
        $sql = "SELECT * FROM seedlings WHERE 1=1";

        if ($region !== 'all') {
            $sql .= " AND region = '$region'";
        }

        if ($price > 0) {
            $sql .= " AND price <= $price";
        }

        if ($height !== 'all') {
            $sql .= " AND height = '$height'";
        }

        if ($fruit !== 'all') {
            $sql .= " AND fruit = '$fruit'";
        }

        if ($purpose !== 'all') {
            $sql .= " AND purpose = '$purpose'";
        }

        // Pagination setup
        $items_per_page = 9;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $items_per_page;

        $sql .= " ORDER BY created_at DESC LIMIT $items_per_page OFFSET $offset";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<a href="seedling_details.php?id=' . $row['id'] . '" class="seedling-card">';
                echo '<div class="card-content">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p><strong>Price:</strong> KES ' . number_format($row['price'], 2) . '</p>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo '<p>No seedlings matching your criteria.</p>';
        }
        ?>
    </div>
</section>+

<!-- Pagination -->
<section class="pagination">
    <?php
    // Count total items for pagination
    $count_sql = "SELECT COUNT(*) AS total FROM seedlings WHERE 1=1";

    if ($region !== 'all') {
        $count_sql .= " AND region = '$region'";
    }

    if ($price > 0) {
        $count_sql .= " AND price <= $price";
    }

    $count_result = $conn->query($count_sql);
    $total_items = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_items / $items_per_page);

    if ($total_pages > 1) {
        echo '<nav aria-label="Pagination">';
        echo '<ul class="pagination-list">';

        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo '<li class="pagination-item ' . $active . '"><a href="?region=' . $region . '&price=' . $price . '&page=' . $i . '">' . $i . '</a></li>';
        }

        echo '</ul>';
        echo '</nav>';
    }
    ?>
</section>

<?php include 'php/footer.php'; ?>