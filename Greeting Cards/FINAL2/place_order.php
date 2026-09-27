<?php
session_start();
include 'db1.php';

// --- If cart is empty, redirect ---
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// --- If not POST, redirect ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit();
}

// --- Get user info ---
$user_id = 0;
$username = isset($_SESSION['user']) ? $_SESSION['user'] : '';

if ($username) {
    $stmt = $conn->prepare("SELECT id FROM user WHERE username=? LIMIT 1");
    if (!$stmt) {
        die("SQL Prepare Error (user fetch): " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($uid);
    if ($stmt->fetch()) {
        $user_id = $uid;
        $_SESSION['user_id'] = $user_id; // store for next time
    }
    $stmt->close();
}

// --- Safety check ---
if ($user_id == 0) {
    die("❌ Unable to find user ID. Please log in again.");
}

// --- Collect form data ---
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment_method = trim($_POST['payment_method'] ?? '');
$total = floatval($_POST['total'] ?? 0);
$created_at = date('Y-m-d H:i:s');

// --- Validate ---
if (empty($first_name) || empty($email) || $total <= 0) {
    die("⚠️ Invalid order data. Please go back and try again.");
}

// --- Insert into orders table ---
$order_query = "INSERT INTO orders (user_id, username, first_name, last_name, email, phone, address, payment_method, total, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PLACED', ?)";
$stmt = $conn->prepare($order_query);

if (!$stmt) {
    die("SQL Prepare Error (orders): " . $conn->error);
}

$stmt->bind_param("isssssssss", $user_id, $username, $first_name, $last_name, $email, $phone, $address, $payment_method, $total, $created_at);

if (!$stmt->execute()) {
    die("Order Insert Error: " . $stmt->error);
}

$order_id = $stmt->insert_id; // get order ID
$stmt->close();

// --- Insert each item into payments table ---
foreach ($_SESSION['cart'] as $item) {
    $product_name = $item['name'];
    $amount = floatval($item['price'] * $item['quantity']);
    $paid_on = date('Y-m-d');

    $pay_stmt = $conn->prepare("INSERT INTO payments (user_id, payment_method, total_payment, product_name, paid_on) VALUES (?, ?, ?, ?, ?)");
    
    if (!$pay_stmt) {
        die("SQL Prepare Error (payments): " . $conn->error);
    }

    $pay_stmt->bind_param("isdss", $user_id, $payment_method, $amount, $product_name, $paid_on);

    if (!$pay_stmt->execute()) {
        echo "⚠️ Payment insert error: " . $pay_stmt->error . "<br>";
    }

    $pay_stmt->close();
}

// --- Extra: Insert into upi_payments or card_payments based on method ---
$paid_on = date('Y-m-d');

if ($payment_method === 'UPI') {
    $upi_id = trim($_POST['upi_id'] ?? '');
    if ($upi_id !== '') {
        $stmt = $conn->prepare("INSERT INTO upi_payments (user_id, upi_id, paid_on) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iss", $user_id, $upi_id, $paid_on);
            if (!$stmt->execute()) {
                error_log("UPI payment insert failed: " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("Prepare failed for UPI insert: " . $conn->error);
        }
    }
} elseif ($payment_method === 'Card') {
    $card_number = trim($_POST['card_number'] ?? '');
    // Save last 4 digits only, sanitize input
    $card_digits = preg_replace('/\D/', '', $card_number);
    $card_last4 = strlen($card_digits) >= 4 ? substr($card_digits, -4) : '';

    if ($card_last4 !== '') {
        $stmt = $conn->prepare("INSERT INTO card_payments (user_id, card_last4, paid_on) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iss", $user_id, $card_last4, $paid_on);
            if (!$stmt->execute()) {
                error_log("Card payment insert failed: " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("Prepare failed for Card insert: " . $conn->error);
        }
    }
}

// --- Clear cart after placing order ---
unset($_SESSION['cart']);

// --- Success Page ---
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<title>Order Placed</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #f8f8f8;
  text-align: center;
  padding: 80px;
}
h1 {
  color: #4caf50;
  font-size: 28px;
}
p {
  font-size: 18px;
  color: #333;
}
a {
  display: inline-block;
  margin-top: 20px;
  text-decoration: none;
  color: white;
  background: #e91e63;
  padding: 10px 20px;
  border-radius: 6px;
  font-weight: 500;
}
</style>
</head>
<body>
<h1>🎉 Your Order Has Been Successfully Placed!</h1>
<p>Thank you, <strong>" . htmlspecialchars($first_name) . "</strong>.</p>
<p>Your Order ID is <strong>" . intval($order_id) . "</strong>.</p>
<p>Payment Method: <strong>" . htmlspecialchars($payment_method) . "</strong></p>
<p>Total Amount: <strong>₹" . number_format($total, 2) . "</strong></p>
<a href='user-dashboard.php'>Back to Dashboard</a>
</body>
</html>
";
?>
