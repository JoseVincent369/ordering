<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Ordering System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            animation: fadeIn 2s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #f9f9f9;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            animation: slideIn 1s ease-in-out;
        }

        @keyframes slideIn {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(0); }
        }

        .logo {
            width: 50px;
            height: 50px;
            background-image: url('logo.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .header a {
            text-decoration: none;
            color: #0066cc;
            margin-right: 10px;
            transition: color 0.3s ease-in-out;
        }

        .header a:hover {
            color: #004080;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo"></div>
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="bakery_menu.php">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="bakery_cart.php">Cart</a>
                </li>
            </ul>
        </nav>
        <form class="form-inline" action="bakery_menu.php" method="get">
            <input type="text" name="query" placeholder="Search..." value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
        </form>
    </header>
</body>
</html>
