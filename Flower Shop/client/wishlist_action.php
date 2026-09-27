<?php
session_start();
$con = mysqli_connect("localhost","root","","flowercrafts");
if (!$con) { die("DB Connection Failed: " . mysqli_connect_error()); }

// ✅ Check login
if (!isset($_SESSION['user_id'])) {
    echo "NOT_LOGGED_IN";
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$price = $_POST['price'];

// ✅ Fetch product name from products table
$product_query = mysqli_query($con, "SELECT name FROM products WHERE id='$product_id'");
if ($product_query && mysqli_num_rows($product_query) > 0) {
    $product_row = mysqli_fetch_assoc($product_query);
    $product_name = $product_row['name'];
} else {
    echo "PRODUCT_NOT_FOUND";
    exit;
}

// ✅ Check if product already exists in wishlist
$check = mysqli_query($con, "SELECT * FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'");
if (mysqli_num_rows($check) > 0) {
    // Remove from wishlist
    mysqli_query($con, "DELETE FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'");
    echo "REMOVED";
} else {
    // Add to wishlist (now including product_name)
    mysqli_query($con, "INSERT INTO wishlist (user_id, product_id, product_name, price) 
                        VALUES ('$user_id', '$product_id', '$product_name', '$price')");
    echo "ADDED";
}
?>
