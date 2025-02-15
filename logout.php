<?php
session_start();
session_destroy();
header("Location: marketplace.php");
exit;
?>