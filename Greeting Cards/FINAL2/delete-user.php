<?php
include 'db1.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize id

    $sql = "DELETE FROM user WHERE id = $id"; // or 'users' if your table name is users
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error deleting user: " . mysqli_error($conn));
    }

    header("Location: manage-user.php?deleted=true");
    exit();
} else {
    header("Location: manage-user.php");
    exit();
}
?>
