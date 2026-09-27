<?php
session_start();
$con=mysqli_connect("localhost","root","","flowercrafts");
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}
$user_id = $_SESSION['user_id']; 
$q = "SELECT o.id, o.product_id, o.qty, o.price, o.address, o.address_type, 
             o.method, o.status, o.payment_status, o.date,
             p.name AS product_name, p.image AS product_image
      FROM orders o 
      JOIN products p ON o.product_id = p.id
      WHERE o.user_id = '$user_id'
      ORDER BY o.date DESC";
$result = mysqli_query($con, $q);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Orders-Client</title>
	<style>
		body {
			font-family: 'Segoe UI', sans-serif;
			background-color: #fff5f8; /* soft pastel pink */
			color: #333;
			padding: 40px;
		}

		h2 {
			color: #d6336c; /* darker pink for heading */
			text-align: center;
			margin-bottom: 30px;
			font-size: 28px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			background-color: #fff;
			box-shadow: 0 4px 20px rgba(214, 51, 108, 0.15);
			border-radius: 12px;
			overflow: hidden;
		}

		th, td {
			padding: 14px 16px;
			text-align: center;
			border-bottom: 1px solid #f8cdda;
		}

		th {
			background-color: #fbb1bd; /* pastel pink header */
			color: #5a0d2d;
			font-weight: bold;
			font-size: 15px;
		}

		tr:hover {
			background-color: #ffe6ec; /* light hover */
		}

		td {
			color: #444;
			font-size: 14px;
		}

		.container {
			max-width: 1100px;
			margin: auto;
		}

		.product-img {
			width: 70px;
			height: 70px;
			object-fit: cover;
			border-radius: 10px;
			border: 2px solid #fbb1bd;
		}

		.download-btn {
			background-color: #d6336c;
			color: #fff;
			padding: 8px 14px;
			border-radius: 8px;
			text-decoration: none;
			font-size: 14px;
			font-weight: 500;
			display: inline-block;
			transition: 0.3s ease;
			border: none;
			cursor: pointer;
		}

		.download-btn:hover {
			background-color: #b02553;
			transform: scale(1.05);
		}
	</style>
</head>
<body>
	<?php include 'header.php'; ?>
	<div class="container">
		<h2>My Orders</h2>
		<table>
		    <tr>
		        <th>Product ID</th>
		        <th>Image</th>
		        <th>Product</th>
		        <th>Qty</th>
		        <th>Price</th>
		        <th>Address</th>
		        <th>Type</th>
		        <th>Method</th>
		        <th>Status</th>
		        <th>Payment</th>
		        <th>Date</th>
		        <th>Download Bill</th>
		    </tr>
		    <?php
			    if (mysqli_num_rows($result) > 0) 
			    {
			        while($row = mysqli_fetch_assoc($result)) {
			            echo "<tr>
			                <td>{$row['product_id']}</td>
			                <td><img src='../admin_panel/pictures/{$row['product_image']}' class='product-img'></td>
			                <td>{$row['product_name']}</td>
			                <td>{$row['qty']}</td>
			                <td>₹{$row['price']}</td>
			                <td>{$row['address']}</td>
			                <td>{$row['address_type']}</td>
			                <td>{$row['method']}</td>
			                <td>{$row['status']}</td>
			                <td>{$row['payment_status']}</td>
			                <td>{$row['date']}</td>
			                <td><button class='download-btn' onclick='printBill({$row['id']}, this)'>Download</button></td>
			            </tr>";
			        }
			    } 
			    else 
			    {
			        echo "<tr><td colspan='11'>No orders found.</td></tr>";
			    }
	    	?>
		</table>
	</div>
<?php include 'footer.php'; ?>
<script>
function printBill(orderId, btn) {
    let row = btn.closest("tr");
    let cells = row.querySelectorAll("td");

    let productId = cells[0].innerText;
    let productImg = cells[1].querySelector("img").src;
    let productName = cells[2].innerText;
    let qty = cells[3].innerText;
    let price = cells[4].innerText;
    let address = cells[5].innerText;
    let type = cells[6].innerText;
    let method = cells[7].innerText;
    let status = cells[8].innerText;
    let payment = cells[9].innerText;
    let date = cells[10].innerText;

    let billHtml = `
        <html>
        <head>
            <title>Floreva Bill</title>
            <style>
                body { font-family: 'Segoe UI', sans-serif; padding:20px; background:#fff5f8; }
                h2 { color:#d6336c; text-align:center; }
                table { width:100%; border-collapse:collapse; margin-top:20px; }
                th, td { border:1px solid #f8cdda; padding:10px; text-align:left; }
                th { background:#fbb1bd; color:#5a0d2d; }
                .product-img { width:100px; height:100px; border-radius:10px; border:2px solid #fbb1bd; }
                .footer { margin-top:30px; text-align:center; font-style:italic; color:#555; }
            </style>
        </head>
        <body>
            <h2>Floreva - Order Invoice</h2>
            <table>
                <tr><th>Order ID</th><td>${orderId}</td></tr>
                <tr><th>Product ID</th><td>${productId}</td></tr>
                <tr><th>Product</th><td>${productName}</td></tr>
                <tr><th>Product Image</th><td><img src="${productImg}" class="product-img"></td></tr>
                <tr><th>Quantity</th><td>${qty}</td></tr>
                <tr><th>Price</th><td>${price}</td></tr>
                <tr><th>Address</th><td>${address} (${type})</td></tr>
                <tr><th>Payment Method</th><td>${method}</td></tr>
                <tr><th>Status</th><td>${status}</td></tr>
                <tr><th>Payment Status</th><td>${payment}</td></tr>
                <tr><th>Date</th><td>${date}</td></tr>
            </table>
            <div class="footer">Thank you for shopping with Floreva!</div>
        </body>
        </html>
    `;

    let win = window.open('', '', 'height=700,width=900');
    win.document.write(billHtml);
    win.document.close();
    win.print();
}
</script>

</body>
</html>
