<?php
$con=mysqli_connect("localhost","root","","dream_floreva");
if (mysqli_connect_errno()) {
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
    <title>Floreva - Products</title>
    <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff0f5;
      padding: 20px;
    }
    h2 {
      text-align: center;
      color: #d63384;
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
      width: 240px;
      border: 1px solid #ffc0cb;
      padding: 15px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    .product-card img {
      width: 100%;
      border-radius: 6px;
      height: 150px;
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
    .price {
      font-size: 16px;
      font-weight: bold;
      margin: 8px 0;
      color: #444;
    }
    .hidden {
      display: none;
    }
    .qty-box {
      margin: 8px 0;
    }
    .qty-box input {
      width: 60px;
      padding: 5px;
      text-align: center;
    }
    .product-card button {
      margin-top: 10px;
      padding: 8px 14px;
      background: #d63384;
      border: none;
      color: white;
      border-radius: 6px;
      cursor: pointer;
    }
    .product-card button:hover {
      background: #a61e5c;
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

    // Update price dynamically based on quantity
    function updatePrice(input, basePrice, productId) {
      let qty = parseInt(input.value);
      if (isNaN(qty) || qty < 1) qty = 1;
      const total = qty * basePrice;
      document.getElementById("price-" + productId).innerText = "₹" + total;
    }
  </script>
</head>
<body>
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
          $id = $row['id'];
          $name = $row['name'];
          $price = $row['price']; // base price per unit
          $img = $row['image'];
          $details = $row['product_details'];
          $category = $row['category'];

          echo "
            <div class='product-card' data-category=\"$category\">
              <img src='../admin_panel/pictures/$img' alt='$name'>
              <h3>$name</h3>
              <p>$details</p>

              <div class='price' id='price-$id'>₹$price</div>

              <form method='post' action='cart_user.php'>
                <input type='hidden' name='product_id' value='{$id}'>
                <input type='hidden' name='price' value='{$price}'>

                <div class='qty-box'>
                  <label>Qty:</label>
                  <input type='number' name='qty' value='1' min='1' 
                    oninput='updatePrice(this, $price, $id)'>
                </div>

                <button type='submit' name='add_to_cart'>Add to Cart</button>
              </form>
            </div>
          ";
        }
      } else {
        echo "No Records inserted..!";
      }
    ?>
  </div>
</body>
</html>
