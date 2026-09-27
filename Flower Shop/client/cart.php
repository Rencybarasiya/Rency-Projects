<?php
session_start();
$con = mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) {
    echo "Failed to connect..." . mysqli_error($con);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$q = "SELECT c.*, p.name, p.image 
      FROM cart c 
      JOIN products p ON c.product_id = p.id 
      WHERE c.user_id = $user_id";
$result = mysqli_query($con, $q);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - Floreva</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #ffeef4; /* pastel pink background */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #c2185b; /* rose pink */
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th {
            background: #f8bbd0; /* soft pink */
            color: #880e4f;
            padding: 12px;
            text-align: left;
            border-radius: 10px 10px 0 0;
        }

        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #f3c1d7;
        }

        table tr:hover {
            background: #fff6fa;
        }

        img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #f8bbd0;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: #ec407a;
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            background: #d81b60;
            transform: scale(1.05);
        }

        .checkout {
            text-align: center;
            margin-top: 20px;
        }

        strong {
            color: #c2185b;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>Your Cart</h2>
    <form action="placeorder_form.php" method="POST">
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
        <div class="checkout">
            <a href="placeorder_form.php" class="btn">Proceed to Checkout</a>
        </div>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>