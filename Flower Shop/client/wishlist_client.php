<?php
session_start();
$con = mysqli_connect("localhost","root","","flowercrafts");
if (!$con) die("DB Connection Failed: " . mysqli_connect_error());

// ✅ Only logged-in users can see their wishlist
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch wishlist items with required columns
$q = "SELECT w.id, w.user_id, w.product_id, p.name AS product_name, w.price, p.image, p.product_details
      FROM wishlist w 
      JOIN products p ON w.product_id = p.id 
      WHERE w.user_id = $user_id";
$result = mysqli_query($con, $q);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Wishlist - Floreva</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #fff0f5; margin: 0; padding: 0; }
    h2 { text-align: center; color: #d63384; margin: 20px 0; }
    .wishlist-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px; }
    .wishlist-card {
      width: 280px; background: white; border: 1px solid #ffc0cb; border-radius: 10px;
      text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 15px; position: relative;
    }
    .wishlist-card img { width: 100%; height: 200px; border-radius: 10px; object-fit: cover; }
    .wishlist-card h3 { margin: 10px 0; color: #880e4f; }
    .wishlist-card p { font-size: 14px; color: #555; }
    .wishlist-card .price { font-weight: bold; margin: 10px 0; color: #d63384; }
    input[type='number'] {
      border: 2px solid #f9c2d3; border-radius: 8px; padding: 5px; margin-right: 8px;
      text-align: center; width: 60px; font-weight: bold; color: #880e4f; background-color: #fffafc;
    }
    .wishlist-card button, .remove-btn {
      background-color: #f9c2d3; border: none; padding: 10px 18px;
      margin-top: 10px; border-radius: 25px; font-weight: bold; cursor: pointer;
      transition: 0.3s ease; color: #880e4f; box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    .wishlist-card button:hover, .remove-btn:hover {
      background-color: #d63384; color: white; transform: scale(1.05);
    }
    .remove-btn { background-color: #ffe6ec; margin-left: 10px; }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
<h2>My Wishlist 💖</h2>

<div class="wishlist-container">
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <div class='wishlist-card'>
          <img src='../admin_panel/pictures/{$row['image']}' alt='{$row['product_name']}'>
          <h3>{$row['product_name']}</h3>
          <p>{$row['product_details']}</p>
          <div class='price'>₹{$row['price']}</div>

          <!-- ✅ FORM TO ADD TO CART LIKE PRODUCT.PHP -->
          <form method='post' action='cart_user.php' style='margin-top:10px;'>
            <input type='hidden' name='product_id' value='{$row['product_id']}'>
            <input type='hidden' name='price' value='{$row['price']}'>
            <input type='number' name='qty' value='1' min='1'>
            <button type='submit' name='add_to_cart'>Add to Cart</button>
          </form>

          <!-- ✅ REMOVE BUTTON -->
          <form method='post' action='remove_wishlist_client.php' style='margin-top:5px;'>
            <input type='hidden' name='wishlist_id' value='{$row['id']}'>
            <button type='submit' class='remove-btn'>Remove</button>
          </form>
        </div>";
    }
} else {
    echo "<p style='text-align:center;'>Your wishlist is empty 💔</p>";
}
?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
