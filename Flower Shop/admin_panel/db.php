<?php
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
	echo "Failed to connect...".mysqli_error();
	exit();
}
?>