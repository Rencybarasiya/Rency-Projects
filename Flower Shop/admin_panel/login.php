<?php 
include 'db.php';
session_start();
$errmsg='';
if (isset($_POST['submit'])) 
{
	$name=$_POST['unm'];
	$email=$_POST['email'];
	$password=$_POST['pass'];
	$q="SELECT * FROM seller where email = '$email' and password = '$password'";
	$result=mysqli_query($con,$q);
	if (mysqli_num_rows($result)>0) 
	{
		while ($row=mysqli_fetch_assoc($result)) 
		{
			$_SESSION['name']=$row['name'];
			header("Location: dashboard.php");
			exit();
		}
	}
	else 
	{
        $error = "Invalid email or password.";
    }
}
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
            background: #ffe6f0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-box {
            background: #fff0f5;
            padding: 40px;
            border-radius: 15px;
            width: 350px;
            box-shadow: 0 0 15px rgba(255, 150, 180, 0.3);
            text-align: center;
        }

        .login-box img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #f8a4c0;
            margin-bottom: 20px;
        }

        .login-box h3 {
            color: #d63384;
            margin-bottom: 20px;
        }

        .login-box label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #b30059;
            font-weight: 500;
        }

        .login-box input[type="text"],
        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #f8a4c0;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .login-box input[type="submit"] {
            margin-top: 20px;
            width: 100%;
            background-color: #ff99bb;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-box input[type="submit"]:hover {
            background-color: #ff6fa3;
        }

        .error {
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
	<div class="login-box">
		<img src="admin.jpeg">
		<h3>Floreva Admin Login</h3>
		<form method="post">

			<label>Name:</label>
			<input type="text" name="unm">
			<label>Email:</label>
			<input type="email" name="email">
			<label>Password:</label>
			<input type="password" name="pass">

			<input type="submit" name="submit" value="Login">
			<?php
				if (!empty($error)) 
				{
					echo "<div class='error'>$error</div>";
				}
			?>
		</form>
	</div>
</body>
</html>