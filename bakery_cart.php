<?php
session_start();

require_once 'phpqrcode/qrlib.php';

// Function to add an item to the cart
function addToCart($item_name, $price, $quantity) {
    $_SESSION['cart'][] = array(
        'item_name' => $item_name,
        'price' => $price,
        'quantity' => $quantity
    );
}

// Function to remove an item from the cart
function removeFromCart($index) {
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Reset array keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Calculate total of items in the cart
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        }
    }
    return $total;
}

// Function to generate QR code for an order
function generateQRCode($orderDetails) {
    include('phpqrcode/qrlib.php');

    // Set temporary directory for saving QR codes
    $tempDir = "qrcodes/";

    // Generate QR code filename
    $fileName = 'order_' . uniqid() . '.png'; // Generate a unique filename

    // Absolute and relative file paths
    $pngAbsoluteFilePath = $tempDir . $fileName;
    $urlRelativeFilePath = $tempDir . $fileName;

    // Generate QR code
    QRcode::png($orderDetails, $pngAbsoluteFilePath);

    // Return relative URL of the QR code image
    return $urlRelativeFilePath;

    
}

// Update quantities in the database
function updateQuantitiesInDatabase() {
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

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $item_name = $item['item_name'];
            $quantity = $item['quantity'];
            updateQuantity($conn, $item_name, $quantity);
        }
    }

    // Close connection
    $conn->close();
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

// Function to clear the cart after checkout
function clearCart() {
    $_SESSION['cart'] = array();
}

// Check if form is submitted to add item to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $item_name = $_POST['item_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        addToCart($item_name, $price, $quantity);
    }

    if (isset($_POST['remove_item'])) {
        $index = $_POST['item_index'];
        removeFromCart($index);
    }

    if (isset($_POST['checkout'])) {
        updateQuantitiesInDatabase(); // Update quantities in the database
        header("Location: bakery_checkout.php"); // Redirect to checkout page
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Order</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("12.jpg");
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
        button:hover {
            background-color: #0056b3; /* Darken background color on hover */
        }

        .search-bar {
            display: none; /* Hide the search bar */
        }

        /* Hide the header when scrolled down */
        .header-hidden {
            top: -100px; /* Move the header out of the viewport */
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
    <h2>Cart</h2>
    <ul>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                <li>
                    <?php echo $item['item_name']; ?> - ₱<?php echo $item['price']; ?> each (Quantity: <?php echo $item['quantity']; ?>)
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                        <button type="submit" name="remove_item">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
            <li><strong>Total: ₱<?php echo number_format(calculateTotal(), 2); ?></strong></li>
            <li>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <button type="submit" name="checkout">Checkout</button>
                </form>
            </li>
        <?php else: ?>
            <li>Your cart is empty.</li>
        <?php endif; ?>
    </ul>
</div>

<script>
    // JavaScript code to handle header visibility on scroll
    let prevScrollpos = window.pageYOffset;
    window.onscroll = function() {
        let currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            document.querySelector(".header").style.top = "0"; // Show the header when scrolling up
        } else {
            document.querySelector(".header").style.top = "-100px"; // Hide the header when scrolling down
        }
        prevScrollpos = currentScrollPos;
    }
</script>

</body>
</html>