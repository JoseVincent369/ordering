<?php
session_start();

require_once "phpqrcode/qrlib.php"; // Include the QR code library

// Retrieve order details from session
$orderDetails = $_SESSION['cart'];

// Encode order details as JSON
$orderJSON = json_encode($orderDetails);

// Generate QR code
QRcode::png($orderJSON); // Generates the QR code image

// Output image directly to browser
echo '<img src="' . $file . '">';

exit;

