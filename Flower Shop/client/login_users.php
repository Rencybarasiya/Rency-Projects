<?php 
include 'db.php';
session_start();
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
				header("Location: products.php");
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
			background-color: #ffeef2;
			font-family: 'Segoe UI', sans-serif;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
		}

		.login-box {
			background-color: #fff0f5;
			border: 2px solid #f8c8dc;
			padding: 40px 30px;
			border-radius: 20px;
			box-shadow: 0 0 15px #f3c5d0;
			width: 350px;
		}

		h2 {
			text-align: center;
			color: #d63384;
			margin-bottom: 30px;
		}

		input[type="email"],
		input[type="password"] {
			width: 100%;
			padding: 12px;
			margin-bottom: 20px;
			border: 1px solid #f8c8dc;
			border-radius: 10px;
			background-color: #fff;
			font-size: 14px;
		}

		input[type="submit"] {
			width: 100%;
			padding: 12px;
			background-color: #f497b6;
			color: white;
			border: none;
			border-radius: 12px;
			font-size: 16px;
			font-weight: bold;
			cursor: pointer;
			transition: background-color 0.3s;
		}

		input[type="submit"]:hover {
			background-color: #e85a98;
		}

		@media (max-width: 500px) {
			.login-box {
				width: 90%;
				padding: 30px 20px;
			}
		}
	</style>
</head>
<body>
	<h2>Login To Floreva</h2>
	<form method="post">
		Email: <input type="email" name="email" required><br>
		Password: <input type="password" name="password" required><br>
		<input type="submit" name="login" value="Login">
	</form>
	<a href="register_user.php">Don't have an account? Register</a>
</body>
</html>