<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

// Check if the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Save the current page URL
        header("Location: login.php");
        exit;
    }
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<p>Your cart is empty.</p>';
    echo '<a href="marketplace.php">Continue Shopping</a>';
    exit;
}

$total_cart_price = 0;

echo '<section class="cart">';
echo '<h2>Your Cart</h2>';
echo '<table border="1" cellpadding="10">';
echo '<tr><th>Name</th><th>Price per Item</th><th>Quantity</th><th>Total Price</th><th>Action</th></tr>';

foreach ($_SESSION['cart'] as $index => $item) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($item['name']) . '</td>';
    echo '<td>KES ' . number_format($item['price_per_item'], 2) . '</td>';
    echo '<td>' . $item['quantity'] . '</td>';
    echo '<td>KES ' . number_format($item['total_price'], 2) . '</td>';
    echo '<td><a href="remove_from_cart.php?index=' . $index . '">Remove</a></td>';
    echo '</tr>';
    $total_cart_price += $item['total_price'];
}

echo '<tr><td colspan="3"><strong>Total:</strong></td><td><strong>KES ' . number_format($total_cart_price, 2) . '</strong></td><td></td></tr>';
echo '</table>';
echo '<a href="checkout.php">Proceed to Checkout</a>';
echo '</section>';

include 'php/footer.php';
?>