<?php
$con=mysqli_connect("localhost","root","","flowercrafts");
if (mysqli_connect_errno()) 
{
    echo "Failed to connect...".mysqli_error();
    exit();
}
session_start();
if (isset($_POST['submit'])) 
{
	$name=$_POST['name'];
	$email=$_POST['email'];
	$password=$_POST['password'];
    $num = $_POST['num'];

	$image=$_FILES['image']['name'];
	$tmp = $_FILES['image']['tmp_name'];
    $path = "photos/" . $image;
    move_uploaded_file($tmp, $path);

    $q = "INSERT INTO users (name, mobile_number, email, password, image) 
      VALUES ('$name', '$num', '$email', '$password', '$image')";

    
    if (mysqli_query($con,$q)) 
    {
    	echo "<script>alert('Registered Successfully'); window.location='login_user.php';</script>";
    }
    else
    {
    	echo "<script>alert('Error: " . $con->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Floreva-Registeration User</title>
	<style>
        body {
            margin: 0;
            padding: 0;
            background: url('flbg.jpg') no-repeat center center/cover;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-box {
            background-color: rgba(255, 240, 247, 0.95);
            padding: 15px 20px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(245, 170, 200, 0.4);
            border: 1px solid #ffbad0;
            width: 450px;
            text-align: center;
        }

        .register-box h2 {
            color: #d63384;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 18px;
        }

        .frmgr {
            margin-bottom: 14px;
            text-align: left;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #d63384;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1.5px solid #f8c8dc;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 15px; /* 🌸 spacing between fields */
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #ff85a1, #d63384);
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 8px; /* 🌸 spacing above button */
        }

        input[type="submit"]:hover {
            background-color: #e85a98;
        }

        .footer-link {
            text-align: center;
            margin-top: 18px;
            font-size: 14px;
        }

        .footer-link a {
            color: #d63384;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .register-box {
                width: 90%;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="register-box">
        <h2>Register To Floreva</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="frmgr">
               <label for="name">Name:</label>
               <input type="text" name="name" required/>
            </div>
            <div class="frmgr">
               <label for="phno.">Mobile Number:</label>
               <input type="number" name="num" maxlength="10" required/>
            </div>
            <div class="frmgr">
                <label for="email">Email:</label>
               <input type="email" name="email" required/>
            </div>
            <div class="frmgr">
                <label for="password">Password:</label>
               <input type="password" name="password" required/>
            </div>
            <div class="frmgr">
                <label for="image">Profile Image:</label>
               <input type="file" name="image">
            </div>
            <input type="submit" name="submit"  value="Register"/>
        </form>
        <div class="footer-link">
            Already have an account? <a href="login_user.php">Login</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>