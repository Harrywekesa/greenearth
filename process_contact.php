<?php
include 'php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (empty($name) || empty($email) || empty($message)) {
        echo '<p>All fields are required!</p>';
        exit;
    }

    // Save the message in the database
    $sql = "INSERT INTO contacts (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo '<p>Thank you for reaching out! We will get back to you soon.</p>';
    } else {
        echo '<p>Error submitting your message. Please try again later.</p>';
    }
}
?>