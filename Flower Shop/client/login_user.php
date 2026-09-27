<?php 
session_start();
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
	echo "Failed to connect...".mysqli_error();
	exit();
}
if (isset($_POST['login'])) 
{
	$email=$_POST['email'];
	$password=$_POST['password'];
	$q="SELECT * FROM users where email='$email'";
	$result=mysqli_query($con,$q);
	if (mysqli_num_rows($result)>0) 
	{
		while ($row=mysqli_fetch_assoc($result)) 
		{
			if ($password == $row['password']) 
			{
				$_SESSION['user_id']=$row['id'];
				$_SESSION['name'] = $row['name'];
				$_SESSION['image'] = $row['image'];
				header("Location: product.php");
                exit();
			}
			else
			{
				echo "<script>alert('Password not match');</script>";
			}
		}
	}
	else 
	{
        echo "<script>alert('User not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Floreva-User login</title>
	<style>
		body {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', sans-serif;
                background: url('flbg.jpg') no-repeat center center fixed; /* your uploaded image */
                background-size: cover; /* makes the image cover the whole screen */
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            /* Add a soft overlay so text pops */
            body::before {
                content: "";
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(255, 192, 203, 0.3); /* light pink transparent overlay */
                z-index: 0;
            }

            /* Login Box Styling */
            .login-box {
                position: relative;
                background: rgba(255, 255, 255, 0.9); /* translucent white */
                border: 1px solid #f8c8dc;
                padding: 40px 35px;
                border-radius: 20px;
                box-shadow: 0 10px 25px rgba(243, 197, 208, 0.6);
                width: 100%;
                max-width: 400px;
                z-index: 1; /* keep it above overlay */
                backdrop-filter: blur(6px); /* dreamy glass effect */
            }

            .login-box h2 {
                text-align: center;
                color: #d63384;
                margin-bottom: 30px;
                font-size: 28px;
                font-family: 'Playfair Display', serif;
                letter-spacing: 1px;
            }

            label {
                display: block;
                margin-bottom: 10px;
                color: #d63384;
                font-weight: 600;
                font-size: 18px;
                font-family: 'Playfair Display', serif;
            }

            input[type="email"],
            input[type="password"] {
                width: 100%;
                padding: 12px;
                border: 1px solid #f8c8dc;
                border-radius: 10px;
                font-size: 15px;
                background: #fff;
                box-sizing: border-box;
                transition: 0.3s;
            }

            input[type="email"],
            input[type="password"] {
                width: 100%;
                padding: 12px;
                border: 1px solid #f8c8dc;
                border-radius: 10px;
                font-size: 15px;
                background: #fff;
                box-sizing: border-box;
                transition: 0.3s;
                margin-bottom: 18px;  /* 🌸 added spacing between fields */
            }

            input[type="submit"] {
                width: 100%;
                padding: 12px;
                background: linear-gradient(to right, #ff85a1, #d63384);
                color: white;
                border: none;
                border-radius: 25px;
                font-size: 16px;
                font-weight: bold;
                cursor: pointer;
                transition: 0.3s ease;
                margin-top: 5px;  /* 🌸 space above button */
            }

            input[type="submit"]:hover {
                background: linear-gradient(to right, #d63384, #a82a63);
                transform: scale(1.05);
            }

            .register-link {
                text-align: center;
                margin-top: 15px;
            }

            .register-link a {
                color: #d63384;
                text-decoration: none;
                font-weight: bold;
                transition: 0.3s;
            }

            .register-link a:hover {
                text-decoration: underline;
                color: #a82a63;
            }

            		@media (max-width: 480px) {
            			.login-box {
            				padding: 30px 20px;
            				width: 90%;
            			}
            		}
	</style>
</head>
<body>
    <?php include 'header.php'; ?>
	<div class="login-box">
		<h2>Login To Floreva</h2>
		<form method="post">
			<div class="frmgr">
				<label>Email:</label>
				<input type="email" name="email" required><br>
			</div>
			<div class="frmgr">
				<label for="password">Password:</label>
				<input type="password" name="password" required><br>

				<input type="submit" name="login" value="Login">
			</div>
		</form>
		<div class="register-link">
			<p>Don't have an account? <a href="register_user.php">Register</a></p>
		</div>
	</div>
    <?php include 'footer.php'; ?>
</body>
</html>