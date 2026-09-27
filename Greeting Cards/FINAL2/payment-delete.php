<?php
include 'db1.php';

if (isset($_GET['id'])) {
    $payment_id = intval($_GET['id']);

    $sql = "DELETE FROM payments WHERE payment_id = $payment_id";
    if (mysqli_query($conn, $sql)) {
        header("Location: manage-payment.php?deleted=true");
        exit();
    } else {
        echo "<p style='color:red;'>Error deleting payment: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color:red;'>No payment ID specified.</p>";
}
?>
