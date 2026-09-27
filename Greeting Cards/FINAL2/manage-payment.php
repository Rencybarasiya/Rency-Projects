<?php include 'header.php'; ?>

<?php
// Only allow logged-in user/admin
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db1.php';

// Show all payments
$query = "SELECT * FROM payments ORDER BY payment_id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<p style='color:red;'>Error fetching payment data: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    exit();
}
?>

<h2>Manage Payments</h2>

<?php if (isset($_GET['deleted'])): ?>
    <p style="text-align:center;color:green;">Payment deleted successfully.</p>
<?php endif; ?>

<table>
  <thead>
    <tr>
      <th>Payment ID</th>
      <th>User ID</th>
      <th>Payment Method</th>
      <th>Total Payment</th>
      <th>Product Name</th>
      <th>Paid On</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['payment_id']) ?></td>
            <td><?= htmlspecialchars($row['user_id']) ?></td>
            <td><?= htmlspecialchars($row['payment_method']) ?></td>
            <td>$<?= number_format($row['total_payment'], 2) ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['paid_on']) ?></td>
            <td>
                <a href="payment-delete.php?id=<?= intval($row['payment_id']) ?>" 
                   onclick="return confirm('Are you sure you want to delete this payment?');"
                   style="color:red; text-decoration:none;">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7" class="no-data">No payments found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #fff0f6; 
        margin: 20px;
        color: #4a0028; 
    }

    h2 {
        text-align: center;
        color: #c2185b; 
        margin-bottom: 25px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 0 12px rgba(194, 24, 91, 0.25);
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    thead {
        background-color: #f48fb1; 
        color: #4a0028; 
    }

    thead th {
        padding: 12px 15px;
        text-align: left;
        font-size: 14px;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #c2185b;
    }

    tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #f8bbd0; 
        font-size: 14px;
    }

    tbody tr:nth-child(even) {
        background-color: #ffe6f0; 
    }

    tbody tr:hover {
        background-color: #f48fb1; 
        color: white;
        cursor: default;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .no-data {
        text-align: center;
        padding: 20px;
        color: #ad1457; 
        font-style: italic;
        font-size: 16px;
    }
</style>

<?php include 'footer1.php'; ?>
