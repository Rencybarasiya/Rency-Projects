<?php
session_start();
include 'db1.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        // Array keys ne reindex karvu (important)
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    // Also remove from database for current user if exists
    $username = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    $userId = null;
    if ($username) {
        $u = $conn->prepare("SELECT id FROM user WHERE username = ? LIMIT 1");
        if ($u) {
            $u->bind_param("s", $username);
            $u->execute();
            $u->bind_result($uid);
            if ($u->fetch()) {
                $userId = intval($uid);
            }
            $u->close();
        }
    }

    if ($conn) {
        if ($userId !== null) {
            $del = $conn->prepare("DELETE FROM cart WHERE card_id = ? AND user_id = ?");
            $del->bind_param("ii", $id, $userId);
            $del->execute();
            $del->close();
        } elseif ($username) {
            $del = $conn->prepare("DELETE FROM cart WHERE card_id = ? AND username = ?");
            $del->bind_param("is", $id, $username);
            $del->execute();
            $del->close();
        } else {
            // guest: nothing to delete per-user in DB
        }
    }
}

header("Location: cart.php");
exit();
