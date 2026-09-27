<?php
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
	echo "Failed to connect...".mysqli_error();
	exit();
}
session_start();
if (isset($_SESSION['user_id'])) 
{
	header("Location: products.php");
	exit();
}
else
{
	header("Location: login_user.php");
	exit();
}
?>