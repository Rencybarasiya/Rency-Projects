<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "flowercrafts");

if (!$con) {
    die("DB Connection Failed: " . mysqli_connect_error());
}

// Only allow admin access
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}*/

// Fetch orders with user, seller, product names
$q = "SELECT o.*, 
             u.name AS user_name, 
             s.name AS seller_name, 
             p.Name AS product_name
      FROM orders o
      LEFT JOIN users u ON o.user_id = u.id
      LEFT JOIN seller s ON o.seller_id = s.id
      LEFT JOIN products p ON o.product_id = p.Id
      ORDER BY o.date DESC";

$result = mysqli_query($con, $q);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Orders - Floreva</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fff0f5; margin:0; padding:40px; }
        h2 { text-align:center; color:#d63384; margin-bottom:20px; }
        table { width:100%; border-collapse: collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        th, td { padding:12px 15px; border-bottom:1px solid #f5c6d6; text-align:left; font-size:14px; }
        th { background:#f8b6c9; color:#5a0d2d; }
        tr:hover { background:#fff6fa; }
        .action-btn { padding:5px 10px; border-radius:6px; border:none; cursor:pointer; font-size:14px; margin:2px; }
        .edit-btn { background:#f25a9b; color:#fff; }
        .edit-btn:hover { background:#d94a88; }
        .delete-btn { background:#ff4d6d; color:#fff; }
        .delete-btn:hover { background:#d43f5e; }
        .alert { text-align:center; padding:10px; margin-bottom:20px; border-radius:6px; font-weight:bold; width:80%; margin:auto; }
        .success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
        .deleted { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this order?")) {
                window.location.href = "delete_order.php?id=" + id;
            }
        }
    </script>
</head>
<body>
<?php include 'header.php'; ?>
<h2>All Orders - Admin Panel 🌸</h2>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert success">✅ Order updated successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="alert deleted">🗑️ Order deleted successfully.</div>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Seller</th>
        <th>Name & Number</th>
        <th>Address</th>
        <th>Type</th>
        <th>Method</th>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Date</th>
        <th>Status</th>
        <th>Payment</th>
        <th>Action</th>
    </tr>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['user_name']}</td>
                <td>{$row['seller_name']}</td>
                <td>{$row['name']}<br>{$row['number']}</td>
                <td>{$row['address']}</td>
                <td>{$row['address_type']}</td>
                <td>{$row['method']}</td>
                <td>{$row['product_name']}</td>
                <td>₹{$row['price']}</td>
                <td>{$row['qty']}</td>
                <td>{$row['date']}</td>
                <td style='color:".($row['status']=='Cancelled'?'red':'green')."'>".$row['status']."</td>
                <td style='color:".($row['payment_status']=='Failed'?'red':'green')."'>".$row['payment_status']."</td>
                <td>
                    <button class='action-btn edit-btn' onclick=\"window.location.href='edit_order.php?id={$row['id']}'\">Edit</button>
                    <button class='action-btn delete-btn' onclick='confirmDelete({$row['id']})'>Delete</button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='14' style='text-align:center; color:#777;'>No orders found.</td></tr>";
    }
    ?>
</table>
<?php include 'footer.php'; ?>
</body>
</html>
