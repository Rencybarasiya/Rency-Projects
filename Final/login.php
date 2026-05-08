<?php
session_start();
$error = '';

$conn = mysqli_connect("localhost", "root", "", "fees"); 
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 1. Admin login
    if ($username === 'Admin27' && $password === '@admin2712') {
        $_SESSION['user'] = $username;
        header("Location: welcome.php");
        exit();
    }

  // Regular user login
$stmt = $conn->prepare("SELECT password, class FROM students WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($hashed_password, $class);
    $stmt->fetch();
    if (password_verify($password, $hashed_password)) {
        $_SESSION['username'] = $username;  // ✅ Fix
        $_SESSION['class'] = $class;        // ✅ Fix
        header("Location: user_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
} else {
    $error = "Invalid username or password!";
}

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        *, *:before, *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #151632;
        }
        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }
        .background .shape {
            height: 210px;
            width: 210px;
            position: absolute;
            border-radius: 50%;
        }
        .shape:first-child {
            background: linear-gradient(#264db7, #1b36b7);
            left: -75px;
            top: -85px;
        }
        .shape:last-child {
            background: linear-gradient(to left, red, #f09819);
            right: -80px;
            bottom: -90px;
        }
        form {
            height: 520px;
            width: 400px;
            background-color: rgba(255,255,255,0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 30px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.1);
            box-shadow: 0 0 50px rgba(8,7,16,0.6);
            padding: 50px 35px;
        }
        form * {
            font-family: Comic Sans MS;
            color: #ffffff;
            outline: none;
            border: none;
        }
        form h1 {
            font-size: 40px;
            font-weight: 200;
            line-height: 140px;
            text-align: center;
        }
        .input-container {
            position: relative;
            margin-top: 10px;
            padding: 1px;
            top: -35px;
        }
        .input-container i {
            position: absolute;
            left: 15px;
            top: 58%;
            transform: translateY(-50%);
            color: #2a2f32;
        }
        .input-container input {
            font-family: Comic Sans MS;
            width: 100%;
            padding: 10px 10px 10px 40px;
            height: 45px;
            background-color: rgba(255,255,255,0.07);
            border-radius: 8px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: 200;
            color: #fff;
        }
        ::placeholder {
            color: #d5dee5;
        }
        button {
            margin-top: 8px;
            width: 100px;
            left: 112px;
            position: relative;
            text-align: center;
            height: 45px;
            background-color: #ffffff;
            color: #211c10;
            padding: 4px;
            font-size: 22px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
        }
        .create-account {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.5); /* blur look */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .create-account:hover {
            color: white; /* turn white on hover */
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
</div>

<form method="POST" action="">
    <h1>Login</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="input-container">
        <i class="fas fa-user"></i>
        <input type="text" id="username" name="username" placeholder="Username" required>
    </div>

    <div class="input-container">
        <i class="fas fa-lock"></i>
        <input type="password" id="password" name="password" placeholder="Password" required>
    </div>

    <button type="submit">Log In</button>

    <!-- Create Account Link -->
    <a href="Registration_user.php" class="create-account">Create Account ?</a>
</form>
</body>
</html>   