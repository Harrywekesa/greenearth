<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

// Get seedling ID from URL
$seedling_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($seedling_id <= 0) {
    echo '<p>Invalid seedling ID.</p>';
    exit;
}

include 'php/db.php';

// Fetch seedling details
$sql = "SELECT * FROM seedlings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seedling_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $seedling = $result->fetch_assoc();
} else {
    echo '<p>Seedling not found.</p>';
    exit;
}
?>

<section class="seedling-details">
    <div class="details-header">
        <h1><?php echo htmlspecialchars($seedling['name'] ?? 'Unknown Seedling'); ?></h1>
        <p><strong>Price:</strong> KES <?php echo number_format($seedling['price'] ?? 0, 2); ?></p>
    </div>

    <div class="details-content">
        <img src="<?php echo htmlspecialchars($seedling['image'] ?? 'images/default-tree.jpg'); ?>" alt="<?php echo htmlspecialchars($seedling['name'] ?? 'Unknown Seedling'); ?>" class="detail-image">

        <h2>Description</h2>
        <p><?php echo nl2br(htmlspecialchars($seedling['description'] ?? 'No description available.')); ?></p>

        <h2>Details</h2>
        <ul>
            <li><strong>Region:</strong> <?php echo htmlspecialchars($seedling['region'] ?? 'Not specified'); ?></li>
            <li><strong>Height:</strong> <?php echo htmlspecialchars($seedling['height'] ?? 'Not specified'); ?></li>
            <li><strong>Fruiting:</strong> <?php echo htmlspecialchars($seedling['fruit'] ?? 'Not specified'); ?></li>
            <li><strong>Purpose:</strong> <?php echo htmlspecialchars($seedling['purpose'] ?? 'Not specified'); ?></li>
        </ul>

        <div class="buttons">
            <button onclick="addToCart(<?php echo $seedling_id; ?>)">Add to Cart</button>
            <button onclick="buyNow(<?php echo $seedling_id; ?>)">Buy Now</button>
        </div>
    </div>
</section>

<script>
    function addToCart(id) {
        const confirmMessage = "Are you sure you want to add this seedling to your cart?";
        if (confirm(confirmMessage)) {
            window.location.href = 'add_to_cart.php?id=' + id;
        }
    }

    function buyNow(id) {
        const confirmMessage = "Are you sure you want to purchase this seedling?";
        if (confirm(confirmMessage)) {
            window.location.href = 'checkout.php?id=' + id;
        }
    }
</script>

<?php include 'php/footer.php'; ?>