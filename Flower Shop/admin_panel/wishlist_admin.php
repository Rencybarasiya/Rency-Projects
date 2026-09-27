<?php
session_start();
$con = mysqli_connect("localhost","root","","flowercrafts");
if (!$con) die("DB Connection Failed: " . mysqli_connect_error());

// ✅ Only allow admin access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all wishlist records with required columns
$q = "SELECT w.id, w.user_id, w.product_id, p.name AS product_name, w.price, u.name AS user_name
      FROM wishlist w
      JOIN users u ON w.user_id = u.id
      JOIN products p ON w.product_id = p.id";
$result = mysqli_query($con, $q);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Wishlist</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #fff0f5; margin: 0; padding: 0; }
    h2 { text-align: center; color: #d63384; margin: 20px 0; }
    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      border: 1px solid #ffc0cb;
      padding: 10px;
      text-align: center;
      font-size: 15px;
    }
    th {
      background-color: #f9c2d3;
      color: #880e4f;
      font-weight: bold;
    }
    tr:nth-child(even) { background-color: #fffafc; }
  </style>
</head>
<body>
<?php include 'admin_header.php'; ?>
<h2>Wishlist Records (Admin View)</h2>

<table>
  <tr>
    <th>Wishlist ID</th>
    <th>User ID</th>
    <th>Product ID</th>
    <th>Product Name</th>
    <th>Price</th>
  </tr>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['user_id']} ({$row['user_name']})</td>
                <td>{$row['product_id']}</td>
                <td>{$row['product_name']}</td>
                <td>₹{$row['price']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No wishlist records found</td></tr>";
}
?>
</table>
</body>
</html>
