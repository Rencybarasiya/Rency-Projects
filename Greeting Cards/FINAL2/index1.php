<?php
session_start();
$products = [
    ['id' => 1, 'name' => 'Birthday Pop-Up Card', 'price' => 276.00, 'image' => 'card1.jpg'],
    ['id' => 2, 'name' => 'Love Greeting Card', 'price' => 199.00, 'image' => 'card2.jpg'],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Cards</title>
</head>
<body>
    <h2>All Greeting Cards</h2>
    <?php foreach ($products as $product): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px;">
            <img src="image/<?= $product['image'] ?>" width="150">
            <h3><?= $product['name'] ?></h3>
            <p>Price: ₹<?= $product['price'] ?></p>
            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="name" value="<?= $product['name'] ?>">
                <input type="hidden" name="price" value="<?= $product['price'] ?>">
                <input type="hidden" name="image" value="<?= $product['image'] ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
    <a href="cart.php">Go to Cart</a>
</body>
</html>
