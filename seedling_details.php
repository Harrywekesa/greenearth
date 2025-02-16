<?php
include 'php/header.php';



if (isset($_GET['id'])) {
    $id = $_GET['id'];
    include 'php/db.php';

    $sql = "SELECT * FROM seedlings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo '<p>Seedling not found.</p>';
        exit;
    }
} else {
    echo '<p>No seedling ID provided.</p>';
    exit;
}
?>

<section class="seedling-details">
    <div class="details-header">
        <h1><?php echo htmlspecialchars($row['name']); ?></h1>
        <p><strong>Price:</strong> KES <?php echo number_format($row['price'], 2); ?></p>
    </div>
    <div class="details-content">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="detail-image">
        <div class="info">
            <h2>Description</h2>
            <p><?php echo htmlspecialchars($row['description']); ?></p>

            <h2>Details</h2>
            <ul>
                <li><strong>Region:</strong> <?php echo htmlspecialchars($row['region']); ?></li>
                <li><strong>Height:</strong> <?php echo htmlspecialchars($row['height']); ?></li>
                <li><strong>Fruiting:</strong> <?php echo htmlspecialchars($row['fruit']); ?></li>
                <li><strong>Purpose:</strong> <?php echo htmlspecialchars($row['purpose']); ?></li>
            </ul>

            <div class="buttons">
                <button onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
                <button onclick="buyNow(<?php echo $row['id']; ?>)">Buy Now</button>
            </div>
        </div>
    </div>
</section>

<script>
    function addToCart(id) {
        const quantity = prompt("Enter the number of seedlings you want:");
        if (quantity && !isNaN(quantity)) {
            window.location.href = 'add_to_cart.php?id=' + id + '&quantity=' + quantity;
        } else {
            alert("Invalid quantity!");
        }
    }

    function buyNow(id) {
        const quantity = prompt("Enter the number of seedlings you want:");
        if (quantity && !isNaN(quantity)) {
            window.location.href = 'checkout.php?id=' + id + '&quantity=' + quantity;
        } else {
            alert("Invalid quantity!");
        }
    }
</script>

<?php include 'php/footer.php'; ?>