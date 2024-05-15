<?php

require 'connection.php';

session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bakery"; // Update to the correct database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch menu items from the database
if(isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT * FROM menu_items WHERE item_name LIKE '%$query%'";
} else {
    $sql = "SELECT * FROM menu_items";
}

$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error); // Output any database error
}

$menu_items = array(); // Initialize an array to store menu items

if ($result->num_rows > 0) {
    // Store each menu item in the array
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
} else {
    echo "No menu items found."; // Output if no menu items are retrieved
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Menu</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("14.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .navbar {
            transition: top 0.3s;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand img {
            width: 60px;
            height: auto;
        }

        .search-bar {
            margin-left: auto;
        }

        .header-hidden {
            top: -100px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu-items-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-gap: 20px;
            width: 100%;
            margin-top: 20px;
        }

        .menu-item {
            display: flex;
            flex-direction: column;
        }

        .menu-item img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .menu-item-details {
            text-align: center;
        }

        button:hover {
            background-color: #0056b3;
        }
        .image-container a {
            display: block;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
            width: 100%; /* Ensure each image container takes up the full width */
        }

        .image-container img {
            width: 100%; /* Make images fill their parent container */
            height: 100%; /* Make images fill their parent container */
            object-fit: cover; /* Maintain aspect ratio while covering the container */
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
            <form class="d-flex search-bar" action="bakery_menu.php" method="get">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="bakery_cart.php">View Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bakery_ordering_system.php">Go to home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <h2 style="text-align: center;">Place Your Order</h2>
    <div class="menu-items-container">
        <?php foreach ($menu_items as $index => $item) { ?>
            <div class="menu-item">
                <form action="bakery_cart.php" method="post">
                    <input type="hidden" name="item_name" value="<?php echo $item['item_name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
                    <input type="hidden" name="quantity_available" value="<?php echo $item['quantity']; ?>">
                    <img src="<?php echo $item['image_source']; ?>" alt="<?php echo $item['item_name']; ?>">
                    <div class="menu-item-details">
                        <h3><?php echo $item['item_name']; ?></h3>
                        <p>₱<?php echo number_format($item['price'], 2); ?> each</p>
                        <p>Available: <?php echo $item['quantity']; ?></p>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $item['quantity']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    let prevScrollpos = window.pageYOffset;
    window.onscroll = function() {
        let currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            document.querySelector(".header").style.top = "0";
        } else {
            document.querySelector(".header").style.top = "-100px";
        }
        prevScrollpos = currentScrollPos;
    }
</script>

</body>
</html>

