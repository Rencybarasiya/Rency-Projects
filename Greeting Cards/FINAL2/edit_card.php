<?php
$conn = new mysqli("localhost", "root", "", "heycardy1");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $res = $conn->query("SELECT * FROM cardss WHERE id = $id");
    $card = $res->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];

    $conn->query("UPDATE cardss SET name='$name', price='$price', category='$category', subcategory='$subcategory' WHERE id = $id");
    header("Location: view_card.php"); // back to list
}
?>

<form method="post">
    Name: <input type="text" name="name" value="<?php echo $card['name']; ?>"><br>
    Price: <input type="text" name="price" value="<?php echo $card['price']; ?>"><br>
    Category: <input type="text" name="category" value="<?php echo $card['category']; ?>"><br>
    Subcategory: <input type="text" name="subcategory" value="<?php echo $card['subcategory']; ?>"><br>
    <input type="submit" value="Update">
</form>
