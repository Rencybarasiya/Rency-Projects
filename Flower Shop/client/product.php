<?php
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
  echo "Failed to connect...".mysqli_error();
  exit();
}
$q = "SELECT * FROM products WHERE Status='Active'";
$result = mysqli_query($con, $q);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Floreva-Products</title>
      <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff0f5; 
      color: #4a2c35;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Product Section */
    h2 {
      text-align: center;
      color: #d63384;
      margin: 20px 0;
    }
    .product-list {
      text-align: center;
      margin-bottom: 20px;
    }
    .product-list button {
      background-color: #f9c2d3;
      border: none;
      padding: 10px 20px;
      margin: 5px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    .product-list button:hover {
      background-color: #d63384;
      color: white;
    }
    .product-details {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }
    .product-card {
      position: relative; /* ✅ Needed for heart positioning */
      width: 250px;
      border: 1px solid #ffc0cb;
      padding: 15px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.2s ease-in-out;
    }
    .product-card:hover {
      transform: translateY(-3px);
    }
    .product-card img {
      width: 100%;
      border-radius: 10px;
      height: 220px;
      object-fit: cover;
    }
    .product-card h3 {
      margin: 10px 0 5px;
      color: #880e4f;
    }
    .product-card p {
      font-size: 14px;
      color: #555;
    }
    .hidden { display: none; }

    /* Quantity + Add to Cart */
    input[type='number'] {
      border: 2px solid #f9c2d3;
      border-radius: 8px;
      padding: 5px;
      margin-right: 8px;
      text-align: center;
      width: 60px;
      font-weight: bold;
      color: #880e4f;
      background-color: #fffafc;
      transition: all 0.3s ease-in-out;
    }
    input[type='number']:focus {
      outline: none;
      border-color: #d63384;
      box-shadow: 0 0 6px rgba(214, 51, 132, 0.4);
    }
    .product-card button {
      background-color: #f9c2d3;
      border: none;
      padding: 10px 18px;
      margin-top: 10px;
      border-radius: 25px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
      color: #880e4f;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    .product-card button:hover {
      background-color: #d63384;
      color: white;
      transform: scale(1.05);
    }

    /* Wishlist Heart Icon */
    .wishlist-heart {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 22px;
      cursor: pointer;
      color: #bbb;
      transition: color 0.3s ease, transform 0.2s ease;
      z-index: 10;
    }
    .wishlist-heart:hover {
      transform: scale(1.2);
    }
    .wishlist-heart.active {
      color: #d63384; /* pink floral color when active */
    }
    </style>
  <script>
  function dream_list(category) {
    const cards = document.querySelectorAll('.product-card');
    cards.forEach(card => {
      const cardCategory = card.getAttribute('data-category');
      if (category === 'All' || cardCategory === category) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
  }
  document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.wishlist-heart').forEach(heart => {
    heart.addEventListener('click', function() {
      this.classList.toggle('active'); // Change color instantly
      let productId = this.getAttribute('data-id');
      let price = this.getAttribute('data-price');

      // Send to server via AJAX
      fetch('wishlist_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}&price=${price}`
      })
      .then(res => res.text())
      .then(response => console.log(response))
      .catch(err => console.error(err));
    });
  });
});
  // ✅ Auto-select category from URL if passed
  window.onload = function() {
    const params = new URLSearchParams(window.location.search);
    const selectedCategory = params.get("category");
    if (selectedCategory) {
      dream_list(selectedCategory);
    } else {
      dream_list('All'); // default: show all products
    }
  };
</script>

</head>
<body>
  <?php include 'header.php'; ?>
    <h2>Our Floral Boutique</h2>

    <div class="product-list">
    <button onclick="dream_list('All')">All</button>
        <button onclick="dream_list('Floral Bouquet')">Floral Bouquet</button>
    <button onclick="dream_list('Floral Combo')">Floral With Gifting Bouquet</button>
        <button onclick="dream_list('Floral Decoration')">Floral Decoration</button>
        <button onclick="dream_list('Saplings And Nursery Products')">Saplings And Nursery Products</button>
        <button onclick="dream_list('Floral Jewellery')">Floral Jewellery</button>
        <button onclick="dream_list('Artificial Plants')">Artificial Plants</button>
    </div>
    
    <div class="product-details">
    <?php
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $name = $row['name'];
          $price = $row['price'];
          $img = $row['image'];
          $details = $row['product_details'];
          $category = $row['category'];

          echo "
            <div class='product-card' data-category=\"$category\">
            <!-- Wishlist Heart Icon -->
              <i class='fa fa-heart wishlist-heart' 
                 data-id='{$row['id']}' 
                 data-price='{$row['price']}'></i>
              <img src='../admin_panel/pictures/$img' alt='$name'>

              <h3>$name</h3>
              <p>$details</p>
              <div class='price'>₹$price</div>
              <form method='post' action='cart_user.php'>
                <input type='hidden' name='product_id' value='{$row['id']}'>
                <input type='hidden' name='price' value='{$row['price']}'>
                <input type='number' name='qty' value='1' min='1' style='width:50px;text-align:center;'>
                <button type='submit' name='add_to_cart'>Add to Cart</button>

              </form>
            </div>
          ";
        }
      } 
      else 
      {
        echo "No Records inserted..!";
      }
    ?>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>