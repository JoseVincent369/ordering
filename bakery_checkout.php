<?php
require_once 'connection.php';

session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Update with your MySQL username
$password = ""; // Update with your MySQL password
$dbname = "bakery"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getDBConnection() {
    global $conn;
    return $conn;
}

// QR code generation
function generateQRCode($orderDetails) {
    include('phpqrcode/qrlib.php');

    // Set temporary directory for saving QR codes
    $tempDir = "qrcodes/";

    // Generate QR code filename
    $fileName = 'order_' . uniqid() . '.png'; // Generate a unique filename

    // Absolute and relative file paths
    $pngAbsoluteFilePath = $tempDir . $fileName;
    $urlRelativeFilePath = $tempDir . $fileName;

    // Format order details with line breaks
    $orderDetailsFormatted = wordwrap($orderDetails, 100, "\n", true); // Adjust the line length as needed

    // Generate QR code
    QRcode::png($orderDetails, $pngAbsoluteFilePath);

    echo '<img src="' . $pngAbsoluteFilePath . '">';

    // Return relative URL of the QR code image
    return $urlRelativeFilePath;
}

// Function to clear the cart after checkout
function clearCart() {
    $_SESSION['cart'] = array();
}

// Process checkout
// Process checkout
function processCheckout() {
    // Validate cart data
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Error: Cart is empty.";
        return;
    }

    // Get order details (for example, item names and quantities)
    $orderDetails = '';
    foreach ($_SESSION['cart'] as $item) {
        $orderDetails .= $item['item_name'] . ": " . $item['quantity'] . "\n"; // Concatenate item name and quantity
    }

    // Generate QR code
    $qrCodeURL = generateQRCode($orderDetails);

    // Update quantities in the database
    updateQuantitiesInDatabase();

    // Clear the cart
    clearCart();

    $message = "Thank you for your order!";
}


// Update quantity in the database
function updateQuantity($conn, $item_name, $quantity) {
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE menu_items SET quantity = quantity - ? WHERE item_name = ?");
    $stmt->bind_param("is", $quantity, $item_name);
    $stmt->execute();

    if ($stmt->error) {
        echo "Error updating quantity: " . $stmt->error;
    }
}

// Update quantities in the database
function updateQuantitiesInDatabase() {
    $conn = getDBConnection(); // Reuse database connection

    foreach ($_SESSION['cart'] as $item_name => $item) {
        $quantity = $item['quantity']; // Get the purchased quantity
        updateQuantity($conn, $item_name, $quantity); // Update the quantity in the database
    }
}

// Check if form is submitted for checkout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    processCheckout();
}

// HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for the header */
        body {
            background-image: url("11.jpg");
            background-size: 10000;
            background-position: center;
            background-attachment: fixed;
        }  
        .navbar {
            transition: top 0.3s; /* Add transition effect to the top property */
            position: sticky;
            top: 0; /* Stick the header to the top of the viewport */
            z-index: 1000; /* Ensure the header is above other content */
        }

        .navbar-brand img {
            width: 60px; /* Adjust the width of the logo as needed */
            height: auto; /* Maintain aspect ratio */
        }

        .container {
            position: relative; /* Set container to relative position */
            max-width: 600px; /* Limit width for better readability */
            margin: 0 auto; /* Center the container horizontally */
            padding: 20px; /* Add some padding for spacing */
            border: 1px solid #ccc; /* Add a border for visual separation */
            border-radius: 5px; /* Add border radius for rounded corners */
            background-color: #f9f9f9; /* Set background color */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box shadow for depth */
        }

        h2 {
            margin-top: 0; /* Remove default margin for h2 */
        }

        p {
            margin-bottom: 20px; /* Add margin below paragraphs for spacing */
        }

        label {
            display: block; /* Display labels as blocks for better spacing */
            font-weight: bold; /* Make labels bold for emphasis */
            margin-bottom: 5px; /* Add margin below labels for spacing */
        }

        select {
            width: 100%; /* Make select element full width */
            padding: 10px; /* Add padding for better appearance */
            margin-bottom: 20px; /* Add margin below select element for spacing */
            border: 1px solid #ccc; /* Add border for visual clarity */
            border-radius: 5px; /* Add border radius for rounded corners */
            background-color: #fff; /* Set background color */
            font-size: 16px; /* Adjust font size for readability */
        }

        button {
            display: block; /* Display buttons as blocks for better spacing */
            width: 100%; /* Make button full width */
            padding: 10px; /* Add padding for better appearance */
            background-color: #007bff; /* Set background color */
            color: #fff; /* Set text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Add border radius for rounded corners */
            cursor: pointer; /* Add cursor pointer for interaction */
            font-size: 16px; /* Adjust font size for readability */
            transition: background-color 0.3s; /* Add transition effect for hover */
        }

        button:hover {
            background-color: #4CAF50; /* Darken background color on hover */
        }

        .qr-code-container {
            text-align: center; /* Center align QR code */
        }

        .qr-code-container img {
            max-width: 100%; /* Make QR code responsive */
            height: auto; /* Maintain aspect ratio */
            margin-top: 20px; /* Add margin on top for spacing */
        }
    </style>
</head>
<body>
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="Bakery Logo">
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="bakery_cart.php">View Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bakery_menu.php">Go to Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bakery_ordering_system.php">Go to Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <h2>Checkout</h2>
    <?php if (isset($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label for="payment_method">Select Payment Method:</label>
            <select name="payment_method" id="payment_method">
                <option value="cash_on_delivery">Cash on Delivery</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>
        <button type="submit" name="checkout">Place Order</button>
    </form>
    <?php if (isset($qrCodeURL)) { ?>
        <div class="qr-code-container">
            <p>Scan the QR code below to view your order:</p>
            <img src="qrcodes/<?php echo basename($qrCodeURL); ?>" alt="QR Code">
        </div>
    <?php } ?>
</div>

</body>
</html>
