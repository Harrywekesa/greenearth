<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/db.php';
include 'php/header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the index of the item to remove
$index = isset($_GET['index']) ? (int)$_GET['index'] : -1;

if ($index >= 0 && isset($_SESSION['cart'][$index])) {
    array_splice($_SESSION['cart'], $index, 1);
    echo '<p>Item removed from cart successfully!</p>';
} else {
    echo '<p>Invalid request.</p>';
}

echo '<a href="cart.php">Back to Cart</a>';
?>