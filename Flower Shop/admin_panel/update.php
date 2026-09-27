<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $details = $_POST['product_details'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    $image_update = "";
    if (!empty($_FILES['image']['name'])) 
    {
        $upload_dir = "pictures/";
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = $upload_dir . basename($image_name);

        // Delete old image
        $old_img_query = mysqli_query($con, "SELECT image FROM products WHERE id = $id");
        $old_img_data = mysqli_fetch_assoc($old_img_query);
        $old_image = "pictures/" . $old_img_data['image'];
        if (file_exists($old_image)) unlink($old_image);

        move_uploaded_file($image_tmp, $image_path);
        $image_update = ", image='$image_name'";
    }

    $q = "UPDATE products SET 
            name='$name', 
            price='$price', 
            stock='$stock', 
            product_details='$details', 
            category='$category', 
            status='$status'
            $image_update
          WHERE id=$id";

    if (mysqli_query($con, $q)) 
    {
        echo "<script>alert('Product updated successfully!'); window.location.href='products.php';</script>";
    } 
    else 
    {
        echo "<script>alert('Update failed!'); window.location.href='products.php';</script>";
    }
} 
else 
{
    header("Location: products.php");
}
?>
