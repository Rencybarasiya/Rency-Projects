<?php
session_start();
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
  echo "Failed to connect...".mysqli_error();
  exit();
}
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$user_id = $_SESSION['user_id'];
if (isset($_POST['add_to_cart'])) 
{
    $product_id = $_POST['product_id'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];

    // Check if already in cart
    $q = "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    $res = mysqli_query($con, $q);

    if (mysqli_num_rows($res) > 0) {
        mysqli_query($con, "UPDATE cart SET qty = qty + $qty WHERE user_id='$user_id' AND product_id='$product_id'");
    } else {
        mysqli_query($con, "INSERT INTO cart (user_id, product_id, price, qty) VALUES ('$user_id', '$product_id', '$price', '$qty')");
    }
}
header("Location: cart.php");
exit();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - Floreva</title>
    <style>
    body {
        margin: 0;
        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
        background-color: #fff5f8; /* pastel pink background */
        color: #333;
    }

    h2 {
        text-align: center;
        color: #c94f7c;
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        box-shadow: 0 0 10px rgba(201, 79, 124, 0.2);
        border-radius: 12px;
        overflow: hidden;
    }

    th, td {
        padding: 14px 18px;
        text-align: center;
        border-bottom: 1px solid #f5d5dd;
    }

    th {
        background-color: #fcdde7;
        color: #c94f7c;
        font-weight: 600;
    }

    td {
        background-color: #fff5fa;
    }

    tr:last-child td {
        border-bottom: none;
    }

    img {
        height: 80px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(201, 79, 124, 0.2);
    }

    .btn {
        padding: 8px 15px;
        background-color: #c94f7c;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: background 0.3s ease;
        font-size: 14px;
        display: inline-block;
    }

    .btn:hover {
        background-color: #e46497;
    }

    strong {
        color: #c94f7c;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        th {
            text-align: left;
        }

        td {
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            font-weight: bold;
            color: #c94f7c;
        }

        tr {
            margin-bottom: 20px;
            border-bottom: 2px solid #fcdde7;
        }
    }
</style>

</head>
<body>
    <?php include 'header.php'; ?>
    <h2>Your Cart</h2>

    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php 
        $grand_total = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $total = $row['price'] * $row['qty'];
            $grand_total += $total;
            echo "<tr>
                <td><img src='../admin_panel/pictures/{$row['image']}'></td>
                <td>{$row['name']}</td>
                <td>₹{$row['price']}</td>
                <td>{$row['qty']}</td>
                <td>₹$total</td>
                <td><a href='remove_from_cart.php?id={$row['id']}' class='btn'>Remove</a></td>
            </tr>";
        }
        ?>
        <tr>
            <td colspan="4"><strong>Grand Total</strong></td>
            <td colspan="2"><strong>₹<?= $grand_total ?></strong></td>
        </tr>
    </table>
    <?php include 'footer.php'; ?>
</body>
</html>