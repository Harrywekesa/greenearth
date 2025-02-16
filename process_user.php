<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/db.php';
include 'php/header.php';

$title = $_POST['title'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$town = $_POST['town'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

$sql = "INSERT INTO users (title, first_name, last_name, email, phone, town, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'user')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $title, $first_name, $last_name, $email, $phone, $town, $password);

if ($stmt->execute()) {
    echo '<script>showModal("User added successfully!");</script>';
} else {
    echo '<script>showModal("Error adding user. Please try again later.");</script>';
}
?>