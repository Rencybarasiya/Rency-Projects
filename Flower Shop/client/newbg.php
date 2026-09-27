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

		/* HEADER */
		header {
			background: rgba(255, 255, 255, 0.3); /* slight transparent overlay */
			backdrop-filter: blur(6px);
			position: sticky;
			top: 0;
			z-index: 1000;
		}
		nav {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 12px 50px;
		}
		nav img {
			height: 55px;
		}
		nav ul {
			list-style: none;
			display: flex;
			align-items: center;
			gap: 25px;
		}
		nav ul li {
			font-weight: 600;
			color: #5a1a35;
		}
		nav ul li a {
			text-decoration: none;
			color: #5a1a35;
			transition: color 0.3s;
		}
		nav ul li a:hover {
			color: #d63384;
		}
		.icons {
			display: flex;
			gap: 20px;
			margin-left: 30px;
		}
		.icons a {
			text-decoration: none;
			color: #5a1a35;
			font-weight: 500;
		}
		.icons a:hover {
			color: #d63384;
			transform: scale(1.05);
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

		/* FOOTER */
		.footer {
			background: rgba(255, 255, 255, 0.3);
			backdrop-filter: blur(6px);
			padding: 30px 50px;
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
			gap: 30px;
			text-align: left;
		}
		.footer h3 {
			color: #d63384;
			margin-bottom: 10px;
		}
		.footer a {
			text-decoration: none;
			color: #4a2c35;
			transition: all 0.3s;
		}
		.footer a:hover {
			color: #d63384;
		}
		.footer i {
			margin-right: 8px;
			color: #d63384;
		}
	</style>
</head>
<body>

	<!-- HEADER -->
	<header>
		<nav>
			<img src="Floreva logo.png" alt="Floreva Logo">
			<ul>
				<li><a href="home.php">Home</a></li>
				<li><a href="about.php">About Floreva</a></li>
				<li><a href="#">Services</a></li>
				<li><a href="#">Feedback</a></li>
				<li><a href="#">Contact</a></li>
				<div class="icons">
					<a href="#"><i class="fas fa-shopping-cart"></i> Cart</a>
					<a href="#"><i class="fas fa-heart"></i> Wishlist</a>
					<a href="#"><i class="fas fa-user"></i> My Account</a>
				</div>
			</ul>
		</nav>
	</header>

	<!-- WELCOME SECTION -->
	<div class="welcome">
		<h1>🌸 Welcome to Floreva 🌸</h1>
		<p>Welcome to Floreva – where every bloom tells a story.  
		Discover our handcrafted floral arrangements and gifts that bring joy, love, and elegance to every moment.</p>
		<button><a href="login_user.php">Shop Now</a></button>
	</div>

	<!-- FOOTER -->
	<footer class="footer">
		<div>
			<h3>Floreva</h3>
			<p>Where every petal tells a story 🌸</p>
		</div>
		<div>
			<h3>Quick Links</h3>
			<a href="home.php">Home</a><br>
			<a href="about.php">About Floreva</a>
		</div>
		<div>
			<h3>Contact Us</h3>
			<p><i class="fas fa-envelope"></i> www.floreva.com</p>
			<p><i class="fas fa-phone"></i> +91 9054943430</p>
			<p><i class="fas fa-map-marker-alt"></i> Rajkot, Gujarat, India</p>
		</div>
		<div>
			<h3>Connect with Us</h3>
			<p><i class="fab fa-facebook"></i> <a href="#">Facebook</a></p>
			<p><i class="fab fa-instagram"></i> <a href="#">Instagram</a></p>
		</div>
	</footer>

</body>
</html>
