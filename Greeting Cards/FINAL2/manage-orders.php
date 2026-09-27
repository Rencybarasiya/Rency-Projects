<?php
include 'db1.php';
include 'header.php'; // session already started here

// Login check
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Now $conn is defined and you can safely query the database
$conn->query("CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    username VARCHAR(255) NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'PLACED',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    card_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Fetch all orders
$query = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<style>
  .orders h2 { color:#cc3366; text-align:center; }
  .orders table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
  .orders th, .orders td { padding:12px; border-bottom:1px solid #eee; text-align:center; }
  .orders th { background:#ffe4ec; }
  .btn { padding:6px 10px; border-radius:6px; text-decoration:none; color:#fff; }
  .btn-view { background:#2196f3; }
  .btn-del { background:#e74c3c; }
</style>

<div class="orders">
  <h2>Manage Orders</h2>

  <?php if (isset($_GET['deleted'])): ?>
    <p style="text-align:center;color:green;font-weight:bold;">Order deleted successfully.</p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Total (₹)</th>
        <th>Status</th>
        <th>Placed At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($o = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= intval($o['id']) ?></td>
            <td><?= htmlspecialchars($o['first_name'] . ' ' . $o['last_name']) ?></td>
            <td><?= htmlspecialchars($o['email']) ?></td>
            <td><?= htmlspecialchars($o['phone']) ?></td>
            <td><?= number_format((float)$o['total'], 2) ?></td>
            <td><?= htmlspecialchars($o['status']) ?></td>
            <td><?= htmlspecialchars($o['created_at']) ?></td>
            <td>
              
              <a class="btn btn-del" href="order-delete.php?id=<?= intval($o['id']) ?>" onclick="return confirm('Delete this order?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="8" class="no-data">No orders found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include 'footer1.php'; ?>
