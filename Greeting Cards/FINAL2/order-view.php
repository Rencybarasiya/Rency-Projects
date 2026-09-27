<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
include 'db1.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { header('Location: manage-orders.php'); exit(); }

$o = null;
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows === 1) { $o = $res->fetch_assoc(); }
$stmt->close();
if (!$o) { header('Location: manage-orders.php'); exit(); }

$items = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$items->bind_param("i", $id);
$items->execute();
$itRes = $items->get_result();
?>
<style>
  .order-view { max-width:900px; margin:20px auto; font-family:Arial, sans-serif; }
  .card { background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05); margin-bottom:16px; }
  h2 { color:#cc3366; }
  table { width:100%; border-collapse:collapse; }
  th, td { padding:10px; border-bottom:1px solid #eee; text-align:center; }
  th { background:#ffe4ec; }
  .btn-del { background:#e74c3c; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; }
</style>

<div class="order-view">
  <div class="card">
    <h2>Order #<?= intval($o['id']) ?></h2>
    <p><strong>Customer:</strong> <?= htmlspecialchars($o['first_name'] . ' ' . $o['last_name']) ?> | <strong>Email:</strong> <?= htmlspecialchars($o['email']) ?> | <strong>Phone:</strong> <?= htmlspecialchars($o['phone']) ?></p>
    <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($o['address'])) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($o['status']) ?> | <strong>Total:</strong> ₹<?= number_format((float)$o['total'],2) ?></p>
  </div>

  <div class="card">
    <h3>Items</h3>
    <table>
      <thead>
        <tr><th>Card ID</th><th>Name</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
      </thead>
      <tbody>
        <?php $sum = 0; while ($row = $itRes->fetch_assoc()): $sub = $row['price'] * $row['quantity']; $sum += $sub; ?>
          <tr>
            <td><?= intval($row['card_id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= number_format((float)$row['price'],2) ?></td>
            <td><?= intval($row['quantity']) ?></td>
            <td><?= number_format((float)$sub,2) ?></td>
          </tr>
        <?php endwhile; ?>
        <tr><td colspan="4" style="text-align:right;font-weight:bold;">Total</td><td style="font-weight:bold;">₹<?= number_format((float)$sum,2) ?></td></tr>
      </tbody>
    </table>
  </div>

  <a class="btn-del" href="order-delete.php?id=<?= intval($o['id']) ?>" onclick="return confirm('Delete this order?');">Delete Order</a>
</div>


