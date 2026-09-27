<?php
session_start();
include("db1.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <style>
        body { margin-top: 10px;
            font-family: 'Poppins', sans-serif;
            padding: 10px;
            background: #f8f8f8;
        }

        h1 {
            margin-top: -10px;
            text-align: center;
            color: #e91e63;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background-color: #ffe4ec;
            color: #333;
        }

        img {
            width: 100px;
            border-radius: 8px;
        }

        .total {
            font-weight: bold;
            font-size: 18px;
            background: #fff5f8;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        .empty-cart {
            text-align: center;
            font-size: 20px;
            color: #777;
            margin-top: 50px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            background: #e91e63;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-link:hover {
            background: #d81b60;
        }
    </style>
</head>
<body>

<h1>🛒 Your Shopping Cart</h1>

<?php if (isset($_GET['added'])): ?>
    <p style="text-align:center;color:green;font-weight:bold;">Card added successfully!</p>
<?php elseif (isset($_GET['error'])): ?>
    <p style="text-align:center;color:#e74c3c;font-weight:bold;">Something went wrong (<?= htmlspecialchars($_GET['error']) ?>)</p>
<?php endif; ?>

<?php if (!empty($_SESSION['cart'])): ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price (₹)</th>
                <th>Quantity</th>
                <th>Subtotal (₹)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><img src="image/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td>₹<?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>₹<?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" 
                       class="btn-delete" 
                       onclick="return confirmDelete('<?php echo addslashes($item['name']); ?>');">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="4">Total</td>
                <td colspan="2">₹<?php echo number_format($total, 2); ?></td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center; display:flex; gap:12px; justify-content:center; align-items:center; margin-top:16px;">
        <a href="user-dashboard.php" class="back-link">← Continue Shopping</a>
        <a href="checkout.php" class="back-link" style="background:#4caf50;">Place Order →</a>
    </div>

<?php else: ?>
    <p class="empty-cart">Your cart is empty 😕</p>
    <div style="text-align: center;">
        <a href="user-dashboard.php" class="back-link">← Go Back to Shop</a>
    </div>
<?php endif; ?>

<script>
function confirmDelete(productName) {
    return confirm("Are you sure you want to remove '" + productName + "' from your cart?");
}
</script>

</body>
</html>
