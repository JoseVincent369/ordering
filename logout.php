<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the main page
header("Location: bakery_ordering_system.php");
exit();
