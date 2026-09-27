<?php
session_start();
$error = '';

$conn = mysqli_connect("localhost", "root", "", "heycardy"); 
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 1. Admin login
    if ($username === 'admin' && $password === 'greeting123') {
        $_SESSION['user'] = $username;
        header("Location: admin-dashboard.php");
        exit();
    }

    // 2. Regular user login
    $stmt = $conn->prepare("SELECT password_hash FROM user WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $username;
            header("Location: user-dashboard.php");
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Greeting Cards</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Comic Sans MS;
            overflow: hidden;
        }

        .background {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: url('image/greeting-cards.jpg') no-repeat center center/cover;
            filter: blur(3px);
            z-index: -1;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.2);
            z-index: -1;
        }

        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(2px);
            border-radius: 16px;
            padding: 40px 30px;
            width: 350px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-card h2 {
            margin-bottom: 10px;
            color: black;
        }

        .login-card p {
            margin-bottom: 20px;
            color: black;
        }

        .login-card input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        .login-card button {
            padding: 12px 20px;
            width: 100%;
            border: none;
            border-radius: 10px;
            background-color: #a6c1ee;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .login-card button:hover {
            background-color: #849be4;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .greeting-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .register-link {
            margin-top: 20px;
        }

        .register-link a {
            text-decoration: none;
            font-weight: bold;
            color: #333;
            display: inline-block;
            margin-top: 10px;
        }

        .register-link a:hover {
            color: #000;
        }
    </style>
</head>
<body>

<div class="background"></div>
<div class="overlay"></div>

<div class="login-container">
    <div class="login-card">
        <div class="greeting-icon">💌</div>
        <h2>Welcome</h2>
        <p>Login to your Greeting Cards Panel</p>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <div class="register-link">
            <a href="register.php">Create Account</a>
        </div>
    </div>
</div>

</body>
</html>
