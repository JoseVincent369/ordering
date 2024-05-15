<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Ordering System</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            background-image: url('1.jpg');
            background-size: cover;
            animation: fadeIn 2s ease-in-out;
            margin: 0;
            padding: 0;
        }
        button:hover {
            background-color: #0056b3; /* Darken background color on hover */
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            1% { opacity: 1; }
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            border-bottom: 1px solid #ccc;
            padding: 10px;
            transition: top 0.3s;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            height: 45px;
            width: auto;
        }

        .header-links {
            margin-right: 20px;
        }

        .container {
            text-align: center;
            margin-top: 80px; /* Adjusted margin to accommodate the fixed header */
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .image-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* 5 columns */
            grid-template-rows: repeat(2, auto); /* Auto height for rows */
            gap: 20px; /* Gap between images */
            justify-content: center;
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

        .image-container a:hover img {
            transform: scale(1.1);
        }

        /* Hide header when scrolling down */
        .hidden {
            top: -80px; /* Height of the header */
        }
    </style>
</head>
<body>
    <div class="header" id="header">
        <a href="index.php"><img src="logo.png" alt="Bakery Logo"></a>
        <div class="header-links">
            <a href="bakery_cart.php">View Cart</a>
            <a href="bakery_menu.php">Go to Menu</a>
        </div>
    </div>
    <div class="container">
        <h1>Welcome to Our Bakery!</h1>
        <div class="image-container">
            <a href="bakery_menu.php">
                <img src="1.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="2.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="3.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="4.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="5.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="6.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="7.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="8.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="9.jpg">
            </a>
            <a href="bakery_menu.php">
                <img src="10.jpg">
            </a>
        </div>
    </div>
    <script>
        // Hide or show header on scroll
        let lastScrollTop = 0;
        const header = document.getElementById("header");

        window.addEventListener("scroll", function () {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > lastScrollTop) {
                // Scroll down
                header.classList.add("hidden");
            } else {
                // Scroll up
                header.classList.remove("hidden");
            }

            lastScrollTop = currentScroll;
        });
    </script>
</body>
</html>
