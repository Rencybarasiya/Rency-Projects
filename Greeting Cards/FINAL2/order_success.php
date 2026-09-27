<?php
session_start();
$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Placed</title>
  <style>
    body { font-family:'Poppins', sans-serif; background:#f8f8f8; padding:40px; text-align:center; }
    .card { max-width:600px; margin:0 auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 6px 16px rgba(0,0,0,0.08); }
    h1 { color:#4caf50; }
    a { display:inline-block; margin-top:16px; background:#e91e63; color:#fff; padding:10px 16px; border-radius:8px; text-decoration:none; }
  </style>
</head>
<body>
  <div class="card">
    <h1>🎉 Order Placed Successfully!</h1>
    <?php if ($orderId): ?>
      <p>Your order ID is <strong>#<?= $orderId ?></strong>.</p>
    <?php endif; ?>
    <p>Thank you for shopping with us.</p>
    <a href="user-dashboard.php">Continue Shopping</a>
  </div>
</body>
</html>


