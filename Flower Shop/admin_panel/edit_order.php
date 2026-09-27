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

// Get order ID from URL
if (!isset($_GET['id'])) {
    header("Location: order_admin.php");
    exit();
}

$order_id = intval($_GET['id']);

// Fetch order details
$q = "SELECT * FROM orders WHERE id='$order_id'";
$result = mysqli_query($con, $q);

if (mysqli_num_rows($result) == 0) {
    echo "Order not found.";
    exit();
}

$order = mysqli_fetch_assoc($result);

// Update order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $payment_status = mysqli_real_escape_string($con, $_POST['payment_status']);

    $update = "UPDATE orders SET status='$status', payment_status='$payment_status' WHERE id='$order_id'";
    if (mysqli_query($con, $update)) {
        header("Location: order_admin.php?updated=1");
        exit();
    } else {
        $error = "Error updating order: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order - Floreva</title>
    <style>
        body { font-family:'Segoe UI',sans-serif; background:#fff0f5; margin:0; padding:40px; }
        h2 { text-align:center; color:#d63384; margin-bottom:20px; }
        form { background:#fff; padding:20px; border-radius:8px; max-width:500px; margin:auto; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
        label { display:block; margin-top:10px; font-weight:bold; color:#5a0d2d; }
        select { width:100%; padding:8px; margin-top:5px; border-radius:6px; border:1px solid #ccc; }
        button { margin-top:20px; padding:10px 15px; background:#f25a9b; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:16px; }
        button:hover { background:#d94a88; }
        .error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; padding:10px; margin-bottom:15px; border-radius:6px; text-align:center; }
    </style>
</head>
<body>

<h2>Edit Order #<?php echo $order['id']; ?></h2>

<?php if(isset($error)) { echo "<div class='error'>{$error}</div>"; } ?>

<form method="POST">
    <label for="status">Order Status</label>
    <select name="status" id="status" required>
        <option value="Pending" <?php if($order['status']=='Pending') echo 'selected'; ?>>Pending</option>
        <option value="Processing" <?php if($order['status']=='Processing') echo 'selected'; ?>>Processing</option>
        <option value="Completed" <?php if($order['status']=='Completed') echo 'selected'; ?>>Completed</option>
        <option value="Cancelled" <?php if($order['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
    </select>

    <label for="payment_status">Payment Status</label>
    <select name="payment_status" id="payment_status" required>
        <option value="Pending" <?php if($order['payment_status']=='Pending') echo 'selected'; ?>>Pending</option>
        <option value="Success" <?php if($order['payment_status']=='Success') echo 'selected'; ?>>Success</option>
        <option value="Failed" <?php if($order['payment_status']=='Failed') echo 'selected'; ?>>Failed</option>
    </select>

    <button type="submit">Update Order</button>
</form>

</body>
</html>
