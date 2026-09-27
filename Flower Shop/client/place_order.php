<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$con = mysqli_connect("localhost","root","","flowercrafts");
$user_id = $_SESSION['user_id'];

$address = $_POST['address'];
$address_type = $_POST['address_type'];
$method = $_POST['method'];

$status = "In Progress"; 
$payment_status = ($method === "Cash on Delivery") ? "Pending" : "Paid";

// Fetch cart items
$q = "SELECT c.product_id, c.qty, p.price, p.seller_id, p.name 
      FROM cart c 
      INNER JOIN products p ON c.product_id = p.id
      WHERE c.user_id = '$user_id'";
$result = mysqli_query($con, $q);
if (mysqli_num_rows($result)>0) {
   while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $qty = $row['qty'];
        $price = $row['price'];
        $seller_id = $row['seller_id'];
        $product_name = mysqli_real_escape_string($con, $row['name']);

        $insert = "INSERT INTO orders 
            (user_id, seller_id, name, number, product_id, qty, price, address, address_type, method, status, payment_status, date) 
            VALUES 
            ('$user_id', '$seller_id', '$product_name', '$number', '$product_id', '$qty', '$price', '$address', '$address_type', '$method', '$status', '$payment_status', NOW())";

        mysqli_query($con, $insert);
    }

    // Clear cart
    mysqli_query($con, "DELETE FROM cart WHERE user_id='$user_id'");

    echo "<script>
        alert('✅ Payment Successful! Your order has been placed.');
        window.location.href='order_client.php';
    </script>";
    exit; 
}
else {
    echo "<script>
        alert('❌ Your cart is empty. Please add products before placing an order.');
        window.location.href='product.php';
    </script>";
    exit;
}
?>
