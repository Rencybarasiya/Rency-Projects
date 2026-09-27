<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "flowercrafts");

if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_error($con);
    exit();
}

// Only admin can delete
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']); // security
    $query = "DELETE FROM message WHERE id = $delete_id";
    mysqli_query($con, $query);
}

header("Location: feedback_admin.php?deleted=1");
exit();
?>
