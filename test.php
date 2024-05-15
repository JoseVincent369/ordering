<?php

require_once 'phpqrcode/qrlib.php';

// Folder to save QR code images
$path = 'qrcodes/';

// Check if the directory exists, create it if it doesn't
if (!is_dir($path)) {
    mkdir($path);
}

// Generate a unique file name based on the current date and time
$file = $path . date("Y-m-d-h-i-s") . '.png';

// Text to be encoded into the QR code
$text = "jeal bdfdfdiot";

// Generate the QR code
QRcode::png($text, $file, 'H', 10, 2);

// Display the QR code image
echo '<img src="' . $file . '">';

