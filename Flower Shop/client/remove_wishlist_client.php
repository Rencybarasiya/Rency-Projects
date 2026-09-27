<?php
session_start();
$con = mysqli_connect("localhost","root","","flowercrafts");
if (!$con) die("DB Connection Failed: " . mysqli_connect_error());

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['wishlist_id'])) {
    $wishlist_id = intval($_POST['wishlist_id']);
    $user_id = $_SESSION['user_id'];

    $q = "DELETE FROM wishlist WHERE id=$wishlist_id AND user_id=$user_id";
    mysqli_query($con, $q);
}
header("Location: wishlist_client.php");
exit();
?>
