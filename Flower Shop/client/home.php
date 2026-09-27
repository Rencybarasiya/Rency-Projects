<?php
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
    echo "Failed to connect...".mysqli_error();
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Floreva</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body {
			font-family: 'Segoe UI', sans-serif;
			background: url("flbg.jpg") no-repeat center center/cover;
			color: #4a2c35;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}
		/* WELCOME SECTION */
		.welcome {
			text-align: center;
			padding: 80px 20px;
			flex: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
		}
		.welcome h1 {
			color: #b03060;
			font-size: 3rem;
			margin-bottom: 15px;
			text-shadow: 1px 1px 4px rgba(255,255,255,0.8);
		}
		.welcome p {
			font-size: 1.2rem;
			line-height: 1.8;
			margin-bottom: 20px;
			color: #4a2c35;
			max-width: 700px;
		}
		.welcome button {
			background: #d63384;
			color: white;
			padding: 14px 35px;
			border: none;
			border-radius: 30px;
			font-size: 1.1rem;
			cursor: pointer;
			box-shadow: 0px 6px 15px rgba(214, 51, 132, 0.4);
			transition: all 0.3s ease;
		}
		.welcome button:hover {
			background: #b03060;
			transform: scale(1.05);
		}
		.welcome button a {
			text-decoration: none;
			color: white;
			font-weight: 600;
		}
	</style>
</head>
<body>
	<?php include 'header.php'; ?>
	<div class="welcome">
	  <h1>🌸 Welcome to Floreva 🌸</h1>
	  <p>
	    Floreva is where nature blossoms into art. 🌸 
	    <p>We weave joy through blooms, gifts, and greenery — turning every moment into something worth cherishing.  
	    From petals to plants, we bring the beauty of nature to your heart, your home, and your celebrations. 🌿✨</p>
	  </p>
	  <button><a href="login_user.php">Shop Now</a></button>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>
