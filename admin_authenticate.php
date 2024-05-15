<?php

require_once 'connection.php';

// Database connection details
$servername = "localhost";
$username_db = "root"; // Update with your MySQL username
$password_db = ""; // Update with your MySQL password
$dbname = "bakery"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message variable
$error_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to retrieve user information
    $sql = "SELECT * FROM users WHERE username = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        // Fetch the row
        $row = $result->fetch_assoc();

        // Verify password (assuming password is stored as plaintext)
        if ($password === $row['password']) {
            // Authentication successful, set session variable
            session_start();
            $_SESSION["admin_logged_in"] = true;
            $_SESSION["username"] = $username; // Store username in session if needed
            header("Location: admin_page.php"); // Redirect to admin dashboard
            exit();
        } else {
            // Authentication failed, display error message
            $error_message = "Incorrect password. Please try again.";
        }
    } else {
        // Authentication failed, display error message
        $error_message = "User not found. Please try again.";
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="stylesadmin.css">
</head>
<body style="background-image: url('11.jpg');">
    <h2>Admin Login</h2>
    <div class="container">
        <?php if (!empty($error_message)) { ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="admin_authenticate.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
