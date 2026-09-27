<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "flowercrafts");

if (!$con) {
    die("DB Connection Failed: " . mysqli_connect_error());
}

/*if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}*/

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Fetch image to delete from server
    $img_query = mysqli_query($con, "SELECT image FROM users WHERE id = $user_id");
    $img_data = mysqli_fetch_assoc($img_query);

    if ($img_data && !empty($img_data['image']) && file_exists("photos/".$img_data['image'])) {
        unlink("photos/".$img_data['image']); // delete image file
    }

    $delete_query = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($con, $delete_query)) {
        header("Location: user.php?msg=User deleted successfully");
    } else {
        echo "Error deleting user: " . mysqli_error($con);
    }
} else {
    echo "Invalid request.";
}
?>
