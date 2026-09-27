<?php
$con = mysqli_connect("localhost", "root", "", "flowercrafts");
if (mysqli_connect_errno()) {
    echo "Failed to connect..." . mysqli_error($con);
    exit();
}

// ✅ Capture selected category from URL
$category = isset($_GET['category']) ? $_GET['category'] : '';

// ✅ Fetch products based on category
if ($category != '') {
    $q = "SELECT * FROM products WHERE Status='Active' AND Category='$category'";
} else {
    $q = "SELECT * FROM products WHERE Status='Active'";
}
$result = mysqli_query($con, $q);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products - Floreva</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #b03060;
            margin: 20px 0 10px;
        }

        /* ✅ Category Buttons */
        .category-bar {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            padding: 0 10px;
        }
        .category-bar a {
            text-decoration: none;
            background: #ffe6f0;
            padding: 8px 16px;
            border-radius: 25px;
            color: #5a1a35;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .category-bar a:hover {
            background: #ffd6e0;
            transform: scale(1.05);
        }
        .category-bar a.active {
            background: #d63384;
            color: white;
            border-color: #b03060;
            font-weight: 600;
        }

        /* ✅ Product Cards */
        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
            padding: 15px;
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
        }
        .product-card h3 {
            margin: 10px 0 5px;
            color: #5a1a35;
        }
        .product-card p {
            color: #d63384;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product-card button {
            background: #d63384;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .product-card button:hover {
            background: #b03060;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<h1>
    <?php echo ($category != '') ? htmlspecialchars($category) : "Our Products"; ?>
</h1>

<!-- ✅ CATEGORY SWITCH BUTTONS -->
<div class="category-bar">
    <?php
    $categories = [
        "Floral Bouquet",
        "Floral Decoration",
        "Floral Combo",
        "Floral Jewellery",
        "Artificial Plants",
        "Saplings And Nursery Products"
    ];

    foreach ($categories as $cat) {
        $activeClass = ($category === $cat) ? "active" : "";
        echo '<a href="product.php?category=' . urlencode($cat) . '" class="' . $activeClass . '">' . $cat . '</a>';
    }
    ?>
</div>

<!-- ✅ PRODUCT LISTING -->
<div class="product-container">
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="product-card">';
        echo '<img src="uploads/' . $row['Image'] . '" alt="' . $row['Name'] . '">';
        echo '<h3>' . $row['Name'] . '</h3>';
        echo '<p>₹' . $row['Price'] . '</p>';
        echo '<button>Add to Cart</button>';
        echo '</div>';
    }
} else {
    echo "<p style='grid-column:1/-1; text-align:center; color:#777;'>No products found for this category.</p>";
}
?>
</div>

</body>
</html>
