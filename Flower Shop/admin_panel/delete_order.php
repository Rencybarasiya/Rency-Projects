<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "flowercrafts");

if (!$con) die("DB Connection Failed: " . mysqli_connect_error());
//if (!isset($_SESSION['admin_id'])) header("Location: login.php");

// Check ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $del_query = "DELETE FROM orders WHERE id='$id'";
    if (mysqli_query($con, $del_query)) {
        header("Location: order_admin.php?deleted=1");
        exit();
    } else {
        die("Delete Error: " . mysqli_error($con));
    }
} else {
    header("Location: order_admin.php");
    exit();
}
?>
