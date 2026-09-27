

<?php
session_start();
include("db1.php");

// Accept both user-dashboard hidden names and generic names
$incomingProductId = isset($_POST['product_id']) ? intval($_POST['product_id']) : (isset($_POST['card_id']) ? intval($_POST['card_id']) : 0);
$incomingQuantity  = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($incomingProductId > 0 && $incomingQuantity > 0) {
    $product_id = $incomingProductId;
    $quantity = $incomingQuantity;

    // Ensure cart table exists with required columns (handles older schemas gracefully)
    $conn->query("CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        username VARCHAR(255) NULL,
        card_id INT NULL,
        name VARCHAR(255) NULL,
        price DECIMAL(10,2) NULL,
        image VARCHAR(255) NULL,
        quantity INT NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Add any missing columns to align schema
    $expectedColumns = [
        'user_id' => "INT NULL",
        'username' => "VARCHAR(255) NULL",
        'card_id' => "INT NULL",
        'name' => "VARCHAR(255) NULL",
        'price' => "DECIMAL(10,2) NULL",
        'image' => "VARCHAR(255) NULL",
        'quantity' => "INT NULL",
        'created_at' => "TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"
    ];
    if ($descRes = $conn->query("DESCRIBE cart")) {
        $existing = [];
        while ($col = $descRes->fetch_assoc()) {
            $existing[strtolower($col['Field'])] = true;
        }
        foreach ($expectedColumns as $colName => $definition) {
            if (!isset($existing[strtolower($colName)])) {
                $conn->query("ALTER TABLE cart ADD COLUMN `$colName` $definition");
            }
        }
        $descRes->close();
    }

    $query = "SELECT * FROM cardss WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }

        // Persist to database cart table (per-user)
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

        // Upsert: if same user and card exists, increase quantity
        if ($userId !== null) {
            $check = $conn->prepare("SELECT id, quantity FROM cart WHERE card_id = ? AND user_id = ? LIMIT 1");
            $check->bind_param("ii", $product_id, $userId);
        } elseif ($username) {
            $check = $conn->prepare("SELECT id, quantity FROM cart WHERE card_id = ? AND username = ? LIMIT 1");
            $check->bind_param("is", $product_id, $username);
        } else {
            $check = $conn->prepare("SELECT id, quantity FROM cart WHERE card_id = ? AND user_id IS NULL AND username IS NULL LIMIT 1");
            $check->bind_param("i", $product_id);
        }

        $check->execute();
        $check->bind_result($cartRowId, $existingQty);
        if ($check->fetch()) {
            $check->close();
            $newQty = intval($existingQty) + $quantity;
            $upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $upd->bind_param("ii", $newQty, $cartRowId);
            $upd->execute();
            $upd->close();
        } else {
            $check->close();
            $ins = $conn->prepare("INSERT INTO cart (user_id, username, card_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $ins->bind_param("isisssi", $userId, $username, $product_id, $product['name'], $product['price'], $product['image'], $quantity);
            $ins->execute();
            $ins->close();
        }

        header("Location: cart.php?added=1");
        exit();
    } else {
        header("Location: cart.php?error=invalid_product");
        exit();
    }
} else {
    header("Location: cart.php?error=invalid_request");
    exit();
}
?>
