<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style>
		body {
			margin: 0;
			padding: 0;
			background-color: #fff0f5;
			font-family: 'Segoe UI', sans-serif;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
		}

		.logout-box {
			background-color: #ffe6f0;
			padding: 40px;
			border-radius: 20px;
			box-shadow: 0 0 15px rgba(255, 192, 203, 0.4);
			text-align: center;
			max-width: 400px;
			width: 90%;
		}

		.logout-box h2 {
			color: #d63384;
			margin-bottom: 15px;
		}

		.logout-box p {
			color: #800040;
			font-size: 16px;
			margin: 10px 0;
		}

		.logout-box a {
			display: inline-block;
			margin-top: 20px;
			background-color: #ff99bb;
			color: white;
			text-decoration: none;
			padding: 10px 20px;
			border-radius: 8px;
			font-weight: bold;
			transition: background 0.3s ease;
		}

		.logout-box a:hover {
			background-color: #ff6fa3;
		}
	</style>
</head>
<body>
	<div class="logout-box">
        <h2>You have been logged out</h2>
        <p>Thank you for visiting Floreva Flower Shop.</p>
        <a href="login.php">Click here if not redirected</a>
    </div>
</body>
</html>