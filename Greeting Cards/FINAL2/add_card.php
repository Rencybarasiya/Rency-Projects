<?php include 'header.php'; ?>

<?php

$conn = new mysqli("localhost", "root", "", "heycardy");
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']); 
    
    $category = $conn->real_escape_string($_POST['category']);
    $subcategory = $conn->real_escape_string($_POST['subcategory']);

    // Image upload
    $image = $_FILES['image']['name'];
    $target = "image/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO cardss (name, image, price, category, subcategory)
                VALUES ('$name', '$image', '$price','$category','$subcategory')";
        if ($conn->query($sql)) {
            echo "<script>alert('card Added Successfully');</script>"; 
        } else { 
            echo "Error: " . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Card</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #ffe6f0, #fff0f5);
            margin: 0;
            padding: 20px;
        }

        .form-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 420px;
            margin: 40px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #ff69b4;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }

        button[type="submit"],
        form button {
            width: 100%;
            padding: 12px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover,
        form button:hover {
            background-color: #e055a2;
        }

        .view-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Card</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="image">Image:</label>
        <input type="file" name="image" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label for="category">Category:</label>
        <select name="category" required>
            <option value="cards">Cards</option>
        </select>

        <label for="subcategory">Sub Category:</label>
        <select name="subcategory" required>
            <option value="Birthday">Birthday</option>
            <option value="Sorry">Sorry</option>
            <option value="Welcome">Welcome</option>
            <option value="Thankyou">Thankyou</option>
            <option value="Congratulation">Congratulation</option>
            <option value="Anniversary">Anniversary</option>
        </select>

        <button type="submit" name="add_card">Add Card</button>
    </form>

    <form action="view_card.php" method="GET" class="view-button">
        <button type="submit">View Cards</button>
    </form>
</div>

</body>
</html>

<?php include 'footer1.php'; ?>