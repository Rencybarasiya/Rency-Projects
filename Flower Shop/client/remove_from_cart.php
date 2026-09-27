<?php
session_start();
$con = mysqli_connect("localhost","root","","flowercrafts");
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = $_GET['id'];

mysqli_query($con, "DELETE FROM cart WHERE id='$cart_id' AND user_id='$user_id'");
header("Location: cart.php");
exit();
?>
