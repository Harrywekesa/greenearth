<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/db.php';
include 'php/header.php';

// Check if the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Save the current page URL
        header("Location: login.php");
        exit;
    }
}

// Get form data
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$total_price = isset($_POST['total_price']) ? (float)$_POST['total_price'] : 0;
$address = isset($_POST['address']) ? trim($_POST['address']) : '';

if ($id <= 0 || $quantity <= 0 || $total_price <= 0 || empty($address)) {
    echo '<p>Invalid checkout details.</p>';
    exit;
}

// Insert order into the database
$user_id = $_SESSION['user_id'];
$sql = "INSERT INTO orders (user_id, seedling_id, quantity, total_price, order_date) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiid", $user_id, $id, $quantity, $total_price);

if ($stmt->execute()) {
    echo '<p>Purchase successful! Your order will be shipped to:</p>';
    echo '<p><strong>Address:</strong> ' . htmlspecialchars($address) . '</p>';
    echo '<a href="marketplace.php">Continue Shopping</a>';
} else {
    echo '<p>Error processing your order. Please try again later.</p>';
}
?>