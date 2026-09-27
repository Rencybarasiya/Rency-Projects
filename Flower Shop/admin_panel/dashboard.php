<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Dashboard</title>
	<style>
		/* RESET */
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body {
			font-family: 'Segoe UI', sans-serif;
			background-color: #fff5f8;
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}

		/* HEADER BAR */
		header {
			background-color: #d63384;
			color: white;
			padding: 15px 30px;
			text-align: center;
			font-size: 20px;
			font-weight: bold;
			box-shadow: 0 2px 6px rgba(0,0,0,0.1);
		}

		/* MAIN CONTENT */
		.content {
			flex: 1;
			padding: 30px 20px;
			max-width: 1200px;
			margin: 0 auto;
		}
		h1 {
			text-align: center;
			color: #d63384;
			margin-bottom: 20px;
			font-size: 2rem;
		}

		/* CARDS ROW */
		.cards {
			display: flex;
			justify-content: center;
			align-items: stretch;
			gap: 20px;
			overflow-x: auto;
			padding: 10px 0 20px;
			scrollbar-width: thin;
			scrollbar-color: #ffcce0 transparent;
		}
		.cards::-webkit-scrollbar {
			height: 8px;
		}
		.cards::-webkit-scrollbar-thumb {
			background-color: #ffcce0;
			border-radius: 4px;
		}
		.card {
			flex: 0 0 220px;
			background: #fff;
			padding: 20px;
			border-radius: 15px;
			text-align: center;
			box-shadow: 0 4px 10px rgba(255, 182, 193, 0.4);
			cursor: pointer;
			transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
		}
		.card:hover {
			transform: translateY(-5px);
			box-shadow: 0 6px 15px rgba(214, 51, 132, 0.4);
		}
		.card h2 {
			font-size: 2em;
			color: #ff4d94;
		}
		.card p {
			margin-top: 8px;
			font-weight: bold;
			color: #5a1a35;
		}

		/* TABLE SECTION */
		.table-section {
			display: none;
			margin-top: 30px;
			background: white;
			padding: 15px;
			border-radius: 12px;
			box-shadow: 0 4px 12px rgba(255, 182, 193, 0.4);
			overflow-x: auto;
		}
		.table-title {
		    font-size: 1.8rem;
		    text-align: center;
		    color: #d63384; /* Pink Floral */
		    margin-bottom: 15px;
		    font-weight: bold;
		    letter-spacing: 1px;
		}

		h2 {
			color: #d63384;
			margin-bottom: 10px;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			min-width: 900px;
		}
		th, td {
			padding: 10px;
			text-align: left;
			border-bottom: 1px solid #f3c4d9;
		}
		th {
			background-color: #ffcce0;
			color: #5a1a35;
			position: sticky;
			top: 0;
			z-index: 1;
		}
		tr:hover {
			background-color: #fff0f5;
		}

		/* BUTTONS */
		.btn-edit, .btn-del {
			padding: 6px 12px;
			border-radius: 6px;
			text-decoration: none;
			font-size: 13px;
			margin: 2px;
			display: inline-block;
			transition: 0.3s;
		}
		.btn-edit { background-color: #28a745; color: #fff; }
		.btn-edit:hover { background-color: #218838; }
		.btn-del { background-color: #dc3545; color: #fff; }
		.btn-del:hover { background-color: #c82333; }

		/* FOOTER */
		footer {
			background-color: #d63384;
			color: white;
			text-align: center;
			padding: 10px;
			font-size: 14px;
			box-shadow: 0 -2px 6px rgba(0,0,0,0.1);
			margin-top: auto;
		}
	</style>
</head>
<body>
	<?php include 'header.php'; ?>

	<!-- Main Content -->
	<div class="content">
		<h1>Admin Dashboard</h1>

		<?php
		$total_products = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as count FROM products"))['count'];
		$total_orders   = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as count FROM orders"))['count'];
		$total_users    = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as count FROM users"))['count'];
		$total_feedback = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as count FROM message"))['count'];
		?>

		<!-- Summary Cards -->
		<div class="cards">
			<div class="card" onclick="showTable('productsTable')">
				<h2><?php echo $total_products; ?></h2>
				<p>Total Products</p>
			</div>
			<div class="card" onclick="showTable('ordersTable')">
				<h2><?php echo $total_orders; ?></h2>
				<p>Total Orders</p>
			</div>
			<div class="card" onclick="showTable('usersTable')">
				<h2><?php echo $total_users; ?></h2>
				<p>Total Users</p>
			</div>
			<div class="card" onclick="showTable('feedbackTable')">
				<h2><?php echo $total_feedback; ?></h2>
				<p>Total Feedback</p>
			</div>
		</div>

		<!-- Tables -->
		<div id="productsTable" class="table-section">
			<h2><a href="products.php">Products</a></h2>
			<table>
				<tr>
					<th>Id</th><th>Seller_id</th><th>Name</th><th>Price</th><th>Image</th><th>Stock</th><th>Product_details</th><th>Status</th><th>Updation</th>
				</tr>
				<?php
				$q="SELECT * FROM products";
				$result=mysqli_query($con,$q);
				if (mysqli_num_rows($result)>0) {
					while ($row=mysqli_fetch_assoc($result)) {
						echo "<tr>";
							echo "<td>".$row['id']."</td>";
							echo "<td>".$row['seller_id']."</td>";
							echo "<td>".$row['name']."</td>";
							echo "<td>".$row['price']."</td>";
							echo "<td>".$row['image']."</td>";
							echo "<td>".$row['stock']."</td>";
							echo "<td>".$row['product_details']."</td>";
							echo "<td>".$row['status']."</td>";
							echo "<td>
							<a href='edit.php?id=".$row['id']."' class='btn-edit'>Edit</a>
							<a href='delete.php?id=".$row['id']."' class='btn-del' onclick='return confirm(\"Delete this product?\")'>Delete</a>
							</td>";
						echo "</tr>";
					}
				}
				?>
			</table>
		</div>

		<div id="ordersTable" class="table-section">
			<h2><a href="order_admin.php">Orders</a></h2>
			<table>
				<tr>
					<th>Id</th><th>User_id</th><th>Seller_id</th><th>Name</th><th>Number</th><th>Email</th><th>Address</th><th>Address_type</th><th>Method</th><th>Product_id</th><th>Price</th><th>Qty</th><th>Date</th><th>Status</th><th>Payment_status</th><th>Updation</th>
				</tr>
				<?php
				$q="SELECT * FROM orders";
				$result=mysqli_query($con,$q);
				if (mysqli_num_rows($result)>0) {
					while ($row=mysqli_fetch_assoc($result)) {
						echo "<tr>";
							echo "<td>".$row['id']."</td>";
							echo "<td>".$row['user_id']."</td>";
							echo "<td>".$row['seller_id']."</td>";
							echo "<td>".$row['name']."</td>";
							echo "<td>".$row['number']."</td>";
							echo "<td>".$row['email']."</td>";
							echo "<td>".$row['address']."</td>";
							echo "<td>".$row['address_type']."</td>";
							echo "<td>".$row['method']."</td>";
							echo "<td>".$row['product_id']."</td>";
							echo "<td>".$row['price']."</td>";
							echo "<td>".$row['qty']."</td>";
							echo "<td>".$row['date']."</td>";
							echo "<td>".$row['status']."</td>";
							echo "<td>".$row['payment_status']."</td>";
							echo "<td>
							<a href='edit_order.php?id=".$row['id']."' class='btn-edit'>Update</a>
							<a href='delete_order.php?id=".$row['id']."' class='btn-del' onclick='return confirm(\"Delete this order?\")'>Delete</a>
							</td>";
						echo "</tr>";
					}
				}
				?>
			</table>
		</div>

		<div id="usersTable" class="table-section">
			<h2><a href="user.php">Users</a></h2>
			<table>
				<tr>
					<th>Id</th><th>Name</th><th>Email</th><th>Password</th><th>Image</th><th>Updation</th>
				</tr>
				<?php
				$q="SELECT * FROM users";
				$result=mysqli_query($con,$q);
				if (mysqli_num_rows($result)>0) {
					while ($row=mysqli_fetch_assoc($result)) {
						echo "<tr>";
							echo "<td>".$row['id']."</td>";
							echo "<td>".$row['name']."</td>";
							echo "<td>".$row['email']."</td>";
							echo "<td>".$row['password']."</td>";
							echo "<td>".$row['image']."</td>";
							echo"<td>
							<a href='user.php?id=".$row['id']."' class='btn-del' onclick='return confirm(\"Delete this user?\")'>Delete</a>
						</td>";
						echo "</tr>";
					}
				}
				?>
			</table>
		</div>

		<div id="feedbackTable" class="table-section">
			<h2><a href="feedback_admin.php">Feedback</a></h2>
			<table>
				<tr>
					<th>Id</th><th>User_id</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Rating</th><th>Updation</th>
				</tr>
				<?php
				$q="SELECT * FROM message";
				$result=mysqli_query($con,$q);
				if (mysqli_num_rows($result)>0) {
					while ($row=mysqli_fetch_assoc($result)) {
						echo "<tr>";
							echo "<td>".$row['id']."</td>";
							echo "<td>".$row['user_id']."</td>";
							echo "<td>".$row['name']."</td>";
							echo "<td>".$row['email']."</td>";
							echo "<td>".$row['subject']."</td>";
							echo "<td>".$row['message']."</td>";
							echo "<td>".$row['rating']."</td>";
							echo"<td>
							<a href='feedback_admin.php?id=".$row['id']."' class='btn-del' onclick='return confirm(\"Delete this feedback?\")'>Delete</a>
							</td>";
						echo "</tr>";
					}
				}
				?>
			</table>
		</div>
	</div>
	<?php include 'footer.php'; ?>
	<!-- JS for toggle -->
	<script>
		function showTable(id) {
			// hide all tables
			document.querySelectorAll('.table-section').forEach(function(sec){
				sec.style.display = "none";
			});
			// show selected
			document.getElementById(id).style.display = "block";
			// scroll to table
			window.scrollTo({ top: document.getElementById(id).offsetTop, behavior: "smooth" });
		}
	</script>
</body>
</html>
