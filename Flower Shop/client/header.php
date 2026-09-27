<?php
$con = mysqli_connect("localhost", "root", "", "flowercrafts");
if (mysqli_connect_errno()) {
    echo "Failed to connect..." . mysqli_error($con);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Floreva</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden; /* ✅ Prevent left-right scroll */
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url("flbg.jpg") no-repeat center center/cover;
            color: #4a2c35;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        img { max-width: 100%; display: block; } /* ✅ Prevents image overflow */

        /* HEADER */
        header {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(6px);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 5vw;
        }
        nav img { height: 55px; }
        nav ul {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 25px;
            /* ❌ Removed flex-wrap so dropdown stays in one line */
        }
        nav ul li {
            position: relative;
            font-weight: 600;
            color: #5a1a35;
            cursor: pointer;
            transition: color 0.3s;
        }
        nav ul li a { text-decoration: none; color: inherit; }
        nav ul li:hover { color: #d63384; }

        /* Dropdown Menu (Unchanged from your original working one) */
        nav ul li ul {
            position: absolute;
            top: 100%;
            left: 0;
            background: #ffe6f0;
            border-radius: 14px;
            padding: 10px 0;
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            min-width: 250px;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.12);
            z-index: 999;
        }
        nav ul li:hover > ul {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        nav ul li ul li {
            padding: 12px 18px;
            font-size: 1rem;
            font-weight: 500;
            color: #5a1a35;
            transition: all 0.3s ease;
            border-radius: 8px;
            white-space: nowrap;
        }
        nav ul li ul li:hover {
            background: #ffd6e0;
            color: #b03060;
            padding-left: 22px;
        }
        nav ul li ul li ul {
            position: absolute;
            top: 0;
            left: 100%;
            background: #ffe6f0;
            border-radius: 10px;
            padding: 10px 0;
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateX(10px);
            transition: all 0.3s ease;
            min-width: 200px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
        }
        nav ul li ul li:hover > ul {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }
        nav ul li ul li ul li {
            padding: 10px 15px;
            font-size: 0.95rem;
            color: #5a1a35;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        nav ul li ul li ul li:hover {
            background: #ffd6e0;
            color: #d63384;
            padding-left: 20px;
        }

        /* Icons (Still Wrapped to Avoid Overflow) */
        .icons {
            display: flex;
            gap: 20px;
            margin-left: 20px;
            flex-wrap: wrap;
        }
        .icons a {
            text-decoration: none;
            color: #5a1a35;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .icons a:hover {
            color: #d63384;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<header>
    <nav>
        <img src="Floreva.png" alt="Floreva Logo">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php">About Floreva</a></li>
            <li>Floral Boutique
                <ul>
                    <li><a href="product.php?category=Floral Bouquet">Floral Bouquet</a></li>
                    <li><a href="product.php?category=Floral Decoration">Floral Decoration</a></li>
                    <li><a href="product.php?category=Floral Combo">Bouquet with Gifts</a></li>
                    <li><a href="product.php?category=Floral Jewellery">Floral Jewellery</a></li>
                    <li><a href="product.php?category=Artificial Plants">Artificial Plants</a></li>
                    <li><a href="product.php?category=Saplings And Nursery Products">Saplings And Nursery Products</a></li>
                </ul>
            </li>
            <li><a href="gallery_client.php">Floreva Gallery</a></li>
            <li><a href="feedback_client.php">Feedback</a></li>
            <div class="icons">
                <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                <a href="wishlist_client.php"><i class="fas fa-heart"></i> Wishlist</a>
                <li>
                    <a href="#"><i class="fas fa-user"></i> My Account</a>
                    <ul>
                        <li><a href="myprofile.php">My Profile</a></li>
                        <li><a href="order_client.php">Download Bill</a></li>
                        <li><a href="logout_user.php">Logout</a></li>
                    </ul>
                </li>
            </div>
        </ul>
    </nav>
</header>
