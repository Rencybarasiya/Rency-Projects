<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    header("Location: home.php");
    exit;}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <meta http-equiv="refresh" content="2;url=login.php"> <!-- Redirect after 5 seconds -->
    <style>
        body {
            text-align: center;
            padding-top: 20px;
            font-family: Arial, sans-serif;
            background-color: #FDAEDB;
        }
        img {
            width: 800px;
        }
        
    </style>
</head>
<body>
    <img src="image/logo1.jpg" alt="Logo">
    
</body>
</html>
