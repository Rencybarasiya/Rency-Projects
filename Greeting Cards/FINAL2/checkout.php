<?php
session_start();
include 'db1.php';

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

$username = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$user = null;
if ($username) {
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email FROM user WHERE username = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($uid, $fn, $ln, $em);
        if ($stmt->fetch()) {
            $user = [ 'id' => $uid, 'first' => $fn, 'last' => $ln, 'email' => $em ];
        }
        $stmt->close();
    }
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += ($item['price'] * $item['quantity']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <style>
    body { font-family: 'Poppins', sans-serif; background:#f8f8f8; padding:30px; }
    h1 { text-align:center; color:#e91e63; }
    .container { max-width: 900px; margin: 24px auto; display:grid; grid-template-columns: 1fr 1fr; gap:20px; }
    .card { background:#fff; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05); padding:20px; }
    label { display:block; margin:10px 0 6px; }
    input, select, textarea { width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; }
    .inline { display:flex; gap:12px; }
    .half { flex: 1 1 50%; }
    .summary table { width:100%; border-collapse:collapse; }
    .summary th, .summary td { border-bottom:1px solid #eee; padding:8px; text-align:center; }
    .actions { text-align:center; margin-top:16px; }
    .btn { display:inline-block; padding:10px 16px; border-radius:6px; text-decoration:none; color:#fff; }
    .btn-back { background:#e91e63; }
    .btn-place { background:#4caf50; }
  </style>
  </head>
<body>

<h1>Checkout</h1>

<div class="container">
  <div class="card">
    <h3>Shipping & Contact</h3>
    <form method="POST" action="place_order.php" id="checkoutForm">
      <input type="hidden" name="total" value="<?= number_format($total,2,'.','') ?>">
      <label>First Name</label>
      <input type="text" name="first_name" value="<?= htmlspecialchars($user['first'] ?? '') ?>" required>
      <label>Last Name</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($user['last'] ?? '') ?>" required>
      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
      <label>Phone</label>
      <input type="text" name="phone" required>
      <label>Address</label>
      <textarea name="address" rows="3" required></textarea>
      <label>Payment Method</label>
      <select name="payment_method" id="paymentMethod" required>
        <option value="UPI">UPI</option>
        <option value="Card">Card</option>
        <option value="COD">Cash on Delivery</option>
      </select>

      <div id="paymentDetails" style="margin-top:10px;">
        <div id="upiField" class="half" style="display:none;">
          <label>UPI ID / Number</label>
          <input type="text" name="upi_id" placeholder="name@bank">
        </div>
        <div id="cardField" class="half" style="display:none;">
          <label>Card Number</label>
          <input type="text" name="card_number" inputmode="numeric" maxlength="19" placeholder="XXXX XXXX XXXX ">
        </div>
      </div>
      <div class="actions">
        <a href="cart.php" class="btn btn-back">← Back to Cart</a>
        <button type="submit" class="btn btn-place">Place Order →</button>
      </div>
    </form>
  </div>

  <div class="card summary">
    <h3>Order Summary</h3>
    <table>
      <thead>
        <tr><th>Item</th><th>Qty</th><th>Price (₹)</th><th>Subtotal</th></tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['cart'] as $it): $sub = $it['price'] * $it['quantity']; ?>
          <tr>
            <td><?= htmlspecialchars($it['name']) ?></td>
            <td><?= intval($it['quantity']) ?></td>
            <td><?= number_format((float)$it['price'],2) ?></td>
            <td><?= number_format((float)$sub,2) ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" style="text-align:right;font-weight:bold;">Total</td>
          <td style="font-weight:bold;">₹<?= number_format((float)$total,2) ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
  (function() {
    const method = document.getElementById('paymentMethod');
    const upi = document.getElementById('upiField');
    const card = document.getElementById('cardField');
    const form = document.getElementById('checkoutForm');
    function sync() {
      const v = method.value;
      upi.style.display = v === 'UPI' ? 'block' : 'none';
      card.style.display = v === 'Card' ? 'block' : 'none';
    }
    method.addEventListener('change', sync);
    sync();

    form.addEventListener('submit', function(e) {
      const v = method.value;
      if (v === 'UPI') {
        const upiInput = form.querySelector('[name="upi_id"]');
        if (!upiInput.value.trim()) {
          e.preventDefault(); alert('Please enter your UPI ID.'); upiInput.focus();
        }
      } else if (v === 'Card') {
        const cardInput = form.querySelector('[name="card_number"]');
        const digits = cardInput.value.replace(/\D/g, '');
        if (digits.length < 12) {
          e.preventDefault(); alert('Please enter a valid card number.'); cardInput.focus();
        }
      }
    });
  })();
</script>

</body>
</html>


