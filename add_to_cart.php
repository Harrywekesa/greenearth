<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/db.php';
include 'php/header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
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

    // Calculate total price
    $price_per_item = (float)$row['price'];
    $total_price = $price_per_item * $quantity;

    // Add to cart (stored in session)
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    $exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] += $quantity;
            $item['total_price'] = $item['price_per_item'] * $item['quantity'];
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $_SESSION['cart'][] = [
            'id' => $id,
            'name' => $row['name'],
            'price_per_item' => $price_per_item,
            'quantity' => $quantity,
            'total_price' => $total_price
        ];
    }

    echo '<p>' . htmlspecialchars($row['name']) . ' added to cart successfully!</p>';
    echo '<a href="marketplace.php">Continue Shopping</a>';
    echo '<a href="cart.php">View Cart</a>';
} else {
    echo '<p>Seedling not found.</p>';
}
?>