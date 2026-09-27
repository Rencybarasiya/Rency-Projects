<?php
session_start();
$con = mysqli_connect("localhost","root","","flowercrafts");
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $user_id = $_SESSION['user_id'];

    // Get form data
    $address =  $_POST['address'];
    $address_type = $_POST['address_type'];
    $number =  $_POST['num'];

    $method =$_POST['method'];
    if (!preg_match("/^[0-9]{10}$/", $number)) {
        echo "<script>alert('Invalid phone number. Please enter a valid 10-digit number.'); history.back();</script>";
        exit;
    }
    $cart_query = "SELECT c.product_id, c.qty, p.Name, p.Price, p.Seller_id 
                   FROM cart c 
                   INNER JOIN products p ON c.product_id = p.Id 
                   WHERE c.user_id = '$user_id'";
    $cart_result = mysqli_query($con, $cart_query);

    if (mysqli_num_rows($cart_result) > 0) {
        $date = date('Y-m-d H:i:s');
        $status = "Pending";

        while ($row = mysqli_fetch_assoc($cart_result)) {
            $product_id = $row['product_id'];
            $seller_id = $row['Seller_id'];
            $product_name = mysqli_real_escape_string($con, $row['Name']);
            $price = $row['Price'];
            $qty = $row['qty'];
            $payment_status = ($method == "Cash on Delivery") ? "Pending" : "Paid";

            $query = "INSERT INTO orders 
                      (user_id, seller_id, name, number, address, address_type, method, product_id, price, qty, date, status, payment_status)
                      VALUES 
                      ('$user_id', '$seller_id', '$product_name', '$number', '$address', '$address_type', '$method', '$product_id', '$price', '$qty', '$date', '$status', '$payment_status')";

            mysqli_query($con, $query);
        }

        // Clear cart after successful order
        mysqli_query($con, "DELETE FROM cart WHERE user_id = '$user_id'");

        if($method == "Cash on Delivery") 
        {
            echo "<script>
                    alert('✅ Your order has been placed successfully! Please pay the amount to the delivery person upon receiving your items.');
                    window.location.href = 'order_client.php';
                  </script>";
        } 
        else 
        {
            echo "<script>
                    alert('✅ Payment Successful! Your order has been placed.');
                    window.location.href = 'order_client.php';
                  </script>";
        }


    } else {
        echo "<script>
                alert('❌ Your cart is empty. Please add products before placing an order.');
                window.location.href = 'product.php';
              </script>";
        exit;
    }
    
    

}   
?>
<!DOCTYPE html>
<html>
<head>
    <title>Confirm-Order - Floreva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffeef5; /* light pastel background */
            margin: 0;
            padding: 40px;
        }

        .checkout-box {
            max-width: 600px;
            margin: auto;
            background: #fff5fa; /* soft pastel pink */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .checkout-box h2 {
            text-align: center;
            color: #d63384;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #b5176f;
            display: block;
            margin-bottom: 8px;
        }

        textarea, select, input {
            width: 100%;
            padding: 12px;
            border: 1px solid #f7b6d2;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        textarea:focus, select:focus, input:focus {
            border-color: #e75480;
            outline: none;
        }

        .btn {
            display: block;
            width: 100%;
            background: #f25a9b;
            color: white;
            text-align: center;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #e0488f;
        }

        /* Hide conditional payment sections */
        .payment-section {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="checkout-box">
    <h2>Confirm Order</h2>
    <form action="place_order.php" method="POST" id="orderForm">
        <label>Delivery Address:</label>
        <textarea name="address" rows="3" required></textarea>

        <label>Address Type:</label>
        <select name="address_type" required>
            <option value="Home">Home</option>
            <option value="Office">Office</option>
        </select>

        <label>Mobile Number:</label>
        <input type="number" name="num" maxlength="10" required/>

        <label>Payment Method:</label>
        <select name="method" id="payment-method" required>
            <option value="Cash on Delivery">Cash on Delivery</option>
            <option value="UPI">UPI</option>
            <option value="Credit/Debit Card">Credit/Debit Card</option>
        </select>

        <!-- UPI Section -->
        <div id="upi-section" class="payment-section">
            <label>Enter UPI ID:</label>
            <input type="text" name="upi_id" id="upi_id" placeholder="example@upi">
        </div>

        <!-- Card Section -->
        <div id="card-section" class="payment-section">
            <label>Card Number:</label>
            <input type="text" name="card_number" id="card_number" maxlength="16" placeholder="1234 5678 9012 3456">

            <label>Expiry Date:</label>
            <input type="text" name="expiry" id="expiry" placeholder="MM/YY">

            <label>CVV:</label>
            <input type="password" name="cvv" id="cvv" maxlength="3" placeholder="123">
        </div>

        <button type="submit" class="btn">Place Order</button>
    </form>
</div>
<?php include 'footer.php'; ?>
<script>
    const paymentSelect = document.getElementById("payment-method");
    const upiSection = document.getElementById("upi-section");
    const cardSection = document.getElementById("card-section");
    const orderForm = document.getElementById("orderForm");

    paymentSelect.addEventListener("change", function() {
        upiSection.style.display = "none";
        cardSection.style.display = "none";

        if (this.value === "UPI") {
            upiSection.style.display = "block";
        } else if (this.value === "Credit/Debit Card") {
            cardSection.style.display = "block";
        }
    });

    // Validation before form submission
    orderForm.addEventListener("submit", function(e) {
        let method = paymentSelect.value;

        if (method === "UPI") {
            let upi = document.getElementById("upi_id").value.trim();
            if (!upi.includes("@")) {
                alert("Please enter a valid UPI ID.");
                e.preventDefault();
                return false;
            }
        }

        if (method === "Credit/Debit Card") {
            let cardNum = document.getElementById("card_number").value.trim();
            let expiry = document.getElementById("expiry").value.trim();
            let cvv = document.getElementById("cvv").value.trim();

            if (!/^\d{16}$/.test(cardNum)) {
                alert("Card number must be 16 digits.");
                e.preventDefault();
                return false;
            }
            if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) {
                alert("Expiry must be in MM/YY format.");
                e.preventDefault();
                return false;
            }
            if (!/^\d{3}$/.test(cvv)) {
                alert("CVV must be 3 digits.");
                e.preventDefault();
                return false;
            }
        }
        
    });
</script>

</body>
</html>
