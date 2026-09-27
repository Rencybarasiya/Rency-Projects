<?php
include 'db.php';

if (isset($_GET['id'])) 
{
    $id = $_GET['id'];

    // Get image name to delete the file
    $img_query = mysqli_query($con, "SELECT image FROM products WHERE id = $id");
    $img_data = mysqli_fetch_assoc($img_query);
    $image_path = "pictures/" . $img_data['image'];

    // Delete from database
    $q = "DELETE FROM products WHERE id = $id";
    if (mysqli_query($con, $q)) 
    {
        if (file_exists($image_path)) 
        {
            unlink($image_path); // Delete image file
        }
        echo "<script>alert('Product deleted successfully!'); window.location.href='products.php';</script>";
    } 
    else 
    {
        echo "<script>alert('Failed to delete product'); window.location.href='products.php';</script>";
    }
} 
else 
{
    header("Location: products.php");
}
?>
