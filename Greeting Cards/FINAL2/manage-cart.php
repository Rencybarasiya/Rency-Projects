
<?php include 'header.php'; ?>

<?php

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'db1.php';

$q = "SELECT id, user_id, username, card_id, name, price, image, quantity, created_at FROM cart ORDER BY created_at DESC";
$res = $conn->query($q);
?>
<style>
    .manage-cart h2 { margin-top: 10px;color:#cc3366; text-align:center; }
    .manage-cart table { width:100%; border-collapse: collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
    .manage-cart th, .manage-cart td { padding:12px; border-bottom:1px solid #eee; text-align:center; }
    .manage-cart th { background:#ffe4ec; }
    .manage-cart img { width:70px; border-radius:6px; }
    .manage-cart .muted { color:#777; font-size:12px; }
    .btn-del { background:#e74c3c; color:#fff; padding:6px 10px; border-radius:6px; text-decoration:none; }
    .btn-del:hover { background:#c0392b; }
</style>

<div class="manage-cart" id="manageCartRoot">
    <h2>All Cart Items</h2>

    <?php if (isset($_GET['deleted'])): ?>
        <p style="text-align:center;color:green;font-weight:bold;">Cart item deleted.</p>
    <?php endif; ?>

  
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Card ID</th>
                <th>Name</th>
                <th>Price (₹)</th>
                <th>Image</th>
                <th>Qty</th>
                <th>Added At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($res && $res->num_rows > 0): ?>
                <?php while ($r = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?= intval($r['id']) ?></td>
                        <td>
                            <?= htmlspecialchars($r['username'] ?: (string)($r['user_id'] ?? 'Guest')) ?>
                            <div class="muted">UID: <?= htmlspecialchars((string)($r['user_id'] ?? '-')) ?></div>
                        </td>
                        <td><?= intval($r['card_id']) ?></td>
                        <td><?= htmlspecialchars($r['name']) ?></td>
                        <td><?= number_format((float)$r['price'], 2) ?></td>
                        <td><img src="image/<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['name']) ?>"></td>
                        <td><?= intval($r['quantity']) ?></td>
                        <td><?= htmlspecialchars($r['created_at']) ?></td>
                        <td>
                            <a class="btn-del" href="manage-cart-delete.php?id=<?= intval($r['id']) ?>" onclick="return confirm('Delete this cart row?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9">No cart items found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
  function refreshManageCart() {
    fetch('manage-cart.php')
      .then(r => r.text())
      .then(html => {
        const temp = document.createElement('div');
        temp.innerHTML = html;
        const incoming = temp.querySelector('#manageCartRoot');
        const current = document.querySelector('#manageCartRoot');
        if (incoming && current) {
          current.outerHTML = incoming.outerHTML;
        }
      })
      .catch(console.error);
  }
  // Auto-refresh every 10 seconds
  setInterval(refreshManageCart, 10000);
</script>


<?php include 'footer1.php'; ?>