<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $details = $_POST['product_details'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $seller_id = 1;

    $upload_dir = "pictures/";
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = $upload_dir . basename($image_name);

    if (move_uploaded_file($image_tmp, $image_folder)) {
        $q = "INSERT INTO products (Seller_id, Name, Price, Image, Stock, Product_details, Category, Status) 
              VALUES ('$seller_id', '$name', '$price', '$image_name', '$stock', '$details', '$category', '$status')";

        if (mysqli_query($con, $q)) {
            echo "<script>alert('Product added successfully!');</script>";
        } else {
            echo "<script>alert('Failed to add product');</script>";
        }
    } else {
        echo "<script>alert('Image upload failed');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Products</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            padding: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #d63384;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            background-color: #ffe6f0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 16px;
            text-align: center;
            border-bottom: 1px solid #fcdce4;
        }

        th {
            background-color: #ffd6e7;
            color: #880e4f;
            font-weight: bold;
        }

        td img {
            width: 60px;
            height: auto;
            border-radius: 50%;
        }

        a {
            color: #d63384;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        form {
            background-color: #ffeef5;
            padding: 20px;
            border-radius: 12px;
            width: 450px;
            margin: 0 auto;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 5px;
            margin: 10px 0;
            border: 1px solid #ffb6c1;
            border-radius: 8px;
            background-color: #fff;
        }

        input[type="file"] {
            margin: 10px 0;
        }

        button {
            background-color: #d63384;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
            width: 100%;
        }

        button:hover {
            background-color: #c2185b;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>Floral Boutique</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required/><br>
        <input type="number" name="price" placeholder="Price" required/><br>
        <input type="number" name="stock" placeholder="Stock" required/><br>
        <textarea name="product_details" placeholder="Details" required></textarea><br>

        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Floral Bouquet">Floral Bouquet</option>
            <option value="Floral Combo">Floral Combo</option>
            <option value="Floral Decoration">Floral Decoration</option>
            <option value="Saplings And Nursery Products">Saplings And Nursery Products</option>
            <option value="Floral Jewellery">Floral Jewellery</option>
            <option value="Artificial Plants">Artificial Plants</option>
        </select><br>
        <input type="file" name="image" required/><br>
        <select name="status">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select><br>
        <button type="submit">Add Product</button>
    </form>

    <hr><br>

        <?php
        $q = "SELECT * FROM products ORDER BY id DESC";
        $result = mysqli_query($con, $q);
        if (!$result) {
            die("<p style='color:red;'>SQL Error: " . mysqli_error($con) . "</p>");
        }

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr><th>Id</th><th>Seller_id</th><th>Name</th><th>Price</th><th>Image</th><th>Stock</th><th>Product_details</th><th>Category</th><th>Status</th><th>Update</th><th>Delete</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['seller_id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['price']."</td>";
                echo "<td><img src='pictures/".$row['image']."' alt='Product Image'></td>";
                echo "<td>".$row['stock']."</td>";
                echo "<td>".$row['product_details']."</td>";
                echo "<td>".$row['category']."</td>";
                echo "<td>".$row['status']."</td>";
                echo "<td><a href='edit.php?id=".$row['id']."'>Update</a></td>";
                echo "<td><a href='delete.php?id=".$row['id']."'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No Products Found..?</p>";
        }
        ?>
<?php include 'footer.php'; ?>
</body>
</html>
