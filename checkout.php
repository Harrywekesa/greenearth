<?php
include 'php/db.php';

// Check if the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Save the current page URL
        header("Location: login.php");
        exit;
    }
}

// Get parameters from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if ($id <= 0 || $quantity <= 0) {
    echo '<p>Invalid request.</p>';
    exit;
}

// Fetch seedling details
$sql = "SELECT * FROM seedlings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $price_per_item = (float)$row['price'];
    $total_price = $price_per_item * $quantity;

    echo '<section class="checkout">';
    echo '<h2>Checkout</h2>';
    echo '<p>You are purchasing <strong>' . htmlspecialchars($row['name']) . '</strong>.</p>';
    echo '<p>Quantity: ' . $quantity . '</p>';
    echo '<p>Price per Item: KES ' . number_format($price_per_item, 2) . '</p>';
    echo '<p>Total Price: KES ' . number_format($total_price, 2) . '</p>';

    echo '<form action="process_checkout.php" method="POST">';
    echo '<label for="address">Shipping Address:</label>';
    echo '<textarea id="address" name="address" required></textarea>';
    echo '<input type="hidden" name="id" value="' . $id . '">';
    echo '<input type="hidden" name="quantity" value="' . $quantity . '">';
    echo '<input type="hidden" name="total_price" value="' . $total_price . '">';
    echo '<button type="submit">Complete Purchase</button>';
    echo '</form>';
    echo '</section>';
} else {
    echo '<p>Seedling not found.</p>';
}

include 'php/footer.php';
?>