<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $_GET['id'];
$q = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($con, $q);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<p>Product not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #d63384;
            margin-bottom: 30px;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffeef5;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ffb6c1;
            border-radius: 10px;
            background-color: #fff;
            font-size: 15px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin: 12px 0;
        }

        img {
            margin: 10px 0;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #d63384;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #c2185b;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<h2 style="text-align:center; color:#d63384;">Edit Product</h2>
<form action="update.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">
    
    <input type="text" name="name" value="<?= $product['name'] ?>" required>
    <input type="number" name="price" value="<?= $product['price'] ?>" required>
    <input type="number" name="stock" value="<?= $product['stock'] ?>" required>
    <textarea name="product_details" required><?= $product['product_details'] ?></textarea>

    <select name="category" required>
        <?php
        $categories = ['Floral Bouquet', 'Floral Combo', 'Floral Decoration', 'Saplings And Nursery Products', 'Floral Jewellery', 'Artificial Plants'];
        foreach ($categories as $cat) {
            $selected = ($cat == $product['category']) ? "selected" : "";
            echo "<option value='$cat' $selected>$cat</option>";
        }
        ?>
    </select>

    <p>Current Image:</p>
    <img src="pictures/<?= $product['image'] ?>" width="80"><br>
    <input type="file" name="image">

    <select name="status">
        <option value="Active" <?= $product['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
        <option value="Inactive" <?= $product['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
    </select>

    <button type="submit">Update Product</button>
</form>
<?php include 'footer.php'; ?>
</body>
</html>
