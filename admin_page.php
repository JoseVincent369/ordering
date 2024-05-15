<?php
session_start();

// Check if admin is logged in, if not, redirect to login page
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bakery"; // Update to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variable to track if any items were updated
$items_updated = false;

// Handle form submission to update quantities for items
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantities'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantity_') === 0) {
            $item_id = substr($key, strlen('quantity_'));
            $quantity = $_POST['quantity_' . $item_id];
            $current_quantity = $_POST['current_quantity_' . $item_id];

            // Only update quantity if it has been modified in the form
            if ($quantity != $current_quantity) {
                // Update quantity in the database using prepared statement
                $stmt = $conn->prepare("UPDATE menu_items SET quantity = quantity + ? WHERE id = ?");
                $stmt->bind_param("ii", $quantity, $item_id);
                if ($stmt->execute()) {
                    // Set flag to true if any item was updated
                    $items_updated = true;
                } else {
                    echo "Error updating quantity: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Handle form submission to add quantity for a specific item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_quantity'])) {
    $item_name = $_POST['item_name'];
    $quantity_to_add = $_POST['quantity'];
    $price = $_POST['price'];
    $image_source = $_POST['image_source'];

    // Insert new item into the database
    $sql = "INSERT INTO menu_items (item_name, price, image_source, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $item_name, $price, $image_source, $quantity_to_add);
    
    // Set parameters and execute the statement
    if ($stmt->execute()) {
        echo "Item added successfully.";
    } else {
        echo "Error adding item: " . $stmt->error;
    }
    $stmt->close();
}

// Handle form submission to remove an item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    // Check if the user confirmed the removal
    if (isset($_POST['confirm_removal']) && $_POST['confirm_removal'] === 'yes') {
        $item_id = $_POST['item_id'];

        // Delete item from the database
        $sql = "DELETE FROM menu_items WHERE id = $item_id";
        if ($conn->query($sql) === TRUE) {
            echo "Item removed successfully.";
            // Optionally, you can redirect or refresh the page after item removal
            // header("Location: admin_page.php");
            // exit();
        } else {
            echo "Error removing item: " . $conn->error;
        }
    } else {
        echo "Item removal canceled.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <script>
        function confirmRemoval() {
            return confirm("Are you sure you want to remove it? By removing it, it will be deleted permanently.");
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            width: 150px;
        }
        .logout {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #4CAF50;
        }
        .logout:hover {
            background-color: #45a049;
        }
        .search-container {
            margin-top: 20px;
        }
        input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        h2 {
            color: #333;
            text-align: center; /* Center align the text */
        }
        h3 {
            color: #666;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-container {
            max-width: 400px;
            margin: 20px auto; /* Center the container */
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <div>
            <a href="bakery_ordering_system.php" class="logout">Logout</a>
        </div>
    </header>
    <h2>Welcome to the Admin Page</h2>
    <!-- Your admin page content goes here -->
    <div class="search-container">
        <form action="#" method="post">
            <input type="text" placeholder="Search..." name="search">
            <input type="submit" value="Search">
        </form>
    </div>
    <h3>Update Item Quantities</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Display menu items and quantity input fields -->

        <table>
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Current Quantity</th>
                <th>Quantity to Add</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT * FROM menu_items";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['item_name'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td><input type='number' name='quantity_" . $row['id'] . "'></td>";
                    echo "<td><input type='hidden' name='current_quantity_" . $row['id'] . "' value='" . $row['quantity'] . "'></td>";
                    echo "<td><button type='submit' name='update_quantities'>Update Quantity</button> ";
                    echo "<form style='display:inline;' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' onsubmit='return confirmRemoval()'>";
                    echo "<input type='hidden' name='item_id' value='" . $row['id'] . "'>";
                    echo "<input type='hidden' name='confirm_removal' value='yes'>";
                    echo "<button type='submit' name='remove_item'>Remove Item</button></form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No items found</td></tr>";
            }
            ?>
        </table>
    </form>
    <h3>Add Item</h3>
    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" required><br>
            <label for="quantity">Quantity to Add:</label>
            <input type="number" id="quantity" name="quantity" required><br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required><br>
            <label for="image_source">Image Source:</label>
            <input type="text" id="image_source" name="image_source" required><br>
            <button type="submit" name="add_quantity">Add Quantity</button>
        </form>
    </div>

</body>
</html>
