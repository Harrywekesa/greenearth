<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/db.php';

// Destroy the session
session_start();
session_destroy();

// Redirect to the homepage
header("Location: index.php");
exit;
?>